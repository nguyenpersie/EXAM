<?php

namespace App\Http\Services;

use PhpOffice\PhpWord\IOFactory;

class WordImportService
{
    /**
     * Parse file Word (.docx) thành danh sách câu hỏi
     */
    public function parseFile($file): array
    {
        $phpWord = IOFactory::load($file->getRealPath());
        $fullText = '';

        // Đọc toàn bộ text
        foreach ($phpWord->getSections() as $section) {
            $fullText .= $this->readSection($section);
        }

        return $this->parseText($fullText);
    }

    /**
     * Parse text thành danh sách câu hỏi
     * Check separator --- hoặc === trước
     */
    public function parseText(string $fullText): array
    {
        // 1. Thử tách bằng dấu phân cách --- hoặc ===
        // Sử dụng PREG_SPLIT_NO_EMPTY để loại bỏ phần rỗng
        $blocks = preg_split('/-{3,}|={3,}/', $fullText, -1, PREG_SPLIT_NO_EMPTY);

        // Nếu chỉ tìm thấy 1 block (có thể là không có dấu phân cách hoặc chỉ có 1 câu),
        // và block đó chứa pattern "Câu [số]" hoặc "Question [số]", ta thử fallback về cách cũ.
        // Tuy nhiên, để an toàn cho trường hợp user KHÔNG dùng "Câu số",
        // ta chỉ fallback nếu thực sự tìm thấy pattern chia câu Ở ĐẦU DÒNG.

        // Logic cũ: Regex split
        $regexOld = '/(?=(?:^|\n)\s*(?:Câu|Cau|Question)?\s*\d+[:.)])/ui';

        // Nếu không tách được bằng --- (tức là chỉ có < 2 block hoặc user không dùng ---)
        // Ta kiểm tra xem có nên dùng cách cũ không.
        // Lưu ý: Nếu user dùng format mới (không "Câu số") mà không có ---, thì sẽ coi là 1 câu hỏi duy nhất.
        // Để hỗ trợ backward compatible tốt nhất:
        // Nếu số block < 2, ta thử split bằng regex cũ. Nếu regex cũ ra > 1 block thì dùng regex cũ.
        if (count($blocks) < 2) {
            $blocksOld = preg_split($regexOld, $fullText, -1, PREG_SPLIT_NO_EMPTY);
            if (count($blocksOld) > 1) {
                $blocks = $blocksOld;
            }
        }

        $data = [];
        foreach ($blocks as $block) {
            $block = trim($block);
            if (empty($block) || mb_strlen($block) < 10) {
                continue;
            }

            $question = $this->parseQuestionBlock($block);
            if ($question && !empty($question['question']) && !empty($question['option_a']) && !empty($question['option_b'])) {
                $data[] = $question;
            }
        }

        return $data;
    }

    /**
     * Đọc section Word
     */
    private function readSection($section): string
    {
        $text = '';
        foreach ($section->getElements() as $element) {
            $text .= $this->readElement($element);
        }
        return $text;
    }

    /**
     * Đọc element Word (đệ quy)
     */
    private function readElement($element): string
    {
        $text = '';

        if (method_exists($element, 'getText')) {
            $text .= $element->getText() . "\n";
        }

        if (method_exists($element, 'getElements')) {
            foreach ($element->getElements() as $childElement) {
                $text .= $this->readElement($childElement);
            }
        }

        return $text;
    }

    /**
     * Parse 1 block câu hỏi
     */
    private function parseQuestionBlock(string $block): ?array
    {
        $lines = array_filter(array_map('trim', explode("\n", $block)));
        $lines = array_values($lines); // Re-index keys

        $data = [
            'question' => '',
            'option_a' => '',
            'option_b' => '',
            'option_c' => '',
            'option_d' => '',
            'correct_answer' => '',
            'section' => null,
            'level' => '1',
            'category' => null,
        ];

        $contentLines = [];

        // 1. Tách Metadata và Nội dung
        foreach ($lines as $line) {
            // Bỏ qua separator nếu còn sót
            if (preg_match('/^-{3,}|^={3,}/', $line)) {
                continue;
            }

            // Đáp án đúng
            if (preg_match('/^(?:Đáp án|ĐA|Correct|Answer|Key)[\s:]+([A-D])/i', $line, $matches)) {
                $data['correct_answer'] = strtoupper($matches[1]);
                continue;
            }
            // Phần
            if (preg_match('/^(?:Phần|Section)[\s:]+(.+)$/i', $line, $matches)) {
                $data['section'] = trim($matches[2]);
                continue;
            }
            // Độ khó
            if (preg_match('/^(?:Độ khó|Level)[\s:]+(.+)$/i', $line, $matches)) {
                $levelRaw = strtolower(trim($matches[1]));
                if (in_array($levelRaw, ['easy', 'dễ', '1'])) {
                    $data['level'] = '1';
                } elseif (in_array($levelRaw, ['hard', 'khó', '3'])) {
                    $data['level'] = '3';
                } else {
                    $data['level'] = '2';
                }
                continue;
            }
            // Danh mục
            if (preg_match('/^(?:Danh mục|Category)[\s:]+(.+)$/i', $line, $matches)) {
                $data['category'] = trim($matches[2]);
                continue;
            }

            // Dòng nội dung (Câu hỏi hoặc Đáp án)
            $contentLines[] = $line;
        }

        // 2. Parse Question & Options từ ContentLines
        $tempOptions = [];
        $questionParts = [];
        $hasExplicitOptions = false;

        foreach ($contentLines as $line) {
            // Check Explicit Option (A. ...)
            if (preg_match('/^([A-D])[.:)]\s*(.+)$/i', $line, $matches)) {
                $letter = strtolower($matches[1]);
                $tempOptions[$letter] = trim($matches[2]);
                $hasExplicitOptions = true;
            } else {
                // Nếu đã tìm thấy option rồi mà gặp dòng không khớp -> có thể là option tiếp theo bị lỗi format hoặc garbage.
                // Nhưng trong logic đơn giản, nếu chưa thấy option thì nó là câu hỏi.
                if (!$hasExplicitOptions) {
                    $questionParts[] = $line;
                }
            }
        }

        // 3. Xử lý kết quả
        if ($hasExplicitOptions && count($tempOptions) >= 2) {
            // Case 1: Có đáp án rõ ràng (A., B...)
            foreach ($tempOptions as $k => $v) {
                $data['option_' . $k] = $v;
            }
            $data['question'] = implode(' ', $questionParts);
        } else {
            // Case 2: Fallback - Không có prefix A,B,C,D -> Lấy 4 dòng cuối cùng làm đáp án
            // Yêu cầu tối thiểu 5 dòng (1 câu hỏi + 4 đáp án)
            if (count($contentLines) >= 5) {
                $data['option_d'] = array_pop($contentLines);
                $data['option_c'] = array_pop($contentLines);
                $data['option_b'] = array_pop($contentLines);
                $data['option_a'] = array_pop($contentLines);
                $data['question'] = implode(' ', $contentLines);
            } else {
                // Không đủ dữ liệu -> Bỏ qua
                return null;
            }
        }

        // 4. Cleanup Question Text
        // Xóa "Câu 1:" ở đầu câu hỏi nếu có
        $data['question'] = preg_replace('/^(?:Câu|Cau|Question)?\s*\d+[:.)]\s*/ui', '', $data['question']);

        // Validate final data
        if (empty($data['question']) || empty($data['option_a']) || empty($data['option_b'])) {
            return null;
        }

        return $data;
    }
}
