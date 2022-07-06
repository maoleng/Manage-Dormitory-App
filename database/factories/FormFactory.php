<?php

namespace Database\Factories;

use App\Models\Contract;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Form>
 */
class FormFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
            'student_id' => Contract::query()
                ->where('is_accept', true)
                ->inRandomOrder()->value('student_id'),
            'content' => $this->faker->text($maxNbChars = 200)
        ];
    }
}
