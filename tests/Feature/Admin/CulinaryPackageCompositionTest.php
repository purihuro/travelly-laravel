<?php

namespace Tests\Feature\Admin;

use App\Models\CulinaryPackage;
use App\Models\CulinaryVenue;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CulinaryPackageCompositionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_package_without_menu_composition_fields(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $venue = CulinaryVenue::query()->create([
            'name' => 'Venue A',
            'slug' => 'venue-a',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.culinary-packages.store'), [
            'culinary_venue_id' => $venue->id,
            'name' => 'Paket Kombo A',
            'slug' => 'paket-kombo-a',
            'price_per_person' => 10,
            'availability_status' => 'available',
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.culinary-packages.index'));

        $package = CulinaryPackage::query()->where('slug', 'paket-kombo-a')->firstOrFail();

        $this->assertDatabaseHas('culinary_packages', [
            'id' => $package->id,
            'culinary_venue_id' => $venue->id,
            'name' => 'Paket Kombo A',
            'discount_amount' => '0.00',
            'final_price' => '10.00',
        ]);

        $this->assertDatabaseCount('culinary_package_items', 0);
    }

    public function test_admin_can_update_package_without_menu_composition_fields(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $venueA = CulinaryVenue::query()->create([
            'name' => 'Venue A',
            'slug' => 'venue-a',
            'is_active' => true,
        ]);

        $package = CulinaryPackage::query()->create([
            'culinary_venue_id' => $venueA->id,
            'name' => 'Paket A',
            'slug' => 'paket-a',
            'price_per_person' => 8,
            'min_people' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.culinary-packages.update', $package), [
            'culinary_venue_id' => $venueA->id,
            'name' => 'Paket A Updated',
            'slug' => 'paket-a',
            'price_per_person' => 8,
            'availability_status' => 'available',
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.culinary-packages.edit', $package));

        $this->assertDatabaseHas('culinary_packages', [
            'id' => $package->id,
            'name' => 'Paket A Updated',
        ]);

        $this->assertDatabaseCount('culinary_package_items', 0);
    }
}
