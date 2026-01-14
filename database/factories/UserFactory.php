<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['LPT', 'TM', 'TT', 'T4', 'T3', 'T2', 'T1', 'M3', 'M2', 'M1', 'ĐKCT', 'ATVB', 'ATXD'];
        return [
           'student_code' => 'SBD' . fake()->unique()->numberBetween(1, 10),
            'full_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('123456'),
            'role' => 'student',
            'category' => fake()->randomElement($categories),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
   public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
            'category' => null,
        ]);
    }
}
