<?php

namespace Database\Seeders;

use App\Models\CulinaryVenue;
use Illuminate\Database\Seeder;

class CulinaryVenueSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'name' => 'Sate Lembang Panorama',
                'slug' => 'sate-klathak-pak-bari',
                'location' => 'Lembang, Bandung Barat',
                'phone' => '+62-274-545678',
                'website' => 'https://example.com',
                'cuisine_type' => 'Indonesian - Satay',
                'opening_time' => '10:00',
                'closing_time' => '22:00',
                'description' => 'Tempat makan sate dengan suasana sejuk khas wisata pegunungan Bandung Barat.',
                'image' => 'product-8.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Seafood Dusun Bambu View',
                'slug' => 'seafood-jimbaran-rasa-laut',
                'location' => 'Cisarua, Bandung Barat',
                'phone' => '+62-361-234567',
                'website' => 'https://example.com',
                'cuisine_type' => 'Indonesian - Seafood',
                'opening_time' => '11:00',
                'closing_time' => '23:00',
                'description' => 'Paket seafood dengan pemandangan wisata alam Bandung Barat yang tenang dan fotogenik.',
                'image' => 'product-9.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Kopi Parongpong Harmoni',
                'slug' => 'gudeg-malioboro-harmoni',
                'location' => 'Parongpong, Bandung Barat',
                'phone' => '+62-22-987654',
                'website' => 'https://example.com',
                'cuisine_type' => 'Indonesian - Javanese',
                'opening_time' => '09:00',
                'closing_time' => '21:00',
                'description' => 'Spot kuliner santai dengan nuansa wisata alam dan udara sejuk khas Bandung Barat.',
                'image' => 'product-10.jpg',
                'is_active' => true,
            ],
        ];

        foreach ($items as $item) {
            CulinaryVenue::query()->updateOrCreate(
                ['slug' => $item['slug']],
                $item,
            );
        }
    }
}
