<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\Option;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    protected $model = Question::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(array $attributes = []): array
    {
       return array_merge([
            'exam_id' => null, // Sẽ được gán khi tạo exam (hoặc null nếu dùng ngân hàng chung)
            'content' => fake()->paragraph(3) . '?', // Nội dung câu hỏi giả
            'section' => fake()->randomElement(['I', 'II', 'III']),
            'level' => fake()->numberBetween(1, 5),
            'created_at' => now(),
            'updated_at' => now(),
        ], $attributes);
    }

    /**
     * Tự động tạo 4 đáp án, trong đó 1 cái đúng
     */
   public function withOptions(): static
    {
        return $this->afterCreating(function (Question $question) {
            $letters = ['A', 'B', 'C', 'D'];
            $correctIndex = fake()->numberBetween(0, 3); // Chọn ngẫu nhiên đáp án đúng

            for ($i = 0; $i < 4; $i++) {
                $question->options()->create([
                    'content' => $letters[$i] . '. ' . fake()->sentence(8),
                    'is_correct' => $i === $correctIndex,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }
}
