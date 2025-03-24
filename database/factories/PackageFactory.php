<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Package>
 */
class PackageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'slug' => fake()->unique()->slug(),
            'description' => fake()->text(),
            'price' => fake()->randomNumber(5),
            'duration' => fake()->word(),
            'min_person' => fake()->randomDigitNotNull(),
            'max_person' => fake()->randomDigitNotNull(),
        ];
    }
}
