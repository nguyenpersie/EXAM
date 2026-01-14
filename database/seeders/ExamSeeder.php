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
                // Tạo 20 câu hỏi cho đề thi này, gán exam_id thủ công
                for ($i = 0; $i < 20; $i++) {
                    $question = Question::factory()
                        ->create(['exam_id' => $exam->id]);

                    // Tạo 4 đáp án cho câu hỏi
                    $question->options()->createMany([
                        ['content' => 'A. ' . fake()->sentence(8), 'is_correct' => fake()->boolean(25)],
                        ['content' => 'B. ' . fake()->sentence(8), 'is_correct' => fake()->boolean(25)],
                        ['content' => 'C. ' . fake()->sentence(8), 'is_correct' => fake()->boolean(25)],
                        ['content' => 'D. ' . fake()->sentence(8), 'is_correct' => fake()->boolean(25)],
                    ]);
                }
            });

        $this->command->info('Đã tạo 10 đề thi giả, mỗi đề 20 câu hỏi + 80 đáp án!');
    }
}
