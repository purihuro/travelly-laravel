# Database Awal

Skema awal yang sudah disiapkan ada di:

- `database/schema.sql`
- `database/migrations/`
- `database/seeders/`

Tabel inti:

- `travel_packages`
- `blog_posts`
- `bookings`
- `booking_items`
- `contact_messages`

Tujuan:

- `travel_packages` untuk katalog paket wisata
- `blog_posts` untuk artikel blog
- `bookings` untuk data checkout utama
- `booking_items` untuk item paket di dalam booking
- `contact_messages` untuk form kontak

Saat Laravel penuh sudah terpasang, skema ini bisa diterjemahkan menjadi migration Laravel.

Urutan pakai saat Laravel sudah aktif:

1. `php artisan migrate`
2. `php artisan db:seed`

Seeder awal yang sudah tersedia:

- `TravelPackageSeeder`
- `BlogPostSeeder`
- `DatabaseSeeder`
