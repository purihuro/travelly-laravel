<?php

namespace App\Support;

use App\Models\DestinationTicket;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Schema;

class SiteContent
{
    private static function setting(string $key, array $defaults): array
    {
        if (! Schema::hasTable('site_settings')) {
            return $defaults;
        }

        $record = SiteSetting::query()->where('key', $key)->first();

        if (! $record || ! $record->value) {
            return $defaults;
        }

        $stored = json_decode($record->value, true);

        if (! is_array($stored)) {
            return $defaults;
        }

        return array_replace_recursive($defaults, $stored);
    }

    public static function defaultHomepageContent(): array
    {
        return [
            'hero_slides' => [
                ['image' => 'assets/images/bg_1.jpg', 'title' => 'Jelajahi Destinasi Impianmu', 'subtitle' => 'Paket wisata pilihan dengan itinerary yang nyaman dan harga transparan.', 'button' => 'Lihat Paket', 'button_route' => 'shop'],
                ['image' => 'assets/images/bg_2.jpg', 'title' => 'Liburan Profesional dan Terpercaya', 'subtitle' => 'Mulai dari city tour, family trip, sampai open trip premium.', 'button' => 'Booking Sekarang', 'button_route' => 'cart'],
            ],
            'service_features' => [
                ['icon' => 'flaticon-shipped', 'bg_class' => 'bg-color-1', 'is_active' => true, 'title' => 'Perjalanan Nyaman', 'text' => 'Itinerary rapi dan support perjalanan responsif'],
                ['icon' => 'flaticon-diet', 'bg_class' => 'bg-color-2', 'is_active' => false, 'title' => 'Paket Terpercaya', 'text' => 'Destinasi kurasi dengan harga yang transparan'],
                ['icon' => 'flaticon-award', 'bg_class' => 'bg-color-3', 'is_active' => false, 'title' => 'Pelayanan Profesional', 'text' => 'Tim berpengalaman untuk liburan yang lebih tenang'],
                ['icon' => 'flaticon-customer-service', 'bg_class' => 'bg-color-4', 'is_active' => false, 'title' => 'Bantuan 24/7', 'text' => 'Respons cepat sebelum, saat, dan sesudah trip'],
            ],
            'category_showcase' => [
                'main_image' => 'assets/images/category.jpg',
                'main_title' => 'Kategori Layanan',
                'main_text' => 'Pilih layanan yang kamu butuhkan, lalu lanjutkan booking dengan alur yang saling terhubung.',
                'button_label' => 'Lihat Layanan',
                'button_route' => 'destinations',
                'cards' => [
                    ['title' => 'Tiket Wisata', 'image' => 'assets/images/category-1.jpg', 'route' => 'destinations', 'icon' => 'ticket'],
                    ['title' => 'Penginapan', 'image' => 'assets/images/category-2.jpg', 'route' => 'accommodations', 'icon' => 'bed'],
                    ['title' => 'Transportasi', 'image' => 'assets/images/category-3.jpg', 'route' => 'transportations', 'icon' => 'bus'],
                    ['title' => 'Kuliner', 'image' => 'assets/images/category-4.jpg', 'route' => 'culinaries', 'icon' => 'utensils'],
                ],
            ],
            'featured_section' => ['subheading' => 'Featured Packages', 'title' => 'Paket Wisata Unggulan', 'text' => 'Pilihan destinasi favorit yang paling sering dipilih traveler Exflore KBB.', 'button_label' => 'Lihat Semua Paket', 'button_route' => 'shop'],
            'newsletter' => ['title' => 'Dapatkan promo terbaru', 'text' => 'Masukkan email untuk update paket wisata dan penawaran spesial.', 'placeholder' => 'Enter email address', 'button_label' => 'Subscribe'],
        ];
    }

    public static function defaultAboutContent(): array
    {
        return [
            'hero_image' => 'assets/images/bg_1.jpg', 'hero_title' => 'About us', 'hero_breadcrumb' => 'About us', 'intro_image' => 'assets/images/about.jpg', 'video_url' => 'https://vimeo.com/45830194', 'intro_heading' => 'Welcome to Exflore KBB, partner liburan profesional Anda', 'paragraph_1' => 'Exflore KBB membantu traveler menikmati destinasi terbaik dengan itinerary yang jelas, support cepat, dan pengalaman yang nyaman dari awal sampai pulang.', 'paragraph_2' => 'Kami merancang perjalanan untuk keluarga, pasangan, dan open trip dengan standar pelayanan yang rapi serta komunikasi yang transparan.', 'cta_label' => 'Lihat Paket', 'cta_route' => 'shop', 'newsletter' => ['title' => 'Subscribe to our Newsletter', 'text' => 'Get e-mail updates about our latest packages and special offers', 'placeholder' => 'Enter email address', 'button_label' => 'Subscribe'], 'counters_background' => 'assets/images/bg_3.jpg', 'counters' => [['number' => '10000', 'label' => 'Happy Customers'], ['number' => '100', 'label' => 'Branches'], ['number' => '1000', 'label' => 'Partner'], ['number' => '100', 'label' => 'Awards']], 'testimony' => ['subheading' => 'Testimony', 'title' => 'Our satisfied customer says', 'text' => 'Cerita nyata dari customer yang sudah mempercayakan perjalanannya bersama Exflore KBB.'],
        ];
    }

