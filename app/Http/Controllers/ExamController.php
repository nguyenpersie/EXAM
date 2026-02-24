<?php

namespace App\Http\Controllers;

use App\Services\ExamService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ExamController extends Controller
{
    protected ExamService $examService;

    public function __construct(ExamService $examService)
    {
        $this->examService = $examService;
    }

    /**
     * Hiển thị danh sách đề thi
     */
    public function index(): View
    {
        $user = auth()->user();
        $exams = $this->examService->getExamsForUser($user);

        return view('exams.home', compact('exams'));
    }

    /**
     * View làm bài thi lay id
     */
    public function test(int $id): View
    {
        $exam = $this->examService->getExamWithQuestions($id);
        return view('exams.test', compact('exam'));
    }

    /**
     * API: Lấy danh sách sections
     */
    public function getSections(int $id): JsonResponse
    {
        $sections = $this->examService->getSections($id);
        return response()->json($sections);
    }

    /**
     * API: Lấy đề thi với câu hỏi
     */
    public function getRandomizedExam(int $id, Request $request): JsonResponse
    {
        $mode = $request->input('mode', 'test');
        $section = $request->input('section');
        $categories = $request->input('categories', []);
        $limit = $request->input('limit', 30);

        $exam = $this->examService->getRandomizedExam($id, $mode, $section, $categories, $limit);

        return response()->json($exam);
    }

    /**
     * Hiển thị danh sách theo danh mục
     */
    public function category(?string $category = null): View
    {
        $exams = $this->examService->getExamsByCategory($category);
        $categories = $this->examService->getAllCategories();

        return view('exams.category', compact('exams', 'categories', 'category'));
    }

    /**
     * Tạo đề thi mới
     */
    public function create(): View|RedirectResponse
    {
        if (!$this->examService->canUserManageExam(auth()->user())) {
            abort(403, 'Bạn không có quyền tạo đề thi.');
        }

        return view('exams.create');
    }

    /**
     * Lưu đề thi mới
     */
    public function store(Request $request): RedirectResponse
    {
        if (!$this->examService->canUserManageExam(auth()->user())) {
            abort(403, 'Bạn không có quyền tạo đề thi.');
        }

        $validated = $request->validate([
            'code' => 'required|unique:exams',
            'title' => 'required',
            'duration_minutes' => 'required|integer|min:1',
            'total_score' => 'required|numeric|min:0',
            'passing_score' => 'required|numeric|min:0',
            'category' => 'nullable|string',
        ]);

        $exam = $this->examService->createExam($validated);

        return redirect()->route('exams.show', $exam->id)
            ->with('success', 'Đề thi đã được tạo thành công!');
    }

    /**
     * Hiển thị chi tiết đề thi
     */
    public function show(int $id): View
    {
        $exam = $this->examService->getExamWithQuestions($id);
        return view('exams.show', compact('exam'));
    }

    /**
     * Chỉnh sửa đề thi
     */
    public function edit(int $id): View|RedirectResponse
    {
        if (!$this->examService->canUserManageExam(auth()->user())) {
            abort(403, 'Bạn không có quyền sửa đề thi.');
        }

        $exam = $this->examService->getExamWithQuestions($id);
        return view('exams.edit', compact('exam'));
    }

    /**
     * Cập nhật đề thi
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        if (!$this->examService->canUserManageExam(auth()->user())) {
            abort(403, 'Bạn không có quyền cập nhật đề thi.');
        }

        $validated = $request->validate([
            'code' => 'required|unique:exams,code,' . $id,
            'title' => 'required',
            'duration_minutes' => 'required|integer|min:1',
            'total_score' => 'required|numeric|min:0',
            'passing_score' => 'required|numeric|min:0',
            'category' => 'nullable|string',
        ]);

        $this->examService->updateExam($id, $validated);

        return redirect()->route('exams.show', $id)
            ->with('success', 'Đề thi đã được cập nhật!');
    }

    /**
     * Xóa đề thi
     */
    public function destroy(int $id): RedirectResponse
    {
        if (!$this->examService->canUserManageExam(auth()->user())) {
            abort(403, 'Bạn không có quyền xóa đề thi.');
        }

        $this->examService->deleteExam($id);

        return redirect()->route('exams.home')
            ->with('success', 'Đề thi và tất cả câu hỏi đã được xóa!');
    }
}
