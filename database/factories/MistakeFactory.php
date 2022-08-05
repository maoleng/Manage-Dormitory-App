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
        $random_type = $this->faker->numberBetween(1, 10);
        $random_bool = $this->faker->numberBetween(0, 1);
        return [
            'student_id' => Contract::query()->whereNotNull('subscription_id')->inRandomOrder()->value('student_id'),
            'teacher_id' => Teacher::query()->inRandomOrder()->value('id'),
            'type' => $random_type,
            'content' => $random_type === 10 ? $this->faker->sentence($nbWords = 6, $variableNbWords = true) : null,
            'is_fix_mistake' => $random_bool,
            'is_confirm' => $random_bool === 1 ? 1 : 0,
            'date' => $this->faker->dateTime($max = 'now', $timezone = null)
        ];
    }
}
