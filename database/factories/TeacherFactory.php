<?php

namespace Database\Factories;

use App\Models\Information;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $teacher_id = $this->faker->numberBetween(100, 999) . strtoupper($this->faker->randomLetter) . $this->faker->numberBetween(1000, 9999);
        return [
            'name' => $this->faker->name,
            'email' => $teacher_id . '@teacher.tdtu.edu.vn',
            'password' => $this->faker->password,
            'role' => $this->faker->randomElement(['Thầy tự quản', 'Quản lý kí túc xá']),
            'information_id' => Information::query()->inRandomOrder()->value('id'),
        ];
    }
}
