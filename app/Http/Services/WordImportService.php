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

        // Tách theo "Câu [số]:" hoặc dấu phân cách
        $blocks = preg_split('/(?=Câu\s+\d+[:.])/', $fullText, -1, PREG_SPLIT_NO_EMPTY);

        $data = [];
        foreach ($blocks as $block) {
            $block = trim($block);
            if (empty($block)) {
                continue;
            }

            // Nếu có dấu phân cách ---, tách tiếp
            $subBlocks = preg_split('/-{3,}|={3,}/', $block, -1, PREG_SPLIT_NO_EMPTY);

            foreach ($subBlocks as $subBlock) {
                $question = $this->parseQuestionBlock(trim($subBlock));
                if ($question && !empty($question['question'])) {
                    $data[] = $question;
                }
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

            // Bỏ số câu hỏi nếu có
            $line = preg_replace('/^Câu\s+\d+[:.]\s*/i', '', $line);

            // Đáp án A, B, C, D
            if (preg_match('/^([A-D])[.\)]\s*(.+)$/i', $line, $matches)) {
                $letter = strtoupper($matches[1]);
                $data['option_' . strtolower($letter)] = trim($matches[2]);
                $foundOptions = true;
            }
            // Đáp án đúng
            elseif (preg_match('/^(Đáp án|ĐA|Correct|Answer)[\s:]+([A-D])/i', $line, $matches)) {
                $data['correct_answer'] = strtoupper($matches[2]);
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
