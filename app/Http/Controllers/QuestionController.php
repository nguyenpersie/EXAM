<?php

namespace App\Http\Controllers;

use App\Http\Services\QuestionService;
use App\Models\Question;
use Illuminate\Http\Request;
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
