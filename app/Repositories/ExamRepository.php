<?php

namespace App\Repositories;

use App\Models\Exam;
use Illuminate\Support\Collection;

class ExamRepository
{
    /**
     * Get all exams with question count
     */
    public function getAllWithQuestionCount(): Collection
    {
        return Exam::withCount('questions')->get();
    }

    /**
     * Find exam by ID
     */
    public function findById(int $id): Exam
    {
        return Exam::findOrFail($id);
    }

    /**
     * Find exam with questions and options
     */
    public function findWithQuestionsAndOptions(int $id): Exam
    {
        return Exam::with('questions.options')->findOrFail($id);
    }

    /**
     * Find exams by category
     */
    public function findByCategory(string $category): Collection
    {
        return Exam::withCount('questions')
            ->where('category', $category)
            ->get();
    }

    /**
     * Create new exam
     */
    public function create(array $data): Exam
    {
        return Exam::create($data);
    }

    /**
     * Update exam
     */
    public function update(int $id, array $data): Exam
    {
        $exam = $this->findById($id);
        $exam->update($data);
        return $exam;
    }

    /**
     * Delete exam
     */
    public function delete(int $id): bool
    {
        $exam = $this->findById($id);
        return $exam->delete();
    }

    /**
     * Get distinct categories
     */
    public function getDistinctCategories(): Collection
    {
        return Exam::select('category')
            ->distinct()
            ->pluck('category');
    }
}
