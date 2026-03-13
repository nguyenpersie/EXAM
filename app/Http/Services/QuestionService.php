<?php

namespace App\Http\Services;

use App\Http\Repository\QuestionRepository;
use App\Models\Exam;
use App\Models\Option;
use App\Models\Question;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class QuestionService
{
    protected QuestionRepository $questionRepository;
    protected WordImportService $wordImportService;

    public function __construct(
        QuestionRepository $questionRepository,
        WordImportService $wordImportService
    ) {
        $this->questionRepository = $questionRepository;
        $this->wordImportService = $wordImportService;
    }

    /**
     * Lấy dữ liệu cho trang danh sách câu hỏi theo đề thi
     */
    public function getQuestionsByExam(int $examId, array $filters = []): array
    {
        $exam = Exam::findOrFail($examId);

        return [
            'exam' => $exam,
            'questions' => $this->questionRepository->getByExamId($examId, $filters),
            'categories' => $this->questionRepository->getCategoriesByExam($examId),
            'sections' => $this->questionRepository->getSectionsByExam($examId),
        ];
    }

    /**
     * Lấy câu hỏi để chỉnh sửa
     */
    public function getQuestionForEdit(int $id): ?Question
    {
        return $this->questionRepository->findWithOptions($id);
    }

    /**
     * Lấy danh sách câu hỏi phân trang
     */
    public function getPaginatedQuestions(): LengthAwarePaginator
    {
        return $this->questionRepository->getPaginatedQuestions();
    }

    /**
     * Lấy câu hỏi ngẫu nhiên theo phần
     */
    public function getRandomQuestionsBySection(string $section, int $count): Collection
    {
        return $this->questionRepository->getRandomQuestionsBySection($section, $count);
    }

    /**
     * Tạo câu hỏi mới
     */
    public function createQuestion(array $data): Question
    {
        return $this->questionRepository->create($data);
    }

    /**
     * Tạo câu hỏi kèm đáp án
     */
    public function createQuestionWithOptions(array $data, array $options, int $correctIndex): Question
    {
        $formattedOptions = [];
        foreach ($options as $index => $option) {
            $formattedOptions[] = [
                'content' => $option['content'],
                'is_correct' => $index == $correctIndex,
            ];
        }

        $question = $this->questionRepository->createWithOptions($data, $formattedOptions);

        // Xóa cache pool ID câu hỏi của đề thi này
        Cache::forget("exam_{$data['exam_id']}_question_ids");
        Cache::forget('all_exams_with_count');

        return $question;
    }

    /**
     * Cập nhật câu hỏi kèm đáp án
     */
    public function updateQuestionWithOptions(int $id, array $questionData, array $options, int $correctIndex): Question
    {
        return $this->questionRepository->updateWithOptions($id, $questionData, $options, $correctIndex);
    }

    /**
     * Cập nhật câu hỏi
     */
    public function updateQuestion(int $id, array $data): bool
    {
        return $this->questionRepository->update($id, $data);
    }

    /**
     * Xóa câu hỏi
     */
    public function deleteQuestion(int $id): bool
    {
        $question = $this->questionRepository->findWithOptions($id);
        $examId = $question?->exam_id;

        $result = $this->questionRepository->delete($id);

        // Xóa cache pool ID câu hỏi
        if ($examId) {
            Cache::forget("exam_{$examId}_question_ids");
            Cache::forget('all_exams_with_count');
        }

        return $result;
    }

    /**
     * Xóa tất cả câu hỏi của đề thi
     */
    public function deleteQuestionsByExam(int $examId): int
    {
        $count = $this->questionRepository->deleteByExamId($examId);

        // Xóa cache pool ID câu hỏi
        Cache::forget("exam_{$examId}_question_ids");
        Cache::forget('all_exams_with_count');

        return $count;
    }

    /**
     * Import câu hỏi từ file Word
     */
    public function importFromWord($file, int $examId, ?string $category = null): int
    {
        $exam = Exam::findOrFail($examId);

        DB::beginTransaction();
        try {
            $data = $this->wordImportService->parseFile($file);
            $importedCount = 0;

            foreach ($data as $row) {
                if (empty($row['question'])) {
                    continue;
                }

                $question = Question::create([
                    'exam_id' => $exam->id,
                    'content' => $row['question'],
                    'section' => $row['section'] ?? null,
                    'level' => $row['level'] ?? '2',
                    'category' => $category ?? $row['category'] ?? null,
                ]);

                // Tạo 4 đáp án
                foreach (['A', 'B', 'C', 'D'] as $letter) {
                    $optionKey = 'option_' . strtolower($letter);
                    if (!empty($row[$optionKey])) {
                        Option::create([
                            'question_id' => $question->id,
                            'content' => $row[$optionKey],
                            'is_correct' => (strtoupper($row['correct_answer'] ?? '') === $letter),
                        ]);
                    }
                }

                $importedCount++;
            }

            DB::commit();

            // Xóa cache pool ID câu hỏi sau khi import
            Cache::forget("exam_{$examId}_question_ids");
            Cache::forget('all_exams_with_count');

            return $importedCount;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
