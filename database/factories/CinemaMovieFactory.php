<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CinemaMovie>
 */
class CinemaMovieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cinema_id' => $this->faker->numberBetween(1, 8),
            'movie_id' => $this->faker->numberBetween(1, 30)
        ];
    }
}
