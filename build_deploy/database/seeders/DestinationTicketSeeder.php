<?php

namespace Database\Seeders;

use App\Models\DestinationTicket;
use Illuminate\Database\Seeder;

class DestinationTicketSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'Dusun Bambu', 'slug' => 'heha-sky-view', 'location' => 'Lembang, Bandung Barat', 'category' => 'Panorama', 'open_hours' => '09.00 - 21.00', 'duration_minutes' => 120, 'audience' => 'Couple / Grup', 'image' => 'product-8.jpg', 'price' => 8.50, 'description' => 'Area wisata populer dengan view alam, spot foto, dan suasana sejuk Bandung Barat.', 'is_active' => true, 'sort_order' => 1],
            ['name' => 'The Great Asia Africa', 'slug' => 'candi-borobudur', 'location' => 'Lembang, Bandung Barat', 'category' => 'Budaya', 'open_hours' => '08.00 - 18.00', 'duration_minutes' => 180, 'audience' => 'Keluarga / Grup', 'image' => 'product-9.jpg', 'price' => 15.00, 'description' => 'Destinasi tematik dengan pengalaman budaya, foto, dan edukasi keluarga.', 'is_active' => true, 'sort_order' => 2],
            ['name' => 'Tangkuban Parahu', 'slug' => 'bromo-view-point', 'location' => 'Lembang, Bandung Barat', 'category' => 'Adventure', 'open_hours' => '24 jam', 'duration_minutes' => 150, 'audience' => 'Grup / Adventure', 'image' => 'product-10.jpg', 'price' => 12.75, 'description' => 'Tiket masuk area sunrise dan panorama pegunungan vulkanik Bandung Barat.', 'is_active' => true, 'sort_order' => 3],
            ['name' => 'Farmhouse Lembang', 'slug' => 'uluwatu-cliff', 'location' => 'Lembang, Bandung Barat', 'category' => 'Pantai', 'open_hours' => '07.00 - 19.00', 'duration_minutes' => 120, 'audience' => 'Couple / Keluarga', 'image' => 'product-11.jpg', 'price' => 10.25, 'description' => 'Spot wisata keluarga dengan nuansa Eropa dan area foto yang ikonik.', 'is_active' => true, 'sort_order' => 4],
            ['name' => 'Floating Market', 'slug' => 'pink-beach-komodo', 'location' => 'Lembang, Bandung Barat', 'category' => 'Pantai', 'open_hours' => '08.00 - 17.00', 'duration_minutes' => 210, 'audience' => 'Couple / Grup', 'image' => 'product-12.jpg', 'price' => 18.40, 'description' => 'Wisata kuliner dan rekreasi keluarga dengan suasana khas Bandung Barat.', 'is_active' => true, 'sort_order' => 5],
            ['name' => 'Orchid Forest', 'slug' => 'kawah-putih', 'location' => 'Cikole, Bandung Barat', 'category' => 'Alam', 'open_hours' => '07.00 - 17.00', 'duration_minutes' => 90, 'audience' => 'Keluarga / Couple', 'image' => 'product-2.jpg', 'price' => 9.80, 'description' => 'Destinasi alam hutan pinus dengan suasana sejuk dan fotogenik.', 'is_active' => true, 'sort_order' => 6],
        ];

        foreach ($items as $item) {
            DestinationTicket::query()->updateOrCreate(
                ['slug' => $item['slug']],
                $item,
            );
        }
    }
};
