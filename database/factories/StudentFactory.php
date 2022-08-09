<?php

namespace Database\Factories;

use App\Models\Information;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;
use ReflectionClass;

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
        $student_card_id = $this->faker->randomElement([0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'A', 'B', 'C', 'D', 'E', 'H'])
            . $this->faker->numberBetween(15, date("y")) . $this->faker->randomElement([0, 'F', 'H'])
            . $this->faker->numberBetween(1000, 9999);
            $this->faker->numberBetween(100, 999) . strtoupper($this->faker->randomLetter) . $this->faker->numberBetween(1000, 9999);
        $temp =  Teacher::query()->get('information_id')->toArray();
        foreach ($temp as $each) {
            $arr[] = $each['information_id'];
        }

        return [
            'name' => $this->faker->name,
            'email' => $student_card_id . '@student.tdtu.edu.vn',
            'student_card_id' => $student_card_id,
            'password' => "1234",
            'role' => Student::SINH_VIEN,
            'information_id' => Information::query()->whereNotIn('id', $arr)->inRandomOrder()->value('id'),
        ];
    }
}

