<?php

namespace App\Http\Controllers;

use App\Http\Services\QuestionService;
use App\Models\Exam;
use App\Models\Option;
use App\Models\Question;
use Illuminate\Http\Request;
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
    public function index(): View
    {
        $questions = $this->questionService->getPaginatedQuestions();
        return view('admin.questions.index', compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.questions.modals.add', compact('addExam'));
    }

    public function import(Request $request, $id)
    {
        $request->validate([
            'word_file' => 'required|file|mimes:docx|max:5120', // Max 5MB
        ]);

        $exam = Exam::findOrFail($id);
        $file = $request->file('word_file');
        $phpWord = IOFactory::load($file->getPathname());

        $paragraphs = [];
        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
                    $text = '';
                    foreach ($element->getElements() as $textElement) {
                        if ($textElement instanceof \PhpOffice\PhpWord\Element\Text) {
                            $text .= $textElement->getText();
                        } elseif ($textElement instanceof \PhpOffice\PhpWord\Element\Image) {
                            // Xử lý hình ảnh
                            $imageContent = $textElement->getMediaId();
                            $imagePath = $textElement->getPath();
                            $filename = uniqid() . '.png';
                            $path = 'questions/' . $filename;
                            Storage::disk('public')->put($path, file_get_contents($imagePath));
                            $text .= '<img src="' . asset('storage/' . $path) . '" alt="Hình minh họa" style="max-width: 100%;">';
                        }
                    }
                    $text = trim($text);
                    if (!empty($text)) {
                        $paragraphs[] = $text;
                    }
                }
            }
        }

        $questions = [];
        $currentQuestion = null;
        $options = [];

        foreach ($paragraphs as $line) {
            $line = trim($line);

            if (preg_match('/^Câu\s*\d+\./iu', $line)) { // Câu hỏi bắt đầu bằng "Câu 31."
                if ($currentQuestion) {
                    $questions[] = [
                        'content' => $currentQuestion,
                        'options' => $options,
                    ];
                }
                $currentQuestion = $line;
                $options = [];
            } elseif (preg_match('/^([a-d])\./iu', $line, $matches)) { // Đáp án a., b., c., d.
                $letter = strtoupper($matches[1]);
                $isCorrect = str_starts_with($line, '*');
                $content = trim(str_replace('*', '', $line));

                $options[] = [
                    'letter' => $letter,
                    'content' => $content,
                    'is_correct' => $isCorrect ? 1 : 0,
                ];
            }
        }

        // Lưu câu hỏi cuối
        if ($currentQuestion) {
            $questions[] = [
                'content' => $currentQuestion,
                'options' => $options,
            ];
        }

        // Insert vào DB
        $inserted = 0;
        foreach ($questions as $qData) {
            if (count($qData['options']) < 4) continue; // Bỏ nếu không đủ 4 đáp án

            $question = Question::create([
                'exam_id' => $exam->id,
                'content' => $qData['content'],
                'section' => 'I', // Có thể parse từ Word nếu có
                'level' => 3,
            ]);

            foreach ($qData['options'] as $opt) {
                Option::create([
                    'question_id' => $question->id,
                    'content' => $opt['letter'] . '. ' . $opt['content'],
                    'is_correct' => $opt['is_correct'],
                ]);
            }

            $inserted++;
        }

        return back()->with('success', "Đã import {$inserted} câu hỏi thành công!");
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
    public function edit(string $id)
    {
        return view('admin.questions.edit', compact('question'));
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
        $question->delete();
        return redirect()->route('questions.index')->with('success', 'Câu hỏi đã được xóa!');
    }
}