    public static function defaultContactContent(): array
    {
        return [
            'hero_image' => 'assets/images/bg_1.jpg', 'hero_title' => 'Contact us', 'hero_breadcrumb' => 'Contact us', 'info_cards' => [['label' => 'Address', 'value' => 'Jl. Malioboro No. 123, Yogyakarta, Indonesia', 'link' => ''], ['label' => 'Phone', 'value' => '+ 1235 2355 98', 'link' => 'tel:+1235235598'], ['label' => 'Email', 'value' => 'info@exflorekbb.co.id', 'link' => 'mailto:info@exflorekbb.co.id'], ['label' => 'Website', 'value' => 'exflorekbb.co.id', 'link' => 'https://exflorekbb.co.id']], 'form_title' => 'Kirim pesan ke tim Exflore KBB', 'placeholders' => ['name' => 'Your Name', 'email' => 'Your Email', 'subject' => 'Subject', 'message' => 'Message'], 'button_label' => 'Send Message',
        ];
    }

    public static function defaultFooterContent(): array
    {
        return [
            'brand_title' => 'Exflore KBB', 'brand_text' => 'Melayani pemesanan paket wisata terbaik ke destinasi dalam dan luar negeri dengan harga menarik.', 'socials' => [['icon' => 'icon-twitter', 'url' => '#'], ['icon' => 'icon-facebook', 'url' => '#'], ['icon' => 'icon-instagram', 'url' => '#']], 'menu_links' => [['label' => 'Destinasi', 'route' => 'shop'], ['label' => 'About', 'route' => 'about'], ['label' => 'Kontak', 'route' => 'contact']], 'help_links_left' => ['Informasi Perjalanan', 'Pembatalan & Refund', 'Syarat & Ketentuan', 'Kebijakan Privasi'], 'help_links_right' => ['FAQs', 'Contact'], 'questions_title' => 'Have a Questions?', 'address' => '203 Fake St. Mountain View, San Francisco, California, USA', 'phone' => '+2 392 3929 210', 'email' => 'info@yourdomain.com', 'copyright_text' => 'Copyright All rights reserved | Exflore KBB',
        ];
    }

    public static function homepageContent(): array
    {
        return self::setting('homepage', self::defaultHomepageContent());
    }

    public static function aboutContent(): array
    {
        return self::setting('about', self::defaultAboutContent());
    }

    public static function contactContent(): array
    {
        return self::setting('contact', self::defaultContactContent());
    }

    public static function footerContent(): array
    {
        return self::setting('footer', self::defaultFooterContent());
    }

    public static function heroSlides(): array
    {
        return self::homepageContent()['hero_slides'];
    }

    public static function categories(): array
    {
        return [
            ['name' => 'All', 'slug' => 'all'],
            ['name' => 'Adventure', 'slug' => 'adventure'],
            ['name' => 'Beach', 'slug' => 'beach'],
            ['name' => 'Culture', 'slug' => 'culture'],
            ['name' => 'Family', 'slug' => 'family'],
        ];
    }

