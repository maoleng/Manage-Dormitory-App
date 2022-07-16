<?php

namespace Database\Factories;

use App\Models\Image;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
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
            'content' => $this->faker->randomHtml(),
            'banner_id' => Image::query()->inRandomOrder()->value('id'),
            'category' => $this->faker->numberBetween(1, 6),
            'teacher_id' => Teacher::query()->inRandomOrder()->value('id'),
        ];
    }
}
