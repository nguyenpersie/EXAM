<?php

namespace App\Http\Repository;

use App\Models\Exam;
use App\Models\Question;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class QuestionRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = Question::class;
    }

    /**
     * Lấy danh sách câu hỏi (phân trang cho admin)
     */
    public function getPaginatedQuestions(array $select = ['*'], array $relationships = []): LengthAwarePaginator
    {
        return $this->model::select($select)
            ->with($relationships)
            ->paginate(20);
    }

    /**
     * Lấy câu hỏi theo đề thi (phân trang)
     */
    public function getByExamId(int $examId, array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = $this->model::where('exam_id', $examId)
            ->with('options');

        // Apply search filter
        if (!empty($filters['search'])) {
            $query->where('content', 'like', '%' . $filters['search'] . '%');
        }

        // Apply category filter
        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        // Apply level filter
        if (!empty($filters['level'])) {
            $query->where('level', $filters['level']);
        }

        // Apply section filter
        if (!empty($filters['section'])) {
            $query->where('section', $filters['section']);
        }

        return $query->paginate($perPage);
    }

    /**
     * Lấy danh sách categories của đề thi
     */
    public function getCategoriesByExam(int $examId): SupportCollection
    {
        return $this->model::where('exam_id', $examId)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');
    }

    /**
     * Lấy danh sách sections của đề thi
     */
    public function getSectionsByExam(int $examId): SupportCollection
    {
        return $this->model::where('exam_id', $examId)
            ->whereNotNull('section')
            ->distinct()
            ->pluck('section');
    }

    /**
     * Lấy câu hỏi ngẫu nhiên theo phần (dùng khi tạo đề thi)
     */
    public function getRandomQuestionsBySection(string $section, int $count): Collection
    {
        return $this->model::where('section', $section)
            ->inRandomOrder()
            ->take($count)
            ->get();
    }

    /**
     * Tìm câu hỏi theo ID
     */
    public function find(int $id): ?Question
    {
        return $this->model::find($id);
    }

    /**
     * Tìm câu hỏi kèm options
     */
    public function findWithOptions(int $id): ?Question
    {
        return $this->model::with('options')->find($id);
    }

    /**
     * Tạo câu hỏi
     */
    public function create(array $data): Question
    {
        return $this->model::create($data);
    }

    /**
     * Tạo câu hỏi kèm đáp án
     */
    public function createWithOptions(array $questionData, array $options): Question
    {
        $question = $this->model::create($questionData);

        foreach ($options as $option) {
            $question->options()->create($option);
        }

        return $question;
    }

    /**
     * Cập nhật câu hỏi
     */
    public function update(int $id, array $data): bool
    {
        $question = $this->model::findOrFail($id);
        return $question->update($data);
    }

    /**
     * Cập nhật câu hỏi kèm đáp án
     */
    public function updateWithOptions(int $id, array $questionData, array $options, int $correctIndex): Question
    {
        $question = $this->model::findOrFail($id);
        $question->update($questionData);

        // Xóa đáp án cũ và tạo mới
        $question->options()->delete();

        foreach ($options as $index => $option) {
            $question->options()->create([
                'content' => $option['content'],
                'is_correct' => $index == $correctIndex,
            ]);
        }

        return $question;
    }

    /**
     * Xóa câu hỏi
     */
    public function delete(int $id): bool
    {
        $question = $this->model::findOrFail($id);
        return $question->delete();
    }

    /**
     * Xóa tất cả câu hỏi của đề thi
     */
    public function deleteByExamId(int $examId): int
    {
        $exam = Exam::findOrFail($examId);
        $count = $exam->questions()->count();
        $exam->questions()->delete();
        return $count;
    }
}
