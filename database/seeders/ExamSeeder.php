<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // public function run(): void
    // {
    //     // Tạo 10 đề thi giả (mỗi đề có 20 câu hỏi, mỗi câu 4 đáp án)
    //     Exam::factory()
    //         ->count(10)
    //         ->create()
    //         ->each(function ($exam) {
    //             Question::factory()
    //                 ->count(30)
    //                 ->withOptions() // Tự động tạo 4 đáp án
    //                 ->create(['exam_id' => $exam->id]);
    //         });

    //     $this->command->info('Đã tạo 10 đề thi giả, mỗi đề 20 câu hỏi + 80 đáp án!');
    // }

    public function run(): void
    {
        // Tạo 10 đề thi giả
        Exam::factory()
            ->count(10)
            ->create()
            ->each(function ($exam) {
                // Tạo 30 câu hỏi cho đề thi này
                Question::factory()
                    ->count(30)
                    ->create() // Tạo câu hỏi trước (exam_id null tạm thời)
                    ->each(function ($question) use ($exam) {
                        // Gán exam_id thủ công sau khi tạo
                        $question->update(['exam_id' => $exam->id]);

                        // Tạo 4 đáp án cho câu hỏi
                        $letters = ['A', 'B', 'C', 'D'];
                        $correctIndex = fake()->numberBetween(0, 3);

                        for ($i = 0; $i < 4; $i++) {
                            $question->options()->create([
                                'content' => $letters[$i] . '. ' . fake()->sentence(8),
                                'is_correct' => $i === $correctIndex,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    });
            });

        $this->command->info('Đã tạo 10 đề thi giả, mỗi đề 30 câu hỏi + 120 đáp án!');
    }
}
