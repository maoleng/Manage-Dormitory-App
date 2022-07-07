<?php

namespace Database\Factories;

use App\Models\Form;
use App\Models\Mistake;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $mistake_or_form = random_int(0,1);
        if ($mistake_or_form === 1) {
            return [
                'source' => $this->faker->imageUrl(),
                'size' => $this->faker->numberBetween(10000, 100000),
                'mistake_id' => Mistake::query()->inRandomOrder()->value('id'),
            ];
        }

        return [
            'source' => $this->faker->imageUrl(),
            'size' => $this->faker->numberBetween(10000, 100000),
            'form_id' => Form::query()->inRandomOrder()->value('id'),
        ];
    }
}
