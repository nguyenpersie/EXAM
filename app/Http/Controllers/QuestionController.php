<?php

namespace App\Http\Controllers;

use App\Http\Services\QuestionService;
use App\Models\Exam;
use App\Models\Option;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class QuestionController extends Controller
{
    protected QuestionService $questionService;

    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }
    public function index($examId): View
    {
        $exam = Exam::findOrFail($examId);
        $questions = $exam->questions()->with('options')->paginate(20);

        // Lấy danh sách categories và sections từ câu hỏi của đề thi này
        $categories = $exam->questions()->whereNotNull('category')->distinct()->pluck('category');
        $sections = $exam->questions()->whereNotNull('section')->distinct()->pluck('section');

        return view('admin.questions.index', compact('exam', 'questions', 'categories', 'sections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.questions.modals.add', compact('addExam'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required',
            'section' => 'required|in:I,II,III',
            'level' => 'required|integer|min:1|max:5',
        ]);

        $this->questionService->createQuestion($validated);

        return redirect()->route('questions.index')->with('success', 'Câu hỏi đã được thêm!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $question = Question::with('options')->findOrFail($id);
        return view('admin.questions.modals.edit', compact('question'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'content' => 'required',
            'section' => 'required|in:I,II,III',
            'level' => 'required|integer|min:1|max:5',
            'options' => 'required|array|min:4|max:4',
            'options.*.content' => 'required',
            'correct_option' => 'required|integer|min:0|max:3',
        ]);

        $question = Question::findOrFail($id);
        $question->update($request->only(['content', 'section', 'level']));

        $question->options()->delete(); // Xóa đáp án cũ

        foreach ($request->options as $index => $option) {
            $question->options()->create([
                'content' => $option['content'],
                'is_correct' => $index == $request->correct_option,
            ]);
        }

        return redirect()->route('questions.index')->with('success', 'Câu hỏi đã được cập nhật!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $question = Question::findOrFail($id);
        $question->delete();
        return redirect()->route('questions.index')->with('success', 'Câu hỏi đã được xóa!');
    }

    /**
     * Import câu hỏi từ file Word
     */
    public function import(Request $request, $examId)
    {
        $request->validate([
            'file' => 'required|mimes:docx,doc',
            'category' => 'nullable|string',
        ]);

        $exam = Exam::findOrFail($examId);

        try {
            DB::beginTransaction();

            $file = $request->file('file');
            $data = $this->parseWord($file);

            $importedCount = 0;
            $category = $request->category;

            foreach ($data as $row) {
                // Bỏ qua câu hỏi trống
                if (empty($row['question'])) {
                    continue;
                }

                $question = Question::create([
                    'exam_id' => $exam->id,
                    'content' => $row['question'],
                    'section' => $row['section'] ?? null,
                    'level' => $row['level'] ?? 'medium',
                    'category' => $category ?? $row['category'] ?? null,
                ]);

                // Tạo 4 đáp án
                foreach (['A', 'B', 'C', 'D'] as $index => $letter) {
                    if (!empty($row['option_' . strtolower($letter)])) {
                        Option::create([
                            'question_id' => $question->id,
                            'content' => $row['option_' . strtolower($letter)],
                            'is_correct' => (strtoupper($row['correct_answer']) === $letter),
                        ]);
                    }
                }

                $importedCount++;
            }

            //     // Tạo câu hỏi
            //     $question = Question::create([
            //         'exam_id' => $exam->id,
            //         'content' => $row['question'],
            //         'category' => $category,
            //     ]);

            //     // Tạo đáp án A B C D
            //     foreach (['A', 'B', 'C', 'D'] as $letter) {
            //         $key = 'option_' . strtolower($letter);

            //         if (!empty($row[$key])) {
            //             Option::create([
            //                 'question_id' => $question->id,
            //                 'content' => $row[$key],
            //                 'is_correct' => strtoupper($row['correct_answer']) === $letter,
            //             ]);
            //         }
            //     }

            //     $importedCount++;
            // }

            //     if (!empty($row['option_' . strtolower($letter)])) {
            //             Option::create([
            //                 'question_id' => $question->id,
            //                 'content' => $row['option_' . strtolower($letter)],
            //                 'is_correct' => (strtoupper($row['correct_answer']) === $letter),
            //             ]);
            //         }
            //     }

            //     $importedCount++;
            // }

            DB::commit();

            return redirect()->route('exams.show', $exam->id)
                ->with('success', "Đã import thành công {$importedCount} câu hỏi!");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi import: ' . $e->getMessage());
        }
    }

    /**
     * Parse file Word (.docx)
     */
    private function parseWord($file)
    {
        $phpWord = \PhpOffice\PhpWord\IOFactory::load($file->getRealPath());
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
            if (empty($block))
                continue;

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
    private function readSection($section)
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
    private function readElement($element)
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
    // private function parseQuestionBlock($block)
    // {
    //     $lines = explode("\n", $block);
    //     $data = [
    //         'question' => '',
    //         'option_a' => '',
    //         'option_b' => '',
    //         'option_c' => '',
    //         'option_d' => '',
    //         'correct_answer' => '',
    //         'section' => null,
    //         'level' => '2',
    //         'category' => null,
    //     ];

    //     $questionLines = [];

    //     foreach ($lines as $line) {
    //         $line = trim($line);
    //         if (empty($line))
    //             continue;

    //         // Đáp án A, B, C, D
    //         if (preg_match('/^([A-D])[.\)]\s*(.+)$/i', $line, $matches)) {
    //             $letter = strtoupper($matches[1]);
    //             $data['option_' . strtolower($letter)] = trim($matches[2]);
    //         }
    //         // Đáp án đúng
    //         elseif (preg_match('/^(Đáp án|ĐA|Correct|Answer)[\s:]+([A-D])/i', $line, $matches)) {
    //             $data['correct_answer'] = strtoupper($matches[2]);
    //         }
    //         // Phần
    //         elseif (preg_match('/^(Phần|Section)[\s:]+(.+)$/i', $line, $matches)) {
    //             $data['section'] = trim($matches[2]);
    //         }
    //         // Độ khó
    //         elseif (preg_match('/^(Độ khó|Level)[\s:]+(easy|medium|hard|dễ|trung bình|khó)/i', $line, $matches)) {
    //             $level = strtolower($matches[2]);
    //             if (in_array($level, ['1', '1']))
    //                 $data['level'] = '1';
    //             elseif (in_array($level, ['3', '3']))
    //                 $data['level'] = '3';
    //             else
    //                 $data['level'] = '2';
    //         }
    //         // Danh mục
    //         elseif (preg_match('/^(Danh mục|Category)[\s:]+(.+)$/i', $line, $matches)) {
    //             $data['category'] = trim($matches[2]);
    //         }
    //         // Nội dung câu hỏi
    //         else {
    //             $questionLines[] = $line;
    //         }
    //     }

    //     $data['question'] = implode(' ', $questionLines);

    //     // Validate: phải có câu hỏi và ít nhất 2 đáp án
    //     if (
    //         empty($data['question']) ||
    //         (empty($data['option_a']) && empty($data['option_b']))
    //     ) {
    //         return null;
    //     }

    //     return $data;
    // }

    private function parseQuestionBlock($block)
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
            if (empty($line) || preg_match('/^-{3,}|^={3,}/', $line))
                continue;

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
                if (in_array($level, ['1', '1']))
                    $data['level'] = '1';
                elseif (in_array($level, ['3', '3']))
                    $data['level'] = '3';
                else
                    $data['level'] = '2';
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

        // Validate: phải có câu hỏi và ít nhất 2 đáp án
        if (empty($data['question'])) {
            return null;
        }

        // Đếm số đáp án
        $optionCount = 0;
        foreach (['a', 'b', 'c', 'd'] as $letter) {
            if (!empty($data['option_' . $letter]))
                $optionCount++;
        }

        if ($optionCount < 2) {
            return null;
        }

        return $data;
    }

    /**
     * Xóa toàn bộ câu hỏi của 1 đề
     */
    public function destroyAll($examId)
    {
        $exam = Exam::findOrFail($examId);
        $count = $exam->questions()->count();
        $exam->questions()->delete();

        return redirect()->route('exams.show', $exam->id)
            ->with('success', "Đã xóa {$count} câu hỏi!");
    }
}
