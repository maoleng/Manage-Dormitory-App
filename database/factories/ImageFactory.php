<?php

namespace Database\Factories;

use App\Models\Form;
use App\Models\Mistake;
use Bluemmb\Faker\PicsumPhotosProvider;
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
        $faker = $this->faker;
        $faker->addProvider(new PicsumPhotosProvider($faker));
        if ($mistake_or_form === 1) {
            return [
                'source' => $faker->imageUrl(640, 480, $this->faker->numberBetween(1, 1050)),
                'size' => $this->faker->numberBetween(10000, 100000),
                'mistake_id' => Mistake::query()->inRandomOrder()->value('id'),
            ];
        }

        return [
            'source' => $faker->imageUrl(640, 480, $this->faker->numberBetween(1, 1050)),
            'size' => $this->faker->numberBetween(10000, 100000),
            'form_id' => Form::query()->inRandomOrder()->value('id'),
        ];
    }
}
