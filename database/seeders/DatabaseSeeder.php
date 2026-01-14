<?php

namespace Database\Seeders;

use App\Models\Question;
use Database\Factories\UserExamFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(UserExamSeeder::class);
        $this->call(ExamSeeder::class);
        $this->call(QuestionsSeeder::class);
    }
}
