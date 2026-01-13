<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionsSeeder extends Seeder
{
    /**
     * Seed the application's database with fake questions.
     */
    public function run(): void
    {
        // Tạo 1000 câu hỏi, mỗi câu tự động có 4 đáp án (1 đúng)
        Question::factory()
            ->count(100)
            ->withOptions() // Tự động tạo đáp án
            ->create();

            $this->command->info('Đã tạo 100 câu hỏi giả với đáp án!');
    }
}
