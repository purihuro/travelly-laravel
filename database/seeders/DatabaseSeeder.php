<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            TravelPackageSeeder::class,
            BlogPostSeeder::class,
            AccommodationSeeder::class,
            DestinationTicketSeeder::class,
            CulinaryVenueSeeder::class,
            CulinaryPackageSeeder::class,
            CulinaryMenuItemSeeder::class,
            CulinaryPackageItemSeeder::class,
        ]);
    }
}
