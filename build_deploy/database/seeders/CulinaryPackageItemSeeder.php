<?php

namespace Database\Seeders;

use App\Models\CulinaryMenuItem;
use App\Models\CulinaryPackage;
use App\Models\CulinaryPackageItem;
use Illuminate\Database\Seeder;

class CulinaryPackageItemSeeder extends Seeder
{
    public function run(): void
    {
        $packages = CulinaryPackage::query()
            ->get(['id', 'slug', 'culinary_venue_id'])
            ->keyBy('slug');

        $menuItems = CulinaryMenuItem::query()
            ->get(['id', 'slug', 'culinary_venue_id'])
            ->keyBy(fn (CulinaryMenuItem $item) => $item->culinary_venue_id . '|' . $item->slug);

        $items = [
            ['package_slug' => 'paket-sate-klathak-couple', 'menu_slug' => 'sate-klathak', 'quantity' => 2, 'sort_order' => 1],
            ['package_slug' => 'paket-sate-klathak-couple', 'menu_slug' => 'nasi-putih', 'quantity' => 2, 'sort_order' => 2],
            ['package_slug' => 'paket-sate-klathak-couple', 'menu_slug' => 'es-teh-manis', 'quantity' => 2, 'sort_order' => 3],

            ['package_slug' => 'paket-grup-sate-klathak', 'menu_slug' => 'sate-klathak', 'quantity' => 4, 'sort_order' => 1],
            ['package_slug' => 'paket-grup-sate-klathak', 'menu_slug' => 'nasi-putih', 'quantity' => 4, 'sort_order' => 2],
            ['package_slug' => 'paket-grup-sate-klathak', 'menu_slug' => 'es-teh-manis', 'quantity' => 4, 'sort_order' => 3],

            ['package_slug' => 'paket-seafood-sunset', 'menu_slug' => 'seafood-bakar-mix', 'quantity' => 2, 'sort_order' => 1],
            ['package_slug' => 'paket-seafood-sunset', 'menu_slug' => 'soup-laut', 'quantity' => 2, 'sort_order' => 2],
            ['package_slug' => 'paket-seafood-sunset', 'menu_slug' => 'puding-kelapa', 'quantity' => 2, 'sort_order' => 3],

            ['package_slug' => 'paket-gudeg-tradisional', 'menu_slug' => 'gudeg-komplit', 'quantity' => 1, 'sort_order' => 1],
            ['package_slug' => 'paket-gudeg-tradisional', 'menu_slug' => 'tahu-tempe-bacem', 'quantity' => 1, 'sort_order' => 2],
            ['package_slug' => 'paket-gudeg-tradisional', 'menu_slug' => 'wedang-jahe', 'quantity' => 1, 'sort_order' => 3],
        ];

        foreach ($items as $item) {
            /** @var \App\Models\CulinaryPackage|null $package */
            $package = $packages->get($item['package_slug']);
            $menuItemKey = $package?->culinary_venue_id . '|' . $item['menu_slug'];
            /** @var \App\Models\CulinaryMenuItem|null $menuItem */
            $menuItem = $menuItems->get($menuItemKey);

            if (! $package || ! $menuItem) {
                continue;
            }

            CulinaryPackageItem::query()->updateOrCreate(
                [
                    'culinary_package_id' => $package->id,
                    'culinary_menu_item_id' => $menuItem->id,
                ],
                [
                    'quantity' => $item['quantity'],
                    'notes' => null,
                    'sort_order' => $item['sort_order'],
                ],
            );
        }
    }
}
