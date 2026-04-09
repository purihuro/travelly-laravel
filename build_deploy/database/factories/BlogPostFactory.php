<?php

namespace Database\Factories;

use App\Models\BlogPost;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BlogPostFactory extends Factory
{
    protected $model = BlogPost::class;

    public function definition(): array
    {
        $title = fake()->sentence(6);

        return [
            'slug' => Str::slug($title . '-' . fake()->unique()->numberBetween(1, 999)),
            'title' => $title,
            'excerpt' => fake()->paragraph(),
            'content' => fake()->paragraphs(6, true),
            'featured_image' => 'image_' . fake()->numberBetween(1, 6) . '.jpg',
            'author_name' => 'Travelly Team',
            'published_at' => now(),
            'is_published' => true,
            'meta_title' => Str::limit($title, 180, ''),
            'meta_description' => Str::limit(fake()->sentence(20), 255, ''),
        ];
    }
}
