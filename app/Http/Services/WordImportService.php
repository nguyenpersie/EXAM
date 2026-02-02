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

        $data = [
            'question' => '',
            'option_a' => '',
            'option_b' => '',
            'option_c' => '',
            'option_d' => '',
            'correct_answer' => '',
            'section' => null,
            'level' => 'medium',
            'category' => null,
        ];

        $questionLines = [];
        $foundOptions = false;

        foreach ($lines as $line) {
            // Bỏ qua dòng trống và dấu phân cách
            if (empty($line) || preg_match('/^-{3,}|^={3,}/', $line)) {
                continue;
            }

            // Bỏ số câu hỏi nếu có (VD: "Câu 1:", "1.", "Question 1:")
            $line = preg_replace('/^(?:Câu|Cau|Question)?\s*\d+[:.)]\s*/ui', '', $line);

            // Đáp án A, B, C, D (VD: "A. Nội dung", "a) Nội dung")
            if (preg_match('/^([A-D])[.:)]\s*(.+)$/i', $line, $matches)) {
                $letter = strtoupper($matches[1]);
                $data['option_' . strtolower($letter)] = trim($matches[2]);
                $foundOptions = true;
            }
            // Đáp án đúng (VD: "Đáp án: A", "ĐA: A", "Correct: A")
            elseif (preg_match('/^(?:Đáp án|ĐA|Correct|Answer|Key)[\s:]+([A-D])/i', $line, $matches)) {
                $data['correct_answer'] = strtoupper($matches[1]);
            }
            // Phần
            elseif (preg_match('/^(Phần|Section)[\s:]+(.+)$/i', $line, $matches)) {
                $data['section'] = trim($matches[2]);
            }
            // Độ khó
            elseif (preg_match('/^(Độ khó|Level)[\s:]+(easy|medium|hard|dễ|trung bình|khó)/i', $line, $matches)) {
                $level = strtolower(trim($matches[2]));
                if (in_array($level, ['easy', 'dễ'])) {
                    $data['level'] = '1';
                } elseif (in_array($level, ['hard', 'khó'])) {
                    $data['level'] = '3';
                } else {
                    $data['level'] = '2';
                }
            }
            // Danh mục
            elseif (preg_match('/^(Danh mục|Category)[\s:]+(.+)$/i', $line, $matches)) {
                $data['category'] = trim($matches[2]);
            }
            // Nội dung câu hỏi (chỉ lấy trước khi gặp đáp án)
            elseif (!$foundOptions) {
                $questionLines[] = $line;
            }
        }

        $data['question'] = implode(' ', $questionLines);

        // Validate: phải có câu hỏi
        if (empty($data['question'])) {
            return null;
        }

        // Đếm số đáp án (phải có ít nhất 2)
        $optionCount = 0;
        foreach (['a', 'b', 'c', 'd'] as $letter) {
            if (!empty($data['option_' . $letter])) {
                $optionCount++;
            }
        }

        if ($optionCount < 2) {
            return null;
        }

        return $data;
    }
}
