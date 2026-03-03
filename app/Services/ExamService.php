<?php

namespace App\Services;

use App\Models\Exam;
use App\Repositories\ExamRepository;
use Illuminate\Support\Collection;

class ExamService
{
    protected ExamRepository $examRepository;

    public function __construct(ExamRepository $examRepository)
    {
        $this->examRepository = $examRepository;
    }

    /**
     * Get exams filtered by user role and category
     */
    public function getExamsForUser($user): Collection
    {
        if ($user && !$user->isAdmin()) {
            if ($user->category) {
                return $this->examRepository->findByCategory($user->category);
            }
        }

        return $this->examRepository->getAllWithQuestionCount();
    }

    /**
     * Get exam by ID with questions and options
     */
    public function getExamWithQuestions(int $id): Exam
    {
        return $this->examRepository->findWithQuestionsAndOptions($id);
    }

    /**
     * Get sections for an exam
     */
    public function getSections(int $examId): array
    {
        return \Illuminate\Support\Facades\Cache::remember("exam_{$examId}_sections", 86400, function () use ($examId) {
            $exam = $this->examRepository->findById($examId);
            return $exam->getSections()->toArray();
        });
    }

    /**
     * Get randomized exam based on mode
     */
    public function getRandomizedExam(int $examId, string $mode, ?string $section = null, array $categories = [], int $limit = 30): Exam
    {
        $exam = $this->examRepository->findById($examId);

        // Get questions based on mode
        if ($mode === 'practice' && $section) {
            $questions = $exam->getQuestionsBySection($section);
        } else {
            $questions = $exam->getRandomQuestionsByCategory($limit, $categories);
        }

        // Shuffle options only in test mode
        if ($mode === 'test') {
            $questions = $this->shuffleQuestionOptions($questions);
        }

        $exam->questions = $questions;
        return $exam;
    }

    /**
     * Create new exam
     */
    public function createExam(array $data): Exam
    {
        return $this->examRepository->create($data);
    }

    /**
     * Update exam
     */
    public function updateExam(int $id, array $data): Exam
    {
        return $this->examRepository->update($id, $data);
    }

    /**
     * Delete exam
     */
    public function deleteExam(int $id): bool
    {
        return $this->examRepository->delete($id);
    }

    /**
     * Get exams by category
     */
    public function getExamsByCategory(?string $category = null): Collection
    {
        if ($category) {
            return $this->examRepository->findByCategory($category);
        }
        return $this->examRepository->getAllWithQuestionCount();
    }

    /**
     * Get all distinct categories
     */
    public function getAllCategories(): Collection
    {
        return $this->examRepository->getDistinctCategories();
    }

    /**
     * Shuffle options for each question
     */
    protected function shuffleQuestionOptions(Collection $questions): Collection
    {
        return $questions->map(function ($question) {
            $optionsArray = $question->options->toArray();
            shuffle($optionsArray);
            $question->options = collect($optionsArray)->values();
            return $question;
        });
    }

    /**
     * Check if user can manage exam
     */
    public function canUserManageExam($user): bool
    {
        return in_array($user->role, ['admin', 'center']);
    }
}
