<?php

namespace Database\Seeders;

use App\Models\CulinaryPackage;
use App\Models\CulinaryVenue;
use Illuminate\Database\Seeder;

class CulinaryPackageSeeder extends Seeder
{
    public function run(): void
    {
        $venues = CulinaryVenue::query()->pluck('id', 'slug');

        $items = [
            [
                'culinary_venue_slug' => 'sate-klathak-pak-bari',
                'name' => 'Paket Sate Lembang Couple',
                'slug' => 'paket-sate-klathak-couple',
                'image' => 'product-8.jpg',
                'description' => '2 porsi sate, nasi, minuman, dan dessert lokal dengan suasana wisata Bandung Barat.',
                'price_per_person' => 9.50,
                'discount_amount' => 0.50,
                'final_price' => 9.00,
                'preparation_time' => 20,
                'serving_size' => '2 Porsi',
                'min_people' => 2,
                'max_people' => 6,
                'availability_status' => 'available',
                'is_active' => true,
            ],
            [
                'culinary_venue_slug' => 'sate-klathak-pak-bari',
                'name' => 'Paket Grup Sate Lembang',
                'slug' => 'paket-grup-sate-klathak',
                'image' => 'product-9.jpg',
                'description' => 'Paket sharing untuk rombongan dengan menu sate premium dan nuansa wisata alam Bandung Barat.',
                'price_per_person' => 8.75,
                'discount_amount' => 0.75,
                'final_price' => 8.00,
                'preparation_time' => 30,
                'serving_size' => '4-20 Porsi',
                'min_people' => 4,
                'max_people' => 20,
                'availability_status' => 'available',
                'is_active' => true,
            ],
            [
                'culinary_venue_slug' => 'seafood-jimbaran-rasa-laut',
                'name' => 'Paket Seafood Panorama',
                'slug' => 'paket-seafood-sunset',
                'image' => 'product-10.jpg',
                'description' => 'Seafood bakar lengkap dengan view wisata alam Bandung Barat yang sejuk dan romantis.',
                'price_per_person' => 14.00,
                'discount_amount' => 1.00,
                'final_price' => 13.00,
                'preparation_time' => 25,
                'serving_size' => '1-2 Porsi',
                'min_people' => 2,
                'max_people' => 10,
                'availability_status' => 'available',
                'is_active' => true,
            ],
            [
                'culinary_venue_slug' => 'gudeg-malioboro-harmoni',
                'name' => 'Paket Kopi Tradisional Bandung Barat',
                'slug' => 'paket-gudeg-tradisional',
                'image' => 'product-11.jpg',
                'description' => 'Menu tradisional lengkap untuk bersantai setelah wisata di Bandung Barat.',
                'price_per_person' => 6.75,
                'discount_amount' => 0.25,
                'final_price' => 6.50,
                'preparation_time' => 15,
                'serving_size' => '1 Porsi',
                'min_people' => 1,
                'max_people' => 15,
                'availability_status' => 'available',
                'is_active' => true,
            ],
        ];

        foreach ($items as $item) {
            $venueId = $venues[$item['culinary_venue_slug']] ?? null;
            if (! $venueId) {
                continue;
            }

            $payload = $item;
            unset($payload['culinary_venue_slug']);
            $payload['culinary_venue_id'] = $venueId;

            CulinaryPackage::query()->updateOrCreate(
                ['slug' => $payload['slug']],
                $payload,
            );
        }
    }
}
