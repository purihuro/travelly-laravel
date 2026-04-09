<?php

namespace Database\Factories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        $subtotal = fake()->numberBetween(100, 600);
        $serviceFee = fake()->numberBetween(0, 30);
        $discount = fake()->numberBetween(0, 20);

        return [
            'booking_code' => 'TRV-' . Str::upper(Str::random(8)),
            'customer_first_name' => fake()->firstName(),
            'customer_last_name' => fake()->lastName(),
            'customer_email' => fake()->safeEmail(),
            'customer_phone' => fake()->phoneNumber(),
            'country' => fake()->country(),
            'city' => fake()->city(),
            'address_line_1' => fake()->streetAddress(),
            'address_line_2' => fake()->secondaryAddress(),
            'postal_code' => fake()->postcode(),
            'departure_date' => fake()->dateTimeBetween('+1 week', '+3 months')->format('Y-m-d'),
            'participants' => fake()->numberBetween(1, 6),
            'payment_method' => fake()->randomElement(['Transfer Bank', 'Virtual Account', 'Kartu Kredit', 'E-Wallet']),
            'notes' => fake()->optional()->sentence(),
            'subtotal' => $subtotal,
            'service_fee' => $serviceFee,
            'discount_amount' => $discount,
            'total_amount' => $subtotal + $serviceFee - $discount,
            'booking_status' => fake()->randomElement(['pending', 'confirmed', 'completed']),
            'payment_status' => fake()->randomElement(['unpaid', 'paid']),
        ];
    }
}
