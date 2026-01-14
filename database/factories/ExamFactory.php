<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Exam>
 */
class ExamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $code = fake()->unique()->randomElement(['LPT', 'TM', 'TT', 'T4', 'T3', 'T2', 'T1', 'M3', 'M2', 'M1', 'ĐKCT', 'ATVB', 'ATXD']) . '-' . fake()->numberBetween(1, 99);
        return [
            'code' => $code,
            'title' => "Đề thi thử hạng $code " . fake()->year(),
            'duration_minutes' => fake()->numberBetween(30, 90),
            'total_score' => 100,
            'passing_score' => 80,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
