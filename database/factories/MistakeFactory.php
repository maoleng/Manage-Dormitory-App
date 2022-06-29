<?php

namespace Database\Factories;

use App\Models\Contract;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mistake>
 */
class MistakeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'student_id' => Contract::query()->whereNotNull('subscription_id')->inRandomOrder()->value('student_id'),
            'teacher_id' => Teacher::query()->inRandomOrder()->value('id'),
            'content' => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
            'date' => $this->faker->dateTime($max = 'now', $timezone = null)
        ];
    }
}
