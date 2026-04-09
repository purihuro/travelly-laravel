<?php

namespace Database\Seeders;

use App\Models\CulinaryMenuItem;
use App\Models\CulinaryVenue;
use Illuminate\Database\Seeder;

class CulinaryMenuItemSeeder extends Seeder
{
    public function run(): void
    {
        $venues = CulinaryVenue::query()->pluck('id', 'slug');

        $items = [
            [
                'venue_slug' => 'sate-klathak-pak-bari',
                'name' => 'Sate Lembang',
                'slug' => 'sate-klathak',
                'category' => 'main',
                'price' => 5.50,
                'ingredients' => ['daging kambing', 'garam', 'merica'],
                'allergies' => ['daging kambing'],
                'description' => 'Sate hangat khas suasana wisata pegunungan Bandung Barat.',
                'sort_order' => 1,
            ],
            [
                'venue_slug' => 'sate-klathak-pak-bari',
                'name' => 'Nasi Liwet',
                'slug' => 'nasi-putih',
                'category' => 'side',
                'price' => 1.00,
                'ingredients' => ['beras'],
                'allergies' => [],
                'description' => 'Pendamping hangat yang cocok untuk santap di area wisata Bandung Barat.',
                'sort_order' => 2,
            ],
            [
                'venue_slug' => 'sate-klathak-pak-bari',
                'name' => 'Es Teh Bandung',
                'slug' => 'es-teh-manis',
                'category' => 'drink',
                'price' => 0.75,
                'ingredients' => ['teh', 'gula'],
                'allergies' => [],
                'description' => 'Minuman ringan segar untuk menemani jalan-jalan di Bandung Barat.',
                'sort_order' => 3,
            ],
            [
                'venue_slug' => 'seafood-jimbaran-rasa-laut',
                'name' => 'Seafood Bakar Mix',
                'slug' => 'seafood-bakar-mix',
                'category' => 'main',
                'price' => 8.00,
                'ingredients' => ['udang', 'cumi', 'ikan'],
                'allergies' => ['seafood'],
                'description' => 'Mix seafood bakar pilihan untuk pengalaman kuliner wisata Bandung Barat.',
                'sort_order' => 1,
            ],
            [
                'venue_slug' => 'seafood-jimbaran-rasa-laut',
                'name' => 'Sup Hangat',
                'slug' => 'soup-laut',
                'category' => 'side',
                'price' => 2.25,
                'ingredients' => ['kaldu ikan', 'sayuran'],
                'allergies' => ['seafood'],
                'description' => 'Sup hangat pelengkap paket setelah menikmati wisata alam Bandung Barat.',
                'sort_order' => 2,
            ],
            [
                'venue_slug' => 'seafood-jimbaran-rasa-laut',
                'name' => 'Puding Kelapa',
                'slug' => 'puding-kelapa',
                'category' => 'dessert',
                'price' => 1.50,
                'ingredients' => ['kelapa', 'susu'],
                'allergies' => ['susu'],
                'description' => 'Dessert penutup yang ringan untuk wisata kuliner Bandung Barat.',
                'sort_order' => 3,
            ],
            [
                'venue_slug' => 'gudeg-malioboro-harmoni',
                'name' => 'Kopi Susu Tradisional',
                'slug' => 'gudeg-komplit',
                'category' => 'main',
                'price' => 4.75,
                'ingredients' => ['nangka muda', 'santan', 'ayam'],
                'allergies' => ['ayam'],
                'description' => 'Menu hangat untuk istirahat sejenak saat wisata di Bandung Barat.',
                'sort_order' => 1,
            ],
            [
                'venue_slug' => 'gudeg-malioboro-harmoni',
                'name' => 'Tahu Tempe Bacem',
                'slug' => 'tahu-tempe-bacem',
                'category' => 'side',
                'price' => 1.50,
                'ingredients' => ['tahu', 'tempe', 'gula jawa'],
                'allergies' => ['kedelai'],
                'description' => 'Lauk pendamping yang cocok untuk paket wisata kuliner Bandung Barat.',
                'sort_order' => 2,
            ],
            [
                'venue_slug' => 'gudeg-malioboro-harmoni',
                'name' => 'Wedang Jahe',
                'slug' => 'wedang-jahe',
                'category' => 'drink',
                'price' => 0.90,
                'ingredients' => ['jahe', 'gula merah'],
                'allergies' => [],
                'description' => 'Minuman hangat tradisional untuk udara sejuk Bandung Barat.',
                'sort_order' => 3,
            ],
        ];

        foreach ($items as $item) {
            $venueId = $venues[$item['venue_slug']] ?? null;

            if (! $venueId) {
                continue;
            }

            $payload = $item;
            unset($payload['venue_slug']);
            $payload['culinary_venue_id'] = $venueId;

            CulinaryMenuItem::query()->updateOrCreate(
                ['culinary_venue_id' => $venueId, 'slug' => $payload['slug']],
                $payload,
            );
        }
    }
}
