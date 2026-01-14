<?php

namespace Database\Seeders;

use App\Models\UserExam;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo 1 admin
        UserExam::factory()->admin()->create([
            'student_code' => 'ADMIN001',
            'full_name' => 'Admin System',
            'email' => 'admin@example.com',
        ]);

        // Tạo 100 học viên giả
        UserExam::factory()->count(10)->create();

        $this->command->info('Đã tạo 1 admin và 100 học viên giả!');
    }
}
