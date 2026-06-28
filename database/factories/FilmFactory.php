<?php

namespace Database\Factories;

use App\Models\Film;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\FilmStatus;

/**
 * @extends Factory<Film>
 */
class FilmFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(3),
            'poster_image' => fake()->imageUrl(),
            'preview_image' => fake()->imageUrl(),
            'background_image' => fake()->imageUrl(),
            'background_color' => fake()->hexColor(),
            'video_link' => fake()->url(),
            'preview_video_link' => fake()->url(),
            'description' => fake()->paragraph(),
            'run_time' => fake()->numberBetween(45, 300),
            'released' => fake()->year(),
            'imdb_id' => fake()->unique()->regexify('tt[0-9]{7}'),
            'status' => fake()->randomElement(FilmStatus::cases())->value,
            'is_promo' => false,
        ];
    }
}
