# Deploy Laravel ke InfinityFree (Minim Risiko Gagal)

Dokumen ini untuk deploy project ini ke InfinityFree dengan fokus sinkronisasi upload file, database, dan route.

## 1) Persiapan di lokal

1. Pastikan test lulus:
   - php artisan test
2. Build vendor production:
   - composer install --no-dev --optimize-autoloader
3. Bersihkan cache agar bundle upload bersih:
   - php artisan optimize:clear

## 2) Siapkan mode upload tanpa symlink

InfinityFree umumnya tidak mendukung symlink storage:link dengan stabil.
Gunakan mode direct public disk.

Di .env hosting:

- FILESYSTEM_DISK=public
- PUBLIC_DISK_DIRECT=true

Dampak:

- Upload disimpan langsung ke public/storage
- URL file tetap /storage/...
- Tidak wajib menjalankan storage:link

## 3) Siapkan database

Karena SSH/Artisan sering terbatas di InfinityFree, gunakan impor SQL.

1. Di lokal buat dump database yang sudah sesuai migration + seeder.
2. Buat database MySQL di panel InfinityFree.
3. Import SQL via phpMyAdmin InfinityFree.
4. Set env database di hosting:
   - DB_CONNECTION=mysql
   - DB_HOST sesuai panel
   - DB_PORT sesuai panel
   - DB_DATABASE sesuai panel
   - DB_USERNAME sesuai panel
   - DB_PASSWORD sesuai panel

## 4) Upload file project

1. Upload source code aplikasi (termasuk folder vendor).
2. Pastikan document root mengarah ke folder public.
3. Jika document root tidak bisa diubah, gunakan pola umum shared hosting:
   - Isi folder public dipindah ke public_html
   - Sesuaikan path bootstrap di public_html/index.php

## 5) Konfigurasi .env production

- APP_ENV=production
- APP_DEBUG=false
- APP_URL=https://domain-kamu
- FILESYSTEM_DISK=public
- PUBLIC_DISK_DIRECT=true
- SESSION_DRIVER=database
- CACHE_STORE=database
- QUEUE_CONNECTION=database

## 6) Permission folder

Pastikan folder ini writable:

- storage
- bootstrap/cache
- public/storage

## 7) Smoke test wajib setelah upload

1. Buka home page
2. Login admin
3. Tambah venue kuliner + upload gambar
4. Tambah paket kuliner + upload galeri gambar
5. Buka halaman customer kuliner, cek gambar tampil
6. Tambah item ke keranjang dan cek data masuk
7. Cek log error:
   - storage/logs/laravel.log

## 8) Jika terjadi 404 massal

Masalah umum: cache route/config tidak sinkron.

Jalankan di lokal sebelum upload ulang:

- php artisan optimize:clear

Lalu upload ulang file bootstrap/cache dan config yang terbaru.

## 9) Checklist sinkronisasi final

- Form field yang tampil = benar-benar diproses backend
- Upload berhasil tersimpan dan bisa diakses URL
- Data tersimpan ke tabel yang benar
- Tidak ada error baru di laravel.log
- Jalur admin -> customer -> cart berjalan end-to-end
