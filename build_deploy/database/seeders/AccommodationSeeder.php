<?php

namespace Database\Seeders;

use App\Models\Accommodation;
use Illuminate\Database\Seeder;

class AccommodationSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'name' => 'Hotel Malioboro Heritage',
                'slug' => 'hotel-malioboro-heritage',
                'type' => 'hotel',
                'room_type' => 'Deluxe Room',
                'location' => 'Yogyakarta',
                'highlight' => 'Dekat Malioboro dan pusat kuliner',
                'amenities' => 'Breakfast, AC, WiFi, Parking',
                'description' => 'Hotel nyaman dekat pusat kota dan area wisata populer.',
                'image' => 'product-1.jpg',
                'price_per_night' => 42.00,
                'capacity' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Hotel Bromo View',
                'slug' => 'hotel-bromo-view',
                'type' => 'hotel',
                'room_type' => 'Superior Room',
                'location' => 'Jawa Timur',
                'highlight' => 'Cocok untuk sunrise trip Bromo',
                'amenities' => 'Hot Shower, WiFi, Jeep Pickup Point, Restaurant',
                'description' => 'Pilihan hotel strategis untuk trip sunrise dan jeep Bromo.',
                'image' => 'product-3.jpg',
                'price_per_night' => 55.00,
                'capacity' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Villa Uluwatu Breeze',
                'slug' => 'villa-uluwatu-breeze',
                'type' => 'villa',
                'room_type' => 'Private 2 Bedroom Villa',
                'location' => 'Bali',
                'highlight' => 'Private stay dengan view tebing',
                'amenities' => 'Private Pool, WiFi, Kitchen, Living Room',
                'description' => 'Villa private dengan view tebing dan suasana santai.',
                'image' => 'product-6.jpg',
                'price_per_night' => 88.00,
                'capacity' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Villa Komodo Bay',
                'slug' => 'villa-komodo-bay',
                'type' => 'villa',
                'room_type' => 'Family Villa',
                'location' => 'Labuan Bajo',
                'highlight' => 'Ideal untuk keluarga dan small group',
                'amenities' => 'Sea View, Breakfast, WiFi, Airport Transfer',
                'description' => 'Villa premium untuk trip keluarga atau small group.',
                'image' => 'product-5.jpg',
                'price_per_night' => 96.00,
                'capacity' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Homestay Borobudur Asri',
                'slug' => 'homestay-borobudur-asri',
                'type' => 'homestay',
                'room_type' => 'Standard Twin Room',
                'location' => 'Magelang',
                'highlight' => 'Nuansa lokal dekat area candi',
                'amenities' => 'Fan, Breakfast, WiFi, Local Guide Access',
                'description' => 'Homestay hangat dengan nuansa lokal dekat area candi.',
                'image' => 'product-4.jpg',
                'price_per_night' => 24.00,
                'capacity' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Homestay Kawah Putih Lestari',
                'slug' => 'homestay-kawah-putih-lestari',
                'type' => 'homestay',
                'room_type' => 'Cozy Family Room',
                'location' => 'Bandung',
                'highlight' => 'Stay santai untuk healing trip sejuk',
                'amenities' => 'Hot Water, WiFi, Garden, Shared Lounge',
                'description' => 'Homestay sederhana dan nyaman untuk healing trip sejuk.',
                'image' => 'product-2.jpg',
                'price_per_night' => 21.50,
                'capacity' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($items as $item) {
            Accommodation::query()->updateOrCreate(
                ['slug' => $item['slug']],
                $item,
            );
        }
    }
}
