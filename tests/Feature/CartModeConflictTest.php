<?php

namespace Tests\Feature;

use App\Models\TravelPackage;
use App\Support\SiteContent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class CartModeConflictTest extends TestCase
{
    use RefreshDatabase;

    public function test_package_add_is_blocked_when_service_cart_exists_without_replace_mode(): void
    {
        $ticketSlug = (string) (SiteContent::destinationTickets()[0]['slug'] ?? '');

        $this->from(route('destinations'))->post(route('destinations.save'), [
            'destinations' => [$ticketSlug],
            'quantities' => [$ticketSlug => 1],
        ])->assertRedirect(route('cart'));

        $package = TravelPackage::factory()->create(['is_active' => true]);

        $response = $this->from(route('shop'))->post(route('shop.package.add'), [
            'travel_package_id' => $package->id,
            'participants' => 2,
            'departure_date' => Carbon::now()->addDays(3)->toDateString(),
        ]);

        $response->assertRedirect(route('shop'));
        $response->assertSessionHas('error');

        $this->get(route('cart'))
            ->assertOk()
            ->assertViewHas('cartItems', function (array $items): bool {
                return count($items) === 1 && ($items[0]['type'] ?? null) === 'destination';
            });
    }

    public function test_package_add_can_replace_service_cart_when_replace_mode_is_enabled(): void
    {
        $ticketSlug = (string) (SiteContent::destinationTickets()[0]['slug'] ?? '');

        $this->from(route('destinations'))->post(route('destinations.save'), [
            'destinations' => [$ticketSlug],
            'quantities' => [$ticketSlug => 1],
        ])->assertRedirect(route('cart'));

        $package = TravelPackage::factory()->create(['is_active' => true]);

        $response = $this->from(route('shop'))->post(route('shop.package.add'), [
            'travel_package_id' => $package->id,
            'participants' => 2,
            'departure_date' => Carbon::now()->addDays(3)->toDateString(),
            'replace_mode' => 1,
        ]);

        $response->assertRedirect(route('cart'));
        $this->get(route('cart'))
            ->assertOk()
            ->assertViewHas('cartItems', function (array $items): bool {
                return count($items) === 1 && ($items[0]['type'] ?? null) === 'tour_package';
            });
    }

    public function test_service_save_is_blocked_when_package_cart_exists_without_replace_mode(): void
    {
        $package = TravelPackage::factory()->create(['is_active' => true]);

        $this->from(route('shop'))->post(route('shop.package.add'), [
            'travel_package_id' => $package->id,
            'participants' => 2,
            'departure_date' => Carbon::now()->addDays(3)->toDateString(),
        ])->assertRedirect(route('cart'));

        $ticketSlug = (string) (SiteContent::destinationTickets()[0]['slug'] ?? '');

        $response = $this->from(route('destinations'))->post(route('destinations.save'), [
            'destinations' => [$ticketSlug],
            'quantities' => [$ticketSlug => 1],
        ]);

        $response->assertRedirect(route('destinations'));
        $response->assertSessionHas('error');

        $this->get(route('cart'))
            ->assertOk()
            ->assertViewHas('cartItems', function (array $items): bool {
                return count($items) === 1 && ($items[0]['type'] ?? null) === 'tour_package';
            });
    }

    public function test_service_save_can_replace_package_cart_when_replace_mode_is_enabled(): void
    {
        $package = TravelPackage::factory()->create(['is_active' => true]);

        $this->from(route('shop'))->post(route('shop.package.add'), [
            'travel_package_id' => $package->id,
            'participants' => 2,
            'departure_date' => Carbon::now()->addDays(3)->toDateString(),
        ])->assertRedirect(route('cart'));

        $ticketSlug = (string) (SiteContent::destinationTickets()[0]['slug'] ?? '');

        $response = $this->from(route('destinations'))->post(route('destinations.save'), [
            'destinations' => [$ticketSlug],
            'quantities' => [$ticketSlug => 1],
            'replace_mode' => 1,
        ]);

        $response->assertRedirect(route('cart'));
        $this->get(route('cart'))
            ->assertOk()
            ->assertViewHas('cartItems', function (array $items): bool {
                return count($items) === 1 && ($items[0]['type'] ?? null) === 'destination';
            });
    }
}