    public static function products(): array
    {
        return [
            ['slug' => 'bali-escape', 'name' => 'Bali Escape', 'category' => 'Beach', 'image' => 'product-1.jpg', 'price' => '$120.00', 'sale_price' => '$80.00', 'discount' => '30%', 'excerpt' => 'Paket 3 hari 2 malam untuk menikmati sunset, kuliner, dan budaya Bali.', 'stock_text' => '12 slot tersedia'],
            ['slug' => 'bandung-retreat', 'name' => 'Bandung Retreat', 'category' => 'Family', 'image' => 'product-2.jpg', 'price' => '$120.00', 'sale_price' => null, 'discount' => null, 'excerpt' => 'Perjalanan santai untuk keluarga dengan destinasi alam dan kuliner.', 'stock_text' => '20 slot tersedia'],
            ['slug' => 'bromo-sunrise', 'name' => 'Bromo Sunrise', 'category' => 'Adventure', 'image' => 'product-3.jpg', 'price' => '$120.00', 'sale_price' => null, 'discount' => null, 'excerpt' => 'Nikmati sunrise terbaik dari kawasan Bromo dengan transport aman.', 'stock_text' => '8 slot tersedia'],
            ['slug' => 'yogyakarta-heritage', 'name' => 'Yogyakarta Heritage', 'category' => 'Culture', 'image' => 'product-4.jpg', 'price' => '$120.00', 'sale_price' => null, 'discount' => null, 'excerpt' => 'Wisata budaya ke keraton, candi, dan sentra kerajinan lokal.', 'stock_text' => '14 slot tersedia'],
            ['slug' => 'labuan-bajo-sailing', 'name' => 'Labuan Bajo Sailing', 'category' => 'Adventure', 'image' => 'product-5.jpg', 'price' => '$120.00', 'sale_price' => '$80.00', 'discount' => '30%', 'excerpt' => 'Sailing trip ke pulau eksotis dengan pengalaman premium.', 'stock_text' => '6 slot tersedia'],
            ['slug' => 'lombok-island-trip', 'name' => 'Lombok Island Trip', 'category' => 'Beach', 'image' => 'product-6.jpg', 'price' => '$120.00', 'sale_price' => null, 'discount' => null, 'excerpt' => 'Pantai, bukit, dan itinerary santai untuk liburan yang berkesan.', 'stock_text' => '10 slot tersedia'],
            ['slug' => 'malang-highland', 'name' => 'Malang Highland', 'category' => 'Family', 'image' => 'product-7.jpg', 'price' => '$120.00', 'sale_price' => null, 'discount' => null, 'excerpt' => 'Kombinasi wisata kota, pegunungan, dan tempat rekreasi keluarga.', 'stock_text' => '16 slot tersedia'],
            ['slug' => 'raja-ampat-signature', 'name' => 'Raja Ampat Signature', 'category' => 'Beach', 'image' => 'product-8.jpg', 'price' => '$120.00', 'sale_price' => null, 'discount' => null, 'excerpt' => 'Eksplorasi laut dan pulau ikonik untuk pengalaman kelas premium.', 'stock_text' => '5 slot tersedia'],
        ];
    }

    public static function destinationTickets(): array
    {
        if (class_exists(DestinationTicket::class) && Schema::hasTable('destination_tickets')) {
            $items = DestinationTicket::query()
                ->active()
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get()
                ->map(fn (DestinationTicket $ticket) => [
                    'slug' => $ticket->slug,
                    'name' => $ticket->name,
                    'location' => $ticket->location,
                    'image' => $ticket->image ?: 'product-1.jpg',
                    'price' => (float) $ticket->price,
                    'category' => $ticket->category,
                    'open_hours' => $ticket->open_hours,
                    'duration_minutes' => $ticket->duration_minutes,
                    'audience' => $ticket->audience,
                    'description' => $ticket->description ?: 'Tiket destinasi wisata pilihan untuk customer.',
                ])
                ->all();

            if ($items !== []) {
                return $items;
            }
        }

        return [
            ['slug' => 'heha-sky-view', 'name' => 'HeHa Sky View', 'location' => 'Yogyakarta', 'category' => 'Panorama', 'open_hours' => '09.00 - 21.00', 'duration_minutes' => 120, 'audience' => 'Couple / Grup', 'image' => 'product-1.jpg', 'price' => 8.50, 'description' => 'Spot sunset populer dengan view kota dan area foto ikonik.'],
            ['slug' => 'candi-borobudur', 'name' => 'Candi Borobudur', 'location' => 'Magelang', 'category' => 'Budaya', 'open_hours' => '06.30 - 16.30', 'duration_minutes' => 180, 'audience' => 'Keluarga / Grup', 'image' => 'product-4.jpg', 'price' => 15.00, 'description' => 'Destinasi warisan dunia dengan pengalaman budaya yang kuat.'],
            ['slug' => 'bromo-view-point', 'name' => 'Bromo View Point', 'location' => 'Jawa Timur', 'category' => 'Adventure', 'open_hours' => '24 jam', 'duration_minutes' => 150, 'audience' => 'Grup / Adventure', 'image' => 'product-3.jpg', 'price' => 12.75, 'description' => 'Tiket masuk area sunrise dan panorama pegunungan Bromo.'],
            ['slug' => 'uluwatu-cliff', 'name' => 'Uluwatu Cliff', 'location' => 'Bali', 'category' => 'Pantai', 'open_hours' => '07.00 - 19.00', 'duration_minutes' => 120, 'audience' => 'Couple / Keluarga', 'image' => 'product-6.jpg', 'price' => 10.25, 'description' => 'Pemandangan tebing laut dan akses ke area pertunjukan budaya.'],
            ['slug' => 'pink-beach-komodo', 'name' => 'Pink Beach Komodo', 'location' => 'Labuan Bajo', 'category' => 'Pantai', 'open_hours' => '08.00 - 17.00', 'duration_minutes' => 210, 'audience' => 'Couple / Grup', 'image' => 'product-5.jpg', 'price' => 18.40, 'description' => 'Akses destinasi pantai eksotis dan area konservasi laut.'],
            ['slug' => 'kawah-putih', 'name' => 'Kawah Putih', 'location' => 'Bandung', 'category' => 'Alam', 'open_hours' => '07.00 - 17.00', 'duration_minutes' => 90, 'audience' => 'Keluarga / Couple', 'image' => 'product-2.jpg', 'price' => 9.80, 'description' => 'Destinasi alam vulkanik dengan suasana sejuk dan fotogenik.'],
        ];
    }

