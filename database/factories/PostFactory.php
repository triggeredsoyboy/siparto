<?php

namespace Database\Factories;

use App\Enums\PostStatus;
use App\Models\Category;
use App\Models\User;
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
    public function definition(): array
    {
        return [
            'title' => fake()->word(),
            'slug' => fake()->unique()->slug(2),
            'body' => fake()->text(),
            'status' => fake()->randomElement(PostStatus::class),
            'published_at' => fake()->dateTime('j M Y, H:i'),
            'author_id' => User::factory(),
            'category_id' => Category::factory(),
        ];
    }
}
