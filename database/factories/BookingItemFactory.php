<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\TravelPackage;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingItemFactory extends Factory
{
    protected $model = BookingItem::class;

    public function definition(): array
    {
        $unitPrice = fake()->numberBetween(80, 300);
        $quantity = fake()->numberBetween(1, 4);

        return [
            'booking_id' => Booking::factory(),
            'travel_package_id' => TravelPackage::factory(),
            'package_title' => fake()->words(2, true),
            'unit_price' => $unitPrice,
            'quantity' => $quantity,
            'line_total' => $unitPrice * $quantity,
        ];
    }
}
