<?php

namespace App\Http\Services;

use App\Http\Repository\QuestionRepository;
use App\Models\Question;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class QuestionService
{
    protected QuestionRepository $questionRepository;

    public function __construct(QuestionRepository $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }

    public function getPaginatedQuestions(): LengthAwarePaginator
    {
        return $this->questionRepository->getPaginatedQuestions();
    }

    public function getRandomQuestionsBySection(string $section, int $count): Collection
    {
        return $this->questionRepository->getRandomQuestionsBySection($section, $count);
    }

    public function createQuestion(array $data): Question
    {
        return $this->questionRepository->create($data);
    }

    public function updateQuestion(int $id, array $data): bool
    {
        return $this->questionRepository->update($id, $data);
    }

    public function deleteQuestion(int $id): bool
    {
        return $this->questionRepository->delete($id);
    }
}
