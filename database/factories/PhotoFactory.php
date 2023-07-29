<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Photo>
 */
class PhotoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'imageable_id' =>$this->faker->numberBetween(1, 10),
            'imageable_type' => 'App\Models\User',
            'filename' => $this->faker->word() . ".jpg"
        ];
    }
}
