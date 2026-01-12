<?php

namespace App\Http\Repository;

use App\Models\Question;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class QuestionRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = Question::class;
    }

    // Lấy danh sách câu hỏi (phân trang cho admin)
    public function getPaginatedQuestions(array $select = ['*'], array $relationships = []): LengthAwarePaginator
    {
        return $this->model::select($select)
            ->with($relationships)
            ->paginate(20);
    }

    // Lấy câu hỏi ngẫu nhiên theo phần (dùng khi tạo đề thi)
    public function getRandomQuestionsBySection(string $section, int $count): Collection
    {
        return $this->model::where('section', $section)
            ->inRandomOrder()
            ->take($count)
            ->get();
    }

    // Tìm câu hỏi theo ID
    public function find(int $id): ?Question
    {
        return $this->model::find($id);
    }

    // Tạo câu hỏi
    public function create(array $data): Question
    {
        return $this->model::create($data);
    }

    // Cập nhật câu hỏi
    public function update(int $id, array $data): bool
    {
        $question = $this->model::findOrFail($id);
        return $question->update($data);
    }

    // Xóa câu hỏi
    public function delete(int $id): bool
    {
        $question = $this->model::findOrFail($id);
        return $question->delete();
    }
}
