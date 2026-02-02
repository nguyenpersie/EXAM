<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    // public function test($id)
    // {
    //     $exam = Exam::with('questions.options')->findOrFail(67);
    //     return view('pages.test', compact('exam'));
    // }

    public function index()
    {
        $query = Exam::withCount('questions');
        $user = auth()->user();

        if ($user && !$user->isAdmin()) {
            if ($user->category) {
                $query->where('category', $user->category);
            }
        }

        $exams = $query->get();
        return view('pages.exams.home', compact('exams'));
    }

    /**
     * Bắt đầu làm bài thi (Trộn ngẫu nhiên câu hỏi)
     */
    public function test($id)
    {
        $exam = Exam::with('questions.options')->findOrFail($id);

        // Không trộn ở đây, để JavaScript xử lý
        // Vì mỗi lần reload sẽ trộn lại

        return view('pages.exams.test', compact('exam'));
    }

    /**
     * API: Lấy đề thi với 30 câu hỏi ngẫu nhiên (có phân loại)
     */
    public function getRandomizedExam($id, Request $request)
    {
        $exam = Exam::findOrFail($id);

        // Lấy categories được chọn (nếu có)
        $categories = $request->input('categories', []);
        $limit = $request->input('limit', 30);

        // Lấy câu hỏi random
        $questions = $exam->getRandomQuestionsByCategory($limit, $categories);

        // Trộn đáp án của từng câu
        $questions = $questions->map(function ($question) {
            $question->options = $question->options->shuffle();
            return $question;
        });

        $exam->questions = $questions;

        return response()->json($exam);
    }

    /**
     * Hiển thị danh sách theo danh mục/hạng mục
     */
    public function category($category = null)
    {
        $query = Exam::withCount('questions');

        if ($category) {
            // Giả sử bạn có trường 'category' trong bảng exams
            $query->where('category', $category);
        }

        $exams = $query->get();
        $categories = Exam::select('category')->distinct()->pluck('category');

        return view('pages.exams.category', compact('exams', 'categories', 'category'));
    }

    /**
     * Tạo đề thi mới
     */
    public function create()
    {
        return view('pages.exams.create');
    }

    /**
     * Lưu đề thi mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:exams',
            'title' => 'required',
            'duration_minutes' => 'required|integer|min:1',
            'total_score' => 'required|numeric|min:0',
            'passing_score' => 'required|numeric|min:0',
            'category' => 'nullable|string',
        ]);

        $exam = Exam::create($validated);

        return redirect()->route('exams.show', $exam->id)
            ->with('success', 'Đề thi đã được tạo thành công!');
    }

    /**
     * Hiển thị chi tiết đề thi
     */
    public function show($id)
    {
        $exam = Exam::with('questions.options')->findOrFail($id);
        return view('pages.exams.show', compact('exam'));
    }

    /**
     * Chỉnh sửa đề thi
     */
    public function edit($id)
    {
        $exam = Exam::findOrFail($id);
        return view('pages.exams.edit', compact('exam'));
    }

    /**
     * Cập nhật đề thi
     */
    public function update(Request $request, $id)
    {
        $exam = Exam::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|unique:exams,code,' . $id,
            'title' => 'required',
            'duration_minutes' => 'required|integer|min:1',
            'total_score' => 'required|numeric|min:0',
            'passing_score' => 'required|numeric|min:0',
            'category' => 'nullable|string',
        ]);

        $exam->update($validated);

        return redirect()->route('exams.show', $exam->id)
            ->with('success', 'Đề thi đã được cập nhật!');
    }

    /**
     * Xóa đề thi
     */
    public function destroy($id)
    {
        $exam = Exam::findOrFail($id);
        $exam->delete();

        return redirect()->route('exams.index')
            ->with('success', 'Đề thi đã được xóa!');
    }
}
