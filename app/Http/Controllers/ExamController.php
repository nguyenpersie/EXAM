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
     * API: Lấy danh sách sections
     */
    public function getSections($id)
    {
        $exam = Exam::findOrFail($id);
        $sections = $exam->getSections();

        return response()->json($sections);
    }

    /**
     * API: Lấy đề thi với câu hỏi (hỗ trợ cả test và practice mode)
     */
    public function getRandomizedExam($id, Request $request)
    {
        $exam = Exam::findOrFail($id);

        $mode = $request->input('mode', 'test'); // 'test' hoặc 'practice'
        $section = $request->input('section'); // Section cụ thể (cho practice mode)

        // Lấy câu hỏi dựa theo mode
        if ($mode === 'practice' && $section) {
            // Practice mode: Lấy tất cả câu theo section, không random
            $questions = $exam->getQuestionsBySection($section);
        } else {
            // Test mode: Lấy 30 câu random
            $categories = $request->input('categories', []);
            $limit = $request->input('limit', 30);
            $questions = $exam->getRandomQuestionsByCategory($limit, $categories);
        }

        // Chỉ trộn đáp án khi là test mode
        if ($mode === 'test') {
            $questions = $questions->map(function ($question) {
                // Convert to array, shuffle, then back to collection
                $optionsArray = $question->options->toArray();
                shuffle($optionsArray);
                $question->options = collect($optionsArray)->values();
                return $question;
            });
        }

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
        // Only center can create exams
        if (auth()->user()->role !== 'center') {
            abort(403, 'Unauthorized action.');
        }

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
        // Only center can update exams
        if (auth()->user()->role !== 'center') {
            abort(403, 'Unauthorized action.');
        }

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
        //Only center can delete exams
        if (auth()->user()->role !== 'center') {
            abort(403, 'Unauthorized action.');
        }

        $exam = Exam::findOrFail($id);
        $exam->delete(); // Cascade delete handled by foreign keys

        return redirect()->route('exams.home')
            ->with('success', 'Đề thi và tất cả câu hỏi đã được xóa!');
    }
}
