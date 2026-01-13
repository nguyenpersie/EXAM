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
    public function definition(): array
    {
        return [
            'content' => fake()->paragraph(3) . '?', // Câu hỏi giả dạng đoạn văn kết thúc bằng dấu ?
            'section' => fake()->randomElement(['1', '2', '3']), // Phần I, II, III
            'level' => fake()->numberBetween(1, 5), // Độ khó từ 1 đến 5
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Tự động tạo 4 đáp án, trong đó 1 cái đúng
     */
    public function withOptions(): static
    {
        return $this->afterCreating(function (Question $question) {
            $correctIndex = fake()->numberBetween(0, 3); // Chọn ngẫu nhiên đáp án đúng

            $options = [];
            for ($i = 0; $i < 4; $i++) {
                $options[] = [
                    'question_id' => $question->id,
                    'content' => fake()->sentence(6), // Đáp án giả
                    'is_correct' => $i === $correctIndex,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $question->options()->createMany($options);
        });
    }
}
