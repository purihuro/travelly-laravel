<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('blog_posts')->insert([
            [
                'slug' => 'tips-liburan-hemat',
                'title' => 'Tips Liburan Hemat Tanpa Mengorbankan Kenyamanan',
                'excerpt' => 'Cara menyusun itinerary, memilih paket, dan mengelola budget agar liburan tetap maksimal.',
                'content' => 'Artikel ini membahas strategi menyusun perjalanan yang efisien, mulai dari memilih waktu berangkat, tipe paket, hingga cara mengatur pengeluaran perjalanan.',
                'featured_image' => 'image_1.jpg',
                'author_name' => 'Travelly Team',
                'published_at' => '2026-04-09 09:00:00',
                'is_published' => true,
                'meta_title' => 'Tips Liburan Hemat',
                'meta_description' => 'Panduan praktis menyusun liburan hemat tanpa mengorbankan kenyamanan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'waktu-terbaik-ke-bromo',
                'title' => 'Waktu Terbaik untuk Menikmati Sunrise di Bromo',
                'excerpt' => 'Panduan memilih musim, jam keberangkatan, dan persiapan trip ke kawasan Bromo.',
                'content' => 'Travelly membagikan panduan memilih waktu terbaik ke Bromo, termasuk tips pakaian, waktu berangkat, dan opsi itinerary singkat.',
                'featured_image' => 'image_2.jpg',
                'author_name' => 'Travelly Team',
                'published_at' => '2026-04-02 09:00:00',
                'is_published' => true,
                'meta_title' => 'Waktu Terbaik ke Bromo',
                'meta_description' => 'Tips memilih waktu dan persiapan terbaik untuk menikmati sunrise di Bromo.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'slug' => 'packing-singkat-efektif',
                'title' => 'Checklist Packing yang Ringkas untuk Trip 3 Hari',
                'excerpt' => 'Barang yang wajib dibawa agar perjalanan singkat tetap nyaman, ringan, dan efisien.',
                'content' => 'Checklist ini membantu traveler menyiapkan barang penting untuk trip pendek tanpa membawa beban berlebihan.',
                'featured_image' => 'image_3.jpg',
                'author_name' => 'Travelly Team',
                'published_at' => '2026-03-27 09:00:00',
                'is_published' => true,
                'meta_title' => 'Checklist Packing Trip 3 Hari',
                'meta_description' => 'Daftar bawaan penting untuk perjalanan singkat yang lebih efisien.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
