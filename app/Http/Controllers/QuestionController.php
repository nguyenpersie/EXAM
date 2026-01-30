<?php

namespace App\Http\Controllers;

use App\Http\Services\QuestionService;
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

    /**
     * Hiển thị danh sách câu hỏi của đề thi
     */
    public function index(int $examId): View
    {
        $data = $this->questionService->getQuestionsByExam($examId);

        return view('admin.questions.index', $data);
    }

    /**
     * Hiển thị form tạo câu hỏi mới
     */
    public function create(int $examId): View
    {
        return view('admin.questions.modals.add', compact('examId'));
    }

    /**
     * Lưu câu hỏi mới
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'content' => 'required',
            'section' => 'required|in:I,II,III',
            'level' => 'required|integer|min:1|max:5',
        ]);

        $this->questionService->createQuestion($validated);

        return redirect()
            ->route('questions.index', $request->exam_id)
            ->with('success', 'Câu hỏi đã được thêm!');
    }

    /**
     * Hiển thị form chỉnh sửa câu hỏi
     */
    public function edit(int $id): View
    {
        $question = $this->questionService->getQuestionForEdit($id);

        return view('admin.questions.modals.edit', compact('question'));
    }

    /**
     * Cập nhật câu hỏi
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'content' => 'required',
            'section' => 'nullable|string',
            'level' => 'required|integer|min:1|max:5',
            'options' => 'required|array|min:4|max:4',
            'options.*.content' => 'required',
            'correct_answer' => 'required|integer|min:0|max:3',
        ]);

        $question = $this->questionService->updateQuestionWithOptions(
            $id,
            $request->only(['content', 'section', 'level']),
            $request->options,
            $request->correct_answer
        );

        return redirect()
            ->route('questions.index', $question->exam_id)
            ->with('success', 'Câu hỏi đã được cập nhật!');
    }

    /**
     * Xóa câu hỏi
     */
    public function destroy(int $id): RedirectResponse
    {
        $question = $this->questionService->getQuestionForEdit($id);
        $examId = $question->exam_id;

        $this->questionService->deleteQuestion($id);

        return redirect()
            ->route('questions.index', $examId)
            ->with('success', 'Câu hỏi đã được xóa!');
    }

    /**
     * Import câu hỏi từ file Word
     */
    public function import(Request $request, int $examId): RedirectResponse
    {
        $request->validate([
            'file' => 'required|mimes:docx,doc',
            'category' => 'nullable|string',
        ]);

        try {
            $importedCount = $this->questionService->importFromWord(
                $request->file('file'),
                $examId,
                $request->category
            );

            return redirect()
                ->route('exams.show', $examId)
                ->with('success', "Đã import thành công {$importedCount} câu hỏi!");

        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi import: ' . $e->getMessage());
        }
    }

    /**
     * Xóa toàn bộ câu hỏi của đề thi
     */
    public function destroyAll(int $examId): RedirectResponse
    {
        $count = $this->questionService->deleteQuestionsByExam($examId);

        return redirect()
            ->route('exams.show', $examId)
            ->with('success', "Đã xóa {$count} câu hỏi!");
    }
}
