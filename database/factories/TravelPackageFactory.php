<?php

namespace Database\Factories;

use App\Models\TravelPackage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TravelPackageFactory extends Factory
{
    protected $model = TravelPackage::class;

    public function definition(): array
    {
        $title = fake()->unique()->words(2, true);
        $basePrice = fake()->numberBetween(100, 500);

        return [
            'slug' => Str::slug($title . '-' . fake()->unique()->numberBetween(1, 999)),
            'title' => Str::title($title),
            'category' => fake()->randomElement(['Adventure', 'Beach', 'Culture', 'Family']),
            'summary' => fake()->sentence(),
            'description' => fake()->paragraphs(3, true),
            'featured_image' => 'product-' . fake()->numberBetween(1, 12) . '.jpg',
            'base_price' => $basePrice,
            'sale_price' => fake()->boolean(35) ? fake()->numberBetween(80, $basePrice) : null,
            'duration_days' => fake()->numberBetween(2, 6),
            'quota' => fake()->numberBetween(5, 25),
            'location' => fake()->city(),
            'is_featured' => fake()->boolean(30),
            'is_active' => true,
        ];
    }
}
