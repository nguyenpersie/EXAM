<?php

namespace App\Http\Services;

use PhpOffice\PhpWord\IOFactory;

class WordImportService
{
    private $skippedQuestions = [];
    private $errors = [];

    /**
     * Parse file Word (.docx) thành danh sách câu hỏi
     */
    public function parseFile($file): array
    {
        $this->skippedQuestions = [];
        $this->errors = [];

        $phpWord = IOFactory::load($file->getRealPath());
        $fullText = '';

        // Đọc toàn bộ text
        foreach ($phpWord->getSections() as $section) {
            $fullText .= $this->readSection($section);
        }

        \Log::info('Full text length: ' . mb_strlen($fullText));
        \Log::info('First 500 chars: ' . mb_substr($fullText, 0, 500));

        // Tách theo nhiều pattern khác nhau
        // Pattern 1: "Câu 1:", "Câu 1.", "Question 1:"
        // Pattern 2: "1.", "1)", "1:"
        // Pattern 3: Dấu phân cách --- hoặc ===
        $blocks = preg_split(
            '/(?=(?:^|\n)\s*(?:Câu|Cau|Question|Q)?\s*\d+[:.)])|(?:\n-{3,})|(?:\n={3,})/ui',
            $fullText,
            -1,
            PREG_SPLIT_NO_EMPTY
        );

        \Log::info('Total blocks after split: ' . count($blocks));

        $data = [];
        foreach ($blocks as $index => $block) {
            $block = trim($block);

            if (empty($block)) {
                continue;
            }

            // KHÔNG bỏ qua block ngắn nữa - để parse thử xem
            \Log::info("Parsing block {$index}, length: " . mb_strlen($block));
            \Log::info("Block preview: " . mb_substr($block, 0, 200));

            $question = $this->parseQuestionBlock($block, $index);

            if ($question) {
                // Validate có ít nhất câu hỏi và 2 đáp án
                if (
                    !empty($question['question']) &&
                    !empty($question['option_a']) &&
                    !empty($question['option_b'])
                ) {
                    $data[] = $question;
                    \Log::info("✓ Block {$index} parsed successfully");
                } else {
                    $this->skippedQuestions[] = [
                        'block' => $index,
                        'reason' => 'Missing question or options',
                        'data' => $question
                    ];
                    \Log::warning("✗ Block {$index} skipped: Missing required fields");
                }
            } else {
                $this->skippedQuestions[] = [
                    'block' => $index,
                    'reason' => 'Parse failed',
                    'preview' => mb_substr($block, 0, 100)
                ];
                \Log::warning("✗ Block {$index} parse failed");
            }
        }

        \Log::info("Successfully parsed: " . count($data) . " questions");
        \Log::info("Skipped: " . count($this->skippedQuestions) . " blocks");

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
    private function parseQuestionBlock(string $block, int $blockIndex): ?array
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
            'level' => '2',
            'category' => null,
        ];

        $questionLines = [];
        $foundOptions = false;

        foreach ($lines as $lineIndex => $line) {
            if (empty($line))
                continue;

            // Bỏ dấu phân cách
            if (preg_match('/^-{3,}|^={3,}/', $line)) {
                continue;
            }

            // Bỏ số câu hỏi nếu có
            $cleanLine = preg_replace('/^(?:Câu|Cau|Question|Q)?\s*\d+[:.)]\s*/ui', '', $line);

            // Đáp án A, B, C, D - RELAXED PATTERN
            // Chấp nhận: "A.", "A)", "A:", "A -", "a.", "a)"
            if (preg_match('/^([A-Da-d])\s*[.:)\-–—]\s*(.+)$/u', $cleanLine, $matches)) {
                $letter = strtoupper($matches[1]);
                $content = trim($matches[2]);

                if (!empty($content)) {
                    $data['option_' . strtolower($letter)] = $content;
                    $foundOptions = true;
                    \Log::debug("Found option {$letter}: " . mb_substr($content, 0, 50));
                }
            }
            // Đáp án đúng - nhiều pattern hơn
            elseif (preg_match('/(?:Đáp án|ĐA|DA|Correct|Answer|Key|Dap an)[\s:]*([A-D])/i', $cleanLine, $matches)) {
                $data['correct_answer'] = strtoupper($matches[1]);
                \Log::debug("Found correct answer: " . $data['correct_answer']);
            }
            // Phần
            elseif (preg_match('/^(Phần|Phan|Section)[\s:]+(.+)$/i', $cleanLine, $matches)) {
                $data['section'] = trim($matches[2]);
            }
            // Độ khó - flexible matching
            elseif (preg_match('/(?:Độ khó|Do kho|Level)[\s:]+(easy|medium|hard|dễ|de|trung bình|tb|khó|kho|[1-5])/i', $cleanLine, $matches)) {
                $levelStr = strtolower(trim($matches[1]));

                // Map to numeric level
                if (in_array($levelStr, ['easy', 'dễ', 'de', '1'])) {
                    $data['level'] = '1';
                } elseif (in_array($levelStr, ['hard', 'khó', 'kho', '5'])) {
                    $data['level'] = '5';
                } elseif (in_array($levelStr, ['3'])) {
                    $data['level'] = '3';
                } elseif (in_array($levelStr, ['4'])) {
                    $data['level'] = '4';
                } else {
                    $data['level'] = '2';
                }
            }
            // Danh mục
            elseif (preg_match('/^(Danh mục|Danh muc|Category)[\s:]+(.+)$/i', $cleanLine, $matches)) {
                $data['category'] = trim($matches[2]);
            }
            // Nội dung câu hỏi (chỉ lấy trước khi gặp đáp án)
            elseif (!$foundOptions && !empty($cleanLine)) {
                // Bỏ qua các dòng chỉ là số hoặc ký tự đặc biệt
                if (!preg_match('/^\d+$/', $cleanLine) && mb_strlen($cleanLine) > 2) {
                    $questionLines[] = $cleanLine;
                }
            }
        }

        $data['question'] = implode(' ', $questionLines);

        // Nếu không có đáp án đúng, mặc định là A
        if (empty($data['correct_answer']) && $foundOptions) {
            $data['correct_answer'] = 'A';
            \Log::warning("Block {$blockIndex}: No correct answer specified, defaulting to A");
        }

        return $data;
    }

    /**
     * Lấy danh sách câu hỏi bị skip
     */
    public function getSkippedQuestions(): array
    {
        return $this->skippedQuestions;
    }

    /**
     * Lấy log errors
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
