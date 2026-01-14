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
                for ($i = 0; $i < 30; $i++) {
                    $question = Question::factory()->create(); // Tạo câu hỏi trước

                    // Gán exam_id thủ công (đây là fix chính)
                    $question->exam_id = $exam->id;
                    $question->save();

                    // Tạo 4 đáp án cho câu hỏi
                    $letters = ['A', 'B', 'C', 'D'];
                    $correctIndex = fake()->numberBetween(0, 3);

                    for ($j = 0; $j < 4; $j++) {
                        $question->options()->create([
                            'content' => $letters[$j] . '. ' . fake()->sentence(8),
                            'is_correct' => $j === $correctIndex,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            });

        $this->command->info('Đã tạo 10 đề thi giả, mỗi đề 30 câu hỏi + 120 đáp án!');
    }
}