    public static function featuredProducts(): array
    {
        return array_slice(self::products(), 0, 8);
    }

    public static function blogPosts(): array
    {
        return [
            ['slug' => 'tips-liburan-hemat', 'title' => 'Tips Liburan Hemat Tanpa Mengorbankan Kenyamanan', 'image' => 'image_1.jpg', 'date' => 'April 09, 2026', 'author' => 'Exflore KBB Team', 'comments' => 19, 'excerpt' => 'Cara menyusun itinerary, memilih paket, dan mengelola budget agar liburan tetap maksimal.'],
            ['slug' => 'waktu-terbaik-ke-bromo', 'title' => 'Waktu Terbaik untuk Menikmati Sunrise di Bromo', 'image' => 'image_2.jpg', 'date' => 'April 02, 2026', 'author' => 'Exflore KBB Team', 'comments' => 12, 'excerpt' => 'Panduan memilih musim, jam keberangkatan, dan persiapan trip ke kawasan Bromo.'],
            ['slug' => 'packing-singkat-efektif', 'title' => 'Checklist Packing yang Ringkas untuk Trip 3 Hari', 'image' => 'image_3.jpg', 'date' => 'March 27, 2026', 'author' => 'Exflore KBB Team', 'comments' => 8, 'excerpt' => 'Barang yang wajib dibawa agar perjalanan singkat tetap nyaman, ringan, dan efisien.'],
        ];
    }

    public static function cartItems(): array
    {
        return [
            ['slug' => 'bromo-sunrise', 'name' => 'Bromo Sunrise', 'image' => 'product-3.jpg', 'description' => 'Open trip sunrise dengan jeep, tiket masuk, dan dokumentasi dasar.', 'price' => 4.90, 'quantity' => 1],
            ['slug' => 'yogyakarta-heritage', 'name' => 'Yogyakarta Heritage', 'image' => 'product-4.jpg', 'description' => 'Paket budaya kota dan candi dengan guide lokal berpengalaman.', 'price' => 15.70, 'quantity' => 1],
        ];
    }

    public static function wishlistItems(): array
    {
        return [
            ['slug' => 'bali-escape', 'name' => 'Bali Escape', 'image' => 'product-1.jpg', 'description' => 'Cocok untuk honeymoon dan short escape.', 'price' => 4.90, 'quantity' => 1],
            ['slug' => 'bandung-retreat', 'name' => 'Bandung Retreat', 'image' => 'product-2.jpg', 'description' => 'Liburan keluarga yang santai dan sejuk.', 'price' => 15.70, 'quantity' => 1],
        ];
    }

    public static function checkoutForm(): array
    {
        return ['first_name' => 'Alya', 'last_name' => 'Pratama', 'country' => 'Indonesia', 'address_line_1' => 'Jl. Merdeka No. 45', 'address_line_2' => 'Apartment 12B', 'city' => 'Jakarta', 'postal_code' => '10110', 'phone' => '+62 812 3456 7890', 'email' => 'alya@example.com'];
    }

    public static function checkoutCountries(): array
    {
        return ['Indonesia', 'Singapore', 'Malaysia', 'Thailand', 'Japan', 'South Korea'];
    }

    public static function paymentMethods(): array
    {
        return ['Transfer Bank', 'Virtual Account', 'Kartu Kredit', 'E-Wallet'];
    }
}
