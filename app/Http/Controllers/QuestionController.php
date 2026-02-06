<?php

namespace App\Http\Controllers;

use App\Http\Services\QuestionService;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class QuestionController extends Controller
{
    protected QuestionService $questionService;

    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }

    private function checkPermission()
    {
        if (!auth()->check() || !auth()->user()->canManageContent()) {
            abort(403, 'Bạn không có quyền truy cập chức năng này.');
        }
    }

    /**
     * Hiển thị danh sách câu hỏi của đề thi
     */
    public function index(Request $request, int $examId): View
    {
        $this->checkPermission();

        $filters = $request->only(['search', 'category', 'level', 'section']);
        $data = $this->questionService->getQuestionsByExam($examId, $filters);

        return view('admin.questions.index', $data);
    }

    /**
     * Hiển thị form tạo câu hỏi mới
     */
    public function create(int $examId): View
    {
        $this->checkPermission();
        return view('admin.questions.modals.add', compact('examId'));
    }

    /**
     * Lưu câu hỏi mới
     */
    public function store(Request $request): RedirectResponse
    {
        $this->checkPermission();
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'content' => 'required',
            'section' => 'required',
            'level' => 'required|integer|min:1|max:5',
            'image' => 'nullable|image|max:2048',
            'options' => 'required|array|min:4|max:4',
            'options.*.content' => 'required',
            'correct_answer' => 'required|integer|min:0|max:3',
            'category' => 'nullable|string',
        ]);

        $data = $request->only(['exam_id', 'content', 'section', 'level', 'category']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('questions', 'public');
            $data['image'] = $path;
        }

        $this->questionService->createQuestionWithOptions(
            $data,
            $request->options,
            $request->correct_answer
        );

        return redirect()
            ->route('questions.index', $request->exam_id)
            ->with('success', 'Câu hỏi đã được thêm!');
    }

    /**
     * Hiển thị form chỉnh sửa câu hỏi
     */
    public function edit(int $id): View
    {
        $this->checkPermission();
        $question = $this->questionService->getQuestionForEdit($id);

        return view('admin.questions.modals.edit', compact('question'));
    }

    /**
     * Cập nhật câu hỏi
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $this->checkPermission();
        $request->validate([
            'content' => 'required',
            'section' => 'nullable|string',
            'level' => 'required|integer|min:1|max:5',
            'options' => 'required|array|min:4|max:4',
            'options.*.content' => 'required',
            'correct_answer' => 'required|integer|min:0|max:3',
            'image' => 'nullable|image|max:2048',
        ]);

        $question = $this->questionService->getQuestionForEdit($id);
        $data = $request->only(['content', 'section', 'level']);

        // Xử lý hình ảnh
        if ($request->has('remove_image')) {
            if ($question->image) {
                Storage::disk('public')->delete($question->image);
            }
            $data['image'] = null;
        }

        if ($request->hasFile('image')) {
            if ($question->image) {
                Storage::disk('public')->delete($question->image);
            }
            $path = $request->file('image')->store('questions', 'public');
            $data['image'] = $path;
        }

        $this->questionService->updateQuestionWithOptions(
            $id,
            $data,
            $request->options,
            $request->correct_answer
        );

        // Build redirect URL with preserved filters and page
        $redirectUrl = route('questions.index', $question->exam_id);
        $queryParams = array_filter([
            'page' => $request->query('page'),
            'search' => $request->query('search'),
            'category' => $request->query('category'),
            'level' => $request->query('level'),
            'section' => $request->query('section'),
        ]);

        if (!empty($queryParams)) {
            $redirectUrl .= '?' . http_build_query($queryParams);
        }

        return redirect($redirectUrl)
            ->with('success', 'Câu hỏi đã được cập nhật!');
    }

    /**
     * Xóa câu hỏi
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->checkPermission();
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
        $this->checkPermission();
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
        $this->checkPermission();
        $count = $this->questionService->deleteQuestionsByExam($examId);

        return redirect()
            ->route('exams.show', $examId)
            ->with('success', "Đã xóa {$count} câu hỏi!");
    }
}
