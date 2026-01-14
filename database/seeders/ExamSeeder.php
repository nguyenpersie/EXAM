<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo 10 đề thi giả (mỗi đề có 20 câu hỏi, mỗi câu 4 đáp án)
        Exam::factory()
            ->count(10)
            ->create()
            ->each(function ($exam) {
                Question::factory()
                    ->count(20)
                    ->withOptions() // Tự động tạo 4 đáp án
                    ->create(['exam_id' => $exam->id]);
            });

        $this->command->info('Đã tạo 10 đề thi giả, mỗi đề 20 câu hỏi + 80 đáp án!');
    }
}
