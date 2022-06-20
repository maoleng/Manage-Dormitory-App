<?php

namespace Database\Factories;

use App\Models\Information;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $student_card_id = $this->faker->numberBetween(100, 999) . strtoupper($this->faker->randomLetter) . $this->faker->numberBetween(1000, 9999);
        $temp =  Teacher::query()->get('information_id')->toArray();
        foreach ($temp as $each) {
            $arr[] = $each['information_id'];
        }

        return [
            'name' => $this->faker->name,
            'email' => $student_card_id . '@student.tdtu.edu.vn',
            'student_card_id' => $student_card_id,
            'password' => $this->faker->password,
            'role' => $this->faker->randomElement(['Sinh viên tự quản', 'Sinh viên']),
            'information_id' => Information::query()->whereNotIn('id', $arr)->inRandomOrder()->value('id'),
        ];
    }
}

