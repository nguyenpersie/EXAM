<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(ExamSeeder::class);
        $this->call(QuestionsSeeder::class);
    }
}
