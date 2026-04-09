<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;
use App\Models\Booking;
use App\Models\CartItem;
use App\Models\CulinaryPackage;
use App\Models\DestinationTicket;
use App\Models\TravelPackage;
use App\Support\SiteContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PageController extends Controller
{
    private function transportationOptions(): array
    {
        return [
            'none' => ['label' => 'Tanpa transportasi', 'price' => 0, 'description' => 'Atur transportasi sendiri.'],
            'rental_car' => ['label' => 'Sewa kendaraan', 'price' => 45.00, 'description' => 'Sewa kendaraan mandiri untuk perjalanan fleksibel.'],
            'driver_car' => ['label' => 'Mobil + driver', 'price' => 68.00, 'description' => 'Mobil dengan driver untuk antar jemput dan trip harian.'],
            'airport_transfer' => ['label' => 'Airport transfer', 'price' => 24.00, 'description' => 'Jemput atau antar bandara ke lokasi tujuan.'],
            'shuttle' => ['label' => 'Shuttle wisata', 'price' => 18.00, 'description' => 'Shuttle bersama ke area wisata populer.'],
        ];
    }

    private function buildTotals(array $items, float $discount = 0.0, float $delivery = 0.0): array
    {
        $subtotal = 0.0;

        foreach ($items as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }

        return [
            'subtotal' => $subtotal,
            'delivery' => $delivery,
            'discount' => $discount,
            'total' => $subtotal + $delivery - $discount,
        ];
    }

    private function findBySlug(array $items, ?string $slug): array
    {
        foreach ($items as $item) {
            if (($item['slug'] ?? null) === $slug) {
                return $item;
            }
        }

        return $items[0];
    }

    private function packageImagePath(TravelPackage $package): string
    {
        if (! $package->featured_image) {
            return 'assets/images/product-1.jpg';
        }

        return $package->featured_image;
    }

    private function packageDestinations(TravelPackage $package): array
    {
        $location = trim((string) $package->location);
        if ($location === '') {
            return ['City tour', 'Kuliner lokal', 'Belanja oleh-oleh'];
        }

        $destinations = DestinationTicket::query()
            ->active()
            ->where('location', 'like', '%' . $location . '%')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->limit(4)
            ->pluck('name')
            ->all();

        if ($destinations !== []) {
            return $destinations;
        }

        return [
            'Kedatangan dan orientasi ' . $location,
            'Wisata inti ' . $location,
            'Pusat oleh-oleh ' . $location,
        ];
    }

    private function packageToCartItem(TravelPackage $package, int $participants, string $departureDate): array
    {
        $destinations = $this->packageDestinations($package);

        return [
            'slug' => 'package-' . $package->slug . '-' . $departureDate,
            'type' => 'tour_package',
            'source' => 'preset',
            'travel_package_id' => $package->id,
            'name' => $package->title,
            'image' => $this->packageImagePath($package),
            'description' => $package->summary ?: 'Paket wisata siap berangkat dengan itinerary terstruktur.',
            'price' => (float) $package->effective_price,
            'quantity' => $participants,
            'duration_days' => (int) $package->duration_days,
            'departure_date' => $departureDate,
            'location' => $package->location,
            'included_destinations' => $destinations,
        ];
    }

    private function inferItemSource(array $item): string
    {
        if (isset($item['source']) && in_array($item['source'], ['preset', 'single', 'custom'], true)) {
            return (string) $item['source'];
        }

        $type = (string) ($item['type'] ?? 'destination');

        return in_array($type, ['tour_package', 'travel_package'], true) ? 'preset' : 'single';
    }

    private function normalizeCartItems(array $items): array
    {
        return collect($items)
            ->map(function (array $item) {
                $item['source'] = $this->inferItemSource($item);

                return $item;
            })
            ->values()
            ->all();
    }

    private function cartCategory(array $item): string
    {
        $type = (string) ($item['type'] ?? 'destination');

        return match ($type) {
            'tour_package', 'travel_package' => 'package',
            'destination' => 'destination',
            'accommodation' => 'accommodation',
            'transportation' => 'transportation',
            'culinary' => 'culinary',
            default => 'other',
        };
    }

    private function resolveCartMode(array $items): string
    {
        if ($items === []) {
            return 'single';
        }

        $categories = collect($items)->map(fn (array $item) => $this->cartCategory($item))->unique()->values();
        $hasPackage = $categories->contains('package');
        $serviceCategories = $categories->reject(fn (string $category) => $category === 'package');

        if ($hasPackage && $serviceCategories->isEmpty()) {
            return 'preset';
        }

        if (! $hasPackage && $serviceCategories->count() <= 1) {
            return 'single';
        }

        return 'custom';
    }

    private function modeLabel(string $mode): string
    {
        return match ($mode) {
            'preset' => 'Paket Siap Pilih',
            'custom' => 'Build Your Own Trip',
            default => 'Single Service',
        };
    }

    private function wantsModeReplace(Request $request): bool
    {
        return $request->boolean('replace_mode');
    }

    private function resetSelectionSession(Request $request): void
    {
        $request->session()->forget([
            'selected_destination_tickets',
            'selected_accommodation',
            'selected_transportation',
            'selected_culinary',
            'destination_payment_option',
            'onsite_reservation',
        ]);
    }

    private function ensureServiceFlowCompatible(Request $request): ?RedirectResponse
    {
        $items = $this->currentCartItems($request);
        $mode = $this->resolveCartMode($items);

        if ($mode !== 'preset') {
            return null;
        }

        if (! $this->wantsModeReplace($request)) {
            return redirect()->back()->withInput()->with(
                'error',
                'Keranjang Anda sedang berisi Paket Siap Pilih. Aktifkan ganti mode terlebih dahulu untuk beralih ke booking per layanan.'
            );
        }

        $this->clearCartItems($request);
        $this->resetSelectionSession($request);

        return null;
    }

    private function ensurePackageFlowCompatible(Request $request): ?RedirectResponse
    {
        $items = $this->currentCartItems($request);
        $mode = $this->resolveCartMode($items);

        if (! in_array($mode, ['single', 'custom'], true)) {
            return null;
        }

        $hasServiceItems = collect($items)
            ->map(fn (array $item) => $this->cartCategory($item))
            ->contains(fn (string $category) => $category !== 'package');

        if (! $hasServiceItems) {
            return null;
        }

        if (! $this->wantsModeReplace($request)) {
            return redirect()->back()->withInput()->with(
                'error',
                'Keranjang Anda sedang berisi booking per layanan. Aktifkan ganti mode terlebih dahulu untuk beralih ke Paket Siap Pilih.'
            );
        }

        $this->clearCartItems($request);
        $this->resetSelectionSession($request);

        return null;
    }

    private function cartSessionId(Request $request): string
    {
        $request->session()->start();

        return $request->session()->getId();
    }

    private function persistCartItems(Request $request, array $items): void
    {
        $sessionId = $this->cartSessionId($request);
        $items = $this->normalizeCartItems($items);
        $mode = $this->resolveCartMode($items);

        if ($mode === 'custom') {
            $items = collect($items)
                ->map(function (array $item) {
                    if (($item['source'] ?? null) !== 'preset') {
                        $item['source'] = 'custom';
                    }

                    return $item;
                })
                ->values()
                ->all();
        }

        CartItem::query()->where('session_id', $sessionId)->delete();

        foreach ($items as $item) {
            CartItem::query()->create([
                'session_id' => $sessionId,
                'user_id' => auth()->id(),
                'slug' => (string) ($item['slug'] ?? Str::random(16)),
                'type' => (string) ($item['type'] ?? 'destination'),
                'payload' => $item,
            ]);
        }

        $request->session()->put('destination_cart', $items);
        $request->session()->put('cart_mode', $mode);
    }

    private function clearCartItems(Request $request): void
    {
        CartItem::query()->where('session_id', $this->cartSessionId($request))->delete();
        $request->session()->forget('destination_cart');
        $request->session()->forget('cart_mode');
    }

    private function currentCartItems(Request $request): array
    {
        $items = CartItem::query()
            ->where('session_id', $this->cartSessionId($request))
            ->orderBy('id')
            ->get()
            ->map(function (CartItem $item) {
                $payload = is_array($item->payload) ? $item->payload : [];

                if (! isset($payload['slug'])) {
                    $payload['slug'] = $item->slug;
                }

                if (! isset($payload['type'])) {
                    $payload['type'] = $item->type;
                }

                return $payload;
            })
            ->all();
        $items = $this->normalizeCartItems($items);

        if ($items !== []) {
            $request->session()->put('destination_cart', $items);
            $request->session()->put('cart_mode', $this->resolveCartMode($items));

            return $items;
        }

        if ($request->session()->has('destination_cart')) {
            return $request->session()->get('destination_cart', []);
        }

        return [];
    }

    private function selectedAccommodation(Request $request): ?array
    {
        $selectedAccommodation = $request->session()->get('selected_accommodation');

        if ($selectedAccommodation) {
            return $selectedAccommodation;
        }

        return collect($this->currentCartItems($request))->firstWhere('type', 'accommodation');
    }

    private function selectedTransportation(Request $request): ?array
    {
        $selectedTransportation = $request->session()->get('selected_transportation');

        if ($selectedTransportation) {
            return $selectedTransportation;
        }

        return collect($this->currentCartItems($request))->firstWhere('type', 'transportation');
    }

    private function selectedCulinary(Request $request): ?array
    {
        $selectedCulinary = $request->session()->get('selected_culinary');

        if ($selectedCulinary) {
            return $selectedCulinary;
        }

        return collect($this->currentCartItems($request))->firstWhere('type', 'culinary');
    }

    private function selectedTickets(Request $request): array
    {
        $cartDestinations = collect($this->currentCartItems($request))
            ->filter(fn (array $item) => ($item['type'] ?? null) === 'destination')
            ->values()
            ->all();

        if (! empty($cartDestinations)) {
            return $cartDestinations;
        }

        return $request->session()->get('selected_destination_tickets', []);
    }

    private function selectionSummaryItems(Request $request): array
    {
        $items = $this->selectedTickets($request);

        if ($selectedAccommodation = $this->selectedAccommodation($request)) {
            $items[] = $selectedAccommodation;
        }

        if ($selectedTransportation = $this->selectedTransportation($request)) {
            $items[] = $selectedTransportation;
        }

        if ($selectedCulinary = $this->selectedCulinary($request)) {
            $items[] = $selectedCulinary;
        }

        return $items;
    }

    private function tripBuilderSummaryRows(Request $request): array
    {
        $rows = [];

        if ($selectedTransportation = $this->selectedTransportation($request)) {
            $pickupAt = null;
            if (! empty($selectedTransportation['pickup_time'])) {
                try {
                    $pickupAt = \Illuminate\Support\Carbon::parse($selectedTransportation['pickup_time']);
                } catch (\Throwable $e) {
                    $pickupAt = null;
                }
            }

            $rows[] = [
                'service' => '🚗 Transportasi',
                'detail' => trim(($selectedTransportation['name'] ?? '-') . ' · Jemput: ' . ($selectedTransportation['pickup_location'] ?? '-') . ' · ' . (($selectedTransportation['passenger_count'] ?? 1) . ' org')),
                'date' => $pickupAt ? $pickupAt->format('d M Y') : '-',
                'time' => $pickupAt ? $pickupAt->format('H:i') : '-',
                'notes' => $selectedTransportation['transport_notes'] ?? '-',
            ];
        }

        if ($selectedAccommodation = $this->selectedAccommodation($request)) {
            $rows[] = [
                'service' => '🏨 Penginapan',
                'detail' => trim(($selectedAccommodation['name'] ?? '-') . ' · ' . ($selectedAccommodation['location'] ?? '-') . ' · ' . (($selectedAccommodation['nights'] ?? 1) . ' malam')),
                'date' => '-',
                'time' => '-',
                'notes' => $selectedAccommodation['accommodation_notes'] ?? '-',
            ];
        }

        if ($selectedCulinary = $this->selectedCulinary($request)) {
            $reservationAt = null;
            if (! empty($selectedCulinary['reservation_date'])) {
                try {
                    $reservationAt = \Illuminate\Support\Carbon::parse($selectedCulinary['reservation_date']);
                } catch (\Throwable $e) {
                    $reservationAt = null;
                }
            }

            $rows[] = [
                'service' => '🍽️ Kuliner',
                'detail' => trim(($selectedCulinary['name'] ?? '-') . ' · ' . ($selectedCulinary['venue_name'] ?? 'Rumah Makan') . ' · ' . (($selectedCulinary['quantity'] ?? 1) . ' org')),
                'date' => $reservationAt ? $reservationAt->format('d M Y') : '-',
                'time' => $selectedCulinary['arrival_time'] ?? '-',
                'notes' => $selectedCulinary['culinary_notes'] ?? '-',
            ];
        }

        $destinationRows = collect($this->currentCartItems($request))
            ->filter(fn (array $item) => ($item['type'] ?? null) === 'destination')
            ->values()
            ->all();

        if ($destinationRows === []) {
            $destinationRows = $this->selectedTickets($request);
        }

        foreach ($destinationRows as $destination) {
            $rows[] = [
                'service' => '🎫 Wisata',
                'detail' => trim(($destination['name'] ?? '-') . ' · ' . (($destination['quantity'] ?? 1) . ' tiket')),
                'date' => $destination['visit_date'] ?? '-',
                'time' => $destination['visit_time'] ?? '-',
                'notes' => $destination['destination_notes'] ?? '-',
            ];
        }

        return $rows;
    }

    private function syncSelectionCheckoutState(Request $request, string $paymentOption): void
    {
        $summaryItems = $this->selectionSummaryItems($request);

        $request->session()->put('destination_payment_option', $paymentOption);

        if ($paymentOption === 'app') {
            $this->persistCartItems($request, $summaryItems);
            $request->session()->forget('onsite_reservation');
            return;
        }

        $this->clearCartItems($request);
        $request->session()->put('onsite_reservation', [
            'items' => $this->selectedTickets($request),
            'accommodation' => $this->selectedAccommodation($request),
            'transportation' => $this->selectedTransportation($request),
            'culinary' => $this->selectedCulinary($request),
            'totals' => $this->buildTotals($summaryItems),
            'message' => 'Reservasi dibuat dengan opsi bayar di tempat. Tunjukkan detail ini saat datang ke lokasi.',
        ]);
    }

    private function resolveNextRoute(Request $request, array $allowed, string $default = 'cart'): string
    {
        $redirectTo = (string) $request->input('redirect_to', $default);

        return in_array($redirectTo, $allowed, true) ? $redirectTo : $default;
    }

    public function home(): View
    {
        return view('pages.home', [
            'homepage' => SiteContent::homepageContent(),
            'slides' => SiteContent::heroSlides(),
            'featuredProducts' => SiteContent::featuredProducts(),
        ]);
    }

    public function about(): View
    {
        return view('pages.about', [
            'aboutContent' => SiteContent::aboutContent(),
            'homepage' => SiteContent::homepageContent(),
            'contactContent' => SiteContent::contactContent(),
        ]);
    }

    public function cart(Request $request): View
    {
        $items = $this->currentCartItems($request);
        $cartMode = $this->resolveCartMode($items);

        return view('pages.cart', [
            'cartItems' => $items,
            'totals' => $this->buildTotals($items, 3.00, 0.00),
            'onsiteReservation' => $request->session()->get('onsite_reservation'),
            'cartMode' => $cartMode,
            'cartModeLabel' => $this->modeLabel($cartMode),
        ]);
    }

    public function updateCart(Request $request, string $slug): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $items = collect($this->currentCartItems($request))
            ->map(function (array $item) use ($slug, $validated) {
                if (($item['slug'] ?? null) === $slug && ($item['type'] ?? 'destination') !== 'accommodation') {
                    if (($item['type'] ?? 'destination') === 'transportation') {
                        return $item;
                    }

                    $item['quantity'] = (int) $validated['quantity'];
                }

                return $item;
            })
            ->all();

        $this->persistCartItems($request, $items);

        return redirect()->route('cart')->with('status', 'Quantity item berhasil diperbarui.');
    }

    public function removeCartItem(Request $request, string $slug): RedirectResponse
    {
        $items = collect($this->currentCartItems($request))
            ->reject(fn (array $item) => ($item['slug'] ?? null) === $slug)
            ->values()
            ->all();

        $this->persistCartItems($request, $items);

        $selectedAccommodation = $this->selectedAccommodation($request);
        if ($selectedAccommodation && ($selectedAccommodation['slug'] ?? null) === $slug) {
            $request->session()->forget('selected_accommodation');
        }

        $selectedTransportation = $this->selectedTransportation($request);
        if ($selectedTransportation && ($selectedTransportation['slug'] ?? null) === $slug) {
            $request->session()->forget('selected_transportation');
        }

        $selectedCulinary = $this->selectedCulinary($request);
        if ($selectedCulinary && ($selectedCulinary['slug'] ?? null) === $slug) {
            $request->session()->forget('selected_culinary');
        }

        return redirect()->route('cart')->with('status', 'Item berhasil dihapus dari cart.');
    }

    public function checkout(Request $request): View
    {
        $items = $this->currentCartItems($request);

        return view('pages.checkout', [
            'cartItems' => $items,
            'totals' => $this->buildTotals($items, 3.00, 0.00),
            'checkoutForm' => SiteContent::checkoutForm(),
            'countries' => SiteContent::checkoutCountries(),
            'paymentMethods' => SiteContent::paymentMethods(),
            'onsiteReservation' => $request->session()->get('onsite_reservation'),
        ]);
    }

    public function placeOrder(Request $request): RedirectResponse
    {
        $items = $this->currentCartItems($request);

        if ($items === []) {
            return redirect()->route('shop')->with('error', 'Belum ada item yang dipilih untuk dibooking.');
        }

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:120'],
            'address_line_1' => ['nullable', 'string', 'max:180'],
            'address_line_2' => ['nullable', 'string', 'max:180'],
            'city' => ['nullable', 'string', 'max:120'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:150'],
            'payment_method' => ['required', 'string', 'max:50'],
        ]);

        $totals = $this->buildTotals($items, 3.00, 0.00);
        $firstDepartureDate = collect($items)
            ->whereIn('type', ['travel_package', 'tour_package'])
            ->pluck('departure_date')
            ->filter()
            ->first();

        $booking = Booking::query()->create([
            'booking_code' => 'TRV-' . strtoupper(Str::random(8)),
            'customer_first_name' => $validated['first_name'],
            'customer_last_name' => $validated['last_name'],
            'customer_email' => $validated['email'],
            'customer_phone' => $validated['phone'],
            'country' => $validated['country'],
            'city' => $validated['city'] ?? null,
            'address_line_1' => $validated['address_line_1'] ?? null,
            'address_line_2' => $validated['address_line_2'] ?? null,
            'postal_code' => $validated['postal_code'] ?? null,
            'departure_date' => $firstDepartureDate,
            'participants' => collect($items)
                ->whereIn('type', ['destination', 'travel_package', 'tour_package'])
                ->sum('quantity'),
            'payment_method' => $validated['payment_method'],
            'notes' => 'Booking layanan customer dari aplikasi Travelly.',
            'subtotal' => $totals['subtotal'],
            'service_fee' => $totals['delivery'],
            'discount_amount' => $totals['discount'],
            'total_amount' => $totals['total'],
            'booking_status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        foreach ($items as $item) {
            if (in_array(($item['type'] ?? 'destination'), ['travel_package', 'tour_package'], true)) {
                $booking->items()->create([
                    'travel_package_id' => $item['travel_package_id'] ?? null,
                    'package_title' => $item['name'],
                    'unit_price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'line_total' => $item['price'] * $item['quantity'],
                ]);

                continue;
            }

            if (($item['type'] ?? 'destination') === 'accommodation') {
                $booking->accommodation()->create([
                    'accommodation_id' => $item['accommodation_id'] ?? null,
                    'accommodation_name' => $item['name'],
                    'accommodation_type' => $item['accommodation_type'] ?? 'other',
                    'location' => $item['location'] ?? null,
                    'unit_price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'line_total' => $item['price'] * $item['quantity'],
                ]);

                continue;
            }

            if (($item['type'] ?? 'destination') === 'transportation') {
                $booking->transportation()->create([
                    'transport_method' => $item['transport_method'],
                    'transport_label' => $item['name'],
                    'vehicle_detail' => $item['vehicle_detail'] ?? null,
                    'pickup_location' => $item['pickup_location'] ?? null,
                    'pickup_time' => $item['pickup_time'] ?? null,
                    'passenger_count' => (int) ($item['passenger_count'] ?? 1),
                    'notes' => $item['transport_notes'] ?? null,
                    'unit_price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'line_total' => $item['price'] * $item['quantity'],
                ]);

                continue;
            }

            if (($item['type'] ?? 'destination') === 'culinary') {
                $booking->culinary()->create([
                    'culinary_venue_id' => $item['culinary_venue_id'] ?? null,
                    'culinary_package_id' => $item['culinary_package_id'] ?? null,
                    'venue_name' => $item['venue_name'] ?? 'Rumah makan',
                    'package_name' => $item['name'],
                    'reservation_date' => $item['reservation_date'],
                    'arrival_time' => $item['arrival_time'],
                    'notes' => $item['culinary_notes'] ?? null,
                    'unit_price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'line_total' => $item['price'] * $item['quantity'],
                ]);

                continue;
            }

            $booking->destinationItems()->create([
                'destination_slug' => $item['slug'],
                'destination_name' => $item['name'],
                'location' => $item['location'] ?? null,
                'unit_price' => $item['price'],
                'quantity' => $item['quantity'],
                'line_total' => $item['price'] * $item['quantity'],
            ]);
        }

        $request->session()->forget([
            'selected_destination_tickets',
            'selected_accommodation',
            'selected_transportation',
            'selected_culinary',
            'destination_payment_option',
            'onsite_reservation',
        ]);
        $this->clearCartItems($request);

        return redirect()->route('checkout.summary', $booking)->with('status', 'Booking berhasil dibuat dengan kode ' . $booking->booking_code . '.');
    }

    public function checkoutSummary(Booking $booking): View
    {
        $booking->load('items.travelPackage', 'destinationItems', 'accommodation', 'transportation', 'culinary');

        return view('pages.checkout-summary', [
            'booking' => $booking,
        ]);
    }

    public function contact(): RedirectResponse
    {
        return redirect()->to(route('about') . '#contact-section');
    }

    public function productSingle(?string $slug = null): View
    {
        $products = SiteContent::products();
        $product = $this->findBySlug($products, $slug);

        return view('pages.product-single', [
            'product' => $product,
            'relatedProducts' => array_slice($products, 0, 4),
        ]);
    }

    public function shop(Request $request): View
    {
        $packages = TravelPackage::query()
            ->active()
            ->orderByDesc('is_featured')
            ->orderBy('title')
            ->get()
            ->map(function (TravelPackage $package) {
                return [
                    'id' => $package->id,
                    'slug' => $package->slug,
                    'title' => $package->title,
                    'description' => $package->summary ?: Str::limit(strip_tags((string) $package->description), 140),
                    'price' => (float) $package->effective_price,
                    'display_price' => $package->display_price,
                    'duration_days' => (int) $package->duration_days,
                    'location' => $package->location,
                    'destinations' => $this->packageDestinations($package),
                    'image' => $this->packageImagePath($package),
                    'is_featured' => (bool) $package->is_featured,
                ];
            })
            ->values()
            ->all();

        return view('pages.shop', [
            'packages' => $packages,
            'cartItems' => $this->currentCartItems($request),
        ]);
    }

    public function tripBuilder(Request $request): View
    {
        $selectedTransportation = $this->selectedTransportation($request);
        $selectedAccommodation = $this->selectedAccommodation($request);
        $selectedCulinary = $this->selectedCulinary($request);
        $selectedDestinations = $this->selectedTickets($request);
        $summaryRows = $this->tripBuilderSummaryRows($request);
        $destinationTickets = collect(SiteContent::destinationTickets());
        $accommodations = Accommodation::query()->active()->orderBy('type')->orderBy('name')->get()->groupBy('type');
        $culinaryPackages = CulinaryPackage::query()
            ->with([
                'culinaryVenue',
                'galleries',
            ])
            ->bookable()
            ->whereHas('culinaryVenue', fn ($query) => $query->active())
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->groupBy('culinaryVenue.name');

        return view('pages.trip-builder', [
            'transportationOptions' => $this->transportationOptions(),
            'selectedTransportation' => $selectedTransportation,
            'lastTransportationMethod' => old('transport_method', $selectedTransportation['transport_method'] ?? 'shuttle'),
            'selectedAccommodation' => $selectedAccommodation,
            'lastLodgingType' => old('lodging_type', $selectedAccommodation['accommodation_type'] ?? 'hotel'),
            'selectedCulinary' => $selectedCulinary,
            'lastCulinaryPackageId' => old('culinary_package_id', $selectedCulinary['culinary_package_id'] ?? ''),
            'selectedDestinations' => $selectedDestinations,
            'selectedDestinationSlugs' => collect($selectedDestinations)->pluck('slug')->all(),
            'summaryRows' => $summaryRows,
            'accommodations' => $accommodations,
            'culinaryPackages' => $culinaryPackages,
            'destinationTickets' => $destinationTickets->values()->all(),
        ]);
    }

    public function shopDetail(TravelPackage $package): View
    {
        abort_unless($package->is_active, 404);

        return view('pages.shop-detail', [
            'package' => [
                'id' => $package->id,
                'slug' => $package->slug,
                'title' => $package->title,
                'summary' => $package->summary,
                'description' => $package->description,
                'price' => (float) $package->effective_price,
                'display_price' => $package->display_price,
                'duration_days' => (int) $package->duration_days,
                'location' => $package->location,
                'destinations' => $this->packageDestinations($package),
                'image' => $this->packageImagePath($package),
            ],
        ]);
    }

    public function addPackageToCart(Request $request): RedirectResponse
    {
        if ($response = $this->ensurePackageFlowCompatible($request)) {
            return $response;
        }

        $validated = $request->validate([
            'travel_package_id' => ['required', 'integer', 'exists:travel_packages,id'],
            'participants' => ['required', 'integer', 'min:1', 'max:100'],
            'departure_date' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $package = TravelPackage::query()
            ->active()
            ->whereKey($validated['travel_package_id'])
            ->firstOrFail();

        $cartItems = collect($this->currentCartItems($request));
        $newItem = $this->packageToCartItem($package, (int) $validated['participants'], (string) $validated['departure_date']);

        $cartItems = $cartItems
            ->reject(fn (array $item) => ($item['slug'] ?? null) === $newItem['slug'])
            ->push($newItem)
            ->values()
            ->all();

        $this->persistCartItems($request, $cartItems);

        return redirect()->route('cart')->with('status', 'Paket wisata berhasil ditambahkan ke keranjang.');
    }

    public function addTourPackageToCart(Request $request): RedirectResponse
    {
        if ($response = $this->ensurePackageFlowCompatible($request)) {
            return $response;
        }

        $validated = $request->validate([
            'tour_package' => ['required', 'in:tour_package'],
            'package_id' => ['required', 'integer', 'exists:travel_packages,id'],
            'qty' => ['required', 'integer', 'min:1', 'max:100'],
            'departure_date' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $package = TravelPackage::query()
            ->active()
            ->whereKey($validated['package_id'])
            ->firstOrFail();

        $cartItems = collect($this->currentCartItems($request));
        $newItem = $this->packageToCartItem($package, (int) $validated['qty'], (string) $validated['departure_date']);

        $cartItems = $cartItems
            ->reject(fn (array $item) => ($item['slug'] ?? null) === $newItem['slug'])
            ->push($newItem)
            ->values()
            ->all();

        $this->persistCartItems($request, $cartItems);

        return redirect()->route('cart')->with('status', 'Paket wisata berhasil ditambahkan ke keranjang.');
    }

    public function destinations(Request $request): View
    {
        $selectedTickets = $this->selectedTickets($request);
        $destinationTickets = collect(SiteContent::destinationTickets());
        $search = trim((string) $request->string('search'));
        $location = trim((string) $request->string('location'));
        $priceFilter = trim((string) $request->string('price'));

        $filteredTickets = $destinationTickets
            ->when($search !== '', function ($collection) use ($search) {
                return $collection->filter(function (array $ticket) use ($search) {
                    $haystack = strtolower(($ticket['name'] ?? '') . ' ' . ($ticket['location'] ?? '') . ' ' . ($ticket['description'] ?? ''));

                    return str_contains($haystack, strtolower($search));
                });
            })
            ->when($location !== '', fn ($collection) => $collection->where('location', $location))
            ->when($priceFilter !== '', function ($collection) use ($priceFilter) {
                return $collection->filter(function (array $ticket) use ($priceFilter) {
                    $price = (float) ($ticket['price'] ?? 0);

                    return match ($priceFilter) {
                        'under-10' => $price < 10,
                        '10-15' => $price >= 10 && $price <= 15,
                        'above-15' => $price > 15,
                        default => true,
                    };
                });
            })
            ->values()
            ->all();

        return view('pages.destinations', [
            'destinationTickets' => $filteredTickets,
            'selectedTickets' => $selectedTickets,
            'selectedAccommodation' => $this->selectedAccommodation($request),
            'selectedTransportation' => $this->selectedTransportation($request),
            'selectedCulinary' => $this->selectedCulinary($request),
            'ticketTotals' => $this->buildTotals($selectedTickets),
            'summaryTotals' => $this->buildTotals($this->selectionSummaryItems($request)),
            'destinationLocations' => $destinationTickets->pluck('location')->filter()->unique()->values()->all(),
        ]);
    }

    public function saveDestinations(Request $request): RedirectResponse
    {
        if ($response = $this->ensureServiceFlowCompatible($request)) {
            return $response;
        }

        $validated = $request->validate([
            'destinations' => ['nullable', 'array'],
            'destinations.*' => ['string'],
            'quantities' => ['nullable', 'array'],
        ]);

        $selectedSlugs = collect($validated['destinations'] ?? [])
            ->filter(fn ($slug) => is_string($slug) && $slug !== '')
            ->unique()
            ->values()
            ->all();

        $selectedTickets = collect(SiteContent::destinationTickets())
            ->whereIn('slug', $selectedSlugs)
            ->map(function (array $ticket) use ($request) {
                $quantity = max(1, (int) $request->input('quantities.' . $ticket['slug'], 1));

                return [
                    'slug' => $ticket['slug'],
                    'type' => 'destination',
                    'source' => 'single',
                    'name' => $ticket['name'],
                    'image' => $ticket['image'],
                    'description' => $ticket['description'],
                    'price' => $ticket['price'],
                    'quantity' => $quantity,
                    'location' => $ticket['location'],
                ];
            })
            ->values()
            ->all();

        $request->session()->put('selected_destination_tickets', $selectedTickets);
        $this->syncSelectionCheckoutState($request, 'app');
        $request->session()->save();
        $nextRoute = $this->resolveNextRoute($request, ['accommodations', 'transportations', 'culinaries', 'trip-builder', 'cart'], 'cart');

        if ($this->selectionSummaryItems($request) === []) {
            return redirect()->route($nextRoute)->with('error', 'Pilih minimal satu layanan agar bisa masuk ke keranjang.');
        }

        return redirect()->route($nextRoute)->with('status', $selectedTickets === [] ? 'Pilihan destinasi dikosongkan. Keranjang diperbarui sesuai layanan aktif.' : 'Destinasi berhasil disimpan. Lanjutkan ke langkah berikutnya.');
    }

    public function accommodations(Request $request): View
    {
        return view('pages.accommodations', [
            'accommodations' => Accommodation::query()->active()->orderBy('type')->orderBy('name')->get()->groupBy('type'),
            'selectedAccommodation' => $this->selectedAccommodation($request),
            'selectedTickets' => $this->selectedTickets($request),
            'selectedTransportation' => $this->selectedTransportation($request),
            'selectedCulinary' => $this->selectedCulinary($request),
            'summaryTotals' => $this->buildTotals($this->selectionSummaryItems($request)),
            'lastLodgingType' => old('lodging_type', $this->selectedAccommodation($request)['accommodation_type'] ?? 'hotel'),
        ]);
    }

    public function saveAccommodation(Request $request): RedirectResponse
    {
        if ($response = $this->ensureServiceFlowCompatible($request)) {
            return $response;
        }

        $validated = $request->validate([
            'lodging_type' => ['required', 'in:hotel,villa,homestay,none'],
            'accommodation_id' => ['nullable', 'integer', 'exists:accommodations,id'],
        ]);

        $selectedAccommodation = null;
        if ($validated['lodging_type'] !== 'none') {
            if (empty($validated['accommodation_id'])) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['accommodation_id' => 'Silakan pilih penginapan dari daftar yang tersedia.']);
            }

            $accommodation = Accommodation::query()
                ->active()
                ->whereKey($validated['accommodation_id'])
                ->where('type', $validated['lodging_type'])
                ->firstOrFail();

            $selectedAccommodation = [
                'slug' => 'accommodation-' . $accommodation->slug,
                'type' => 'accommodation',
                'source' => 'single',
                'accommodation_id' => $accommodation->id,
                'accommodation_type' => $accommodation->type,
                'name' => $accommodation->name,
                'image' => $accommodation->image ?: 'product-1.jpg',
                'description' => $accommodation->description ?: 'Penginapan pilihan untuk perjalanan kamu.',
                'price' => (float) $accommodation->price_per_night,
                'quantity' => 1,
                'location' => $accommodation->location,
            ];
        }

        $request->session()->put('selected_accommodation', $selectedAccommodation);
        $this->syncSelectionCheckoutState($request, 'app');
        $nextRoute = $this->resolveNextRoute($request, ['transportations', 'culinaries', 'trip-builder', 'cart'], 'cart');

        if ($this->selectionSummaryItems($request) === []) {
            return redirect()->route($nextRoute)->with('error', 'Pilih minimal satu layanan agar bisa masuk ke keranjang.');
        }

        return redirect()->route($nextRoute)->with('status', $selectedAccommodation ? 'Penginapan berhasil disimpan. Lanjutkan ke layanan berikutnya.' : 'Pilihan penginapan dikosongkan. Keranjang diperbarui sesuai layanan aktif.');
    }

    public function transportations(Request $request): View
    {
        return view('pages.transportations', [
            'transportationOptions' => $this->transportationOptions(),
            'selectedTickets' => $this->selectedTickets($request),
            'selectedAccommodation' => $this->selectedAccommodation($request),
            'selectedTransportation' => $this->selectedTransportation($request),
            'selectedCulinary' => $this->selectedCulinary($request),
            'summaryTotals' => $this->buildTotals($this->selectionSummaryItems($request)),
            'lastTransportationMethod' => old('transport_method', $this->selectedTransportation($request)['transport_method'] ?? 'shuttle'),
        ]);
    }

    public function saveTransportation(Request $request): RedirectResponse
    {
        if ($response = $this->ensureServiceFlowCompatible($request)) {
            return $response;
        }

        $validated = $request->validate([
            'transport_method' => ['required', 'in:none,rental_car,driver_car,airport_transfer,shuttle'],
            'vehicle_detail' => ['nullable', 'string', 'max:150'],
            'pickup_location' => ['nullable', 'string', 'max:180'],
            'pickup_time' => ['nullable', 'date'],
            'passenger_count' => ['nullable', 'integer', 'min:1', 'max:100'],
            'transport_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $selectedTransportation = null;
        $transportationOptions = $this->transportationOptions();
        $transportMethod = $validated['transport_method'];
        if ($transportMethod !== 'none') {
            if ($transportMethod === 'rental_car' && blank($validated['vehicle_detail'] ?? null)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['vehicle_detail' => 'Detail kendaraan wajib diisi jika memilih sewa kendaraan.']);
            }

            if (blank($validated['pickup_location'] ?? null)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['pickup_location' => 'Lokasi jemput wajib diisi jika memilih transportasi.']);
            }

            if (blank($validated['pickup_time'] ?? null)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['pickup_time' => 'Waktu jemput wajib diisi jika memilih transportasi.']);
            }

            if (blank($validated['passenger_count'] ?? null) || (int)($validated['passenger_count'] ?? 0) < 1) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['passenger_count' => 'Jumlah penumpang minimal 1 orang.']);
            }

            $option = $transportationOptions[$transportMethod];
            $selectedTransportation = [
                'slug' => 'transportation-' . $transportMethod,
                'type' => 'transportation',
                'source' => 'single',
                'transport_method' => $transportMethod,
                'name' => $option['label'],
                'image' => 'product-7.jpg',
                'description' => $option['description'] . ' Jemput di ' . $validated['pickup_location'] . '.',
                'price' => (float) $option['price'],
                'quantity' => 1,
                'vehicle_detail' => $validated['vehicle_detail'] ?? null,
                'pickup_location' => $validated['pickup_location'],
                'pickup_time' => $validated['pickup_time'],
                'passenger_count' => (int) ($validated['passenger_count'] ?? 1),
                'transport_notes' => $validated['transport_notes'] ?? null,
            ];
        }

        $request->session()->put('selected_transportation', $selectedTransportation);
        $this->syncSelectionCheckoutState($request, 'app');
            $request->session()->save();
        $nextRoute = $this->resolveNextRoute($request, ['culinaries', 'cart', 'accommodations', 'trip-builder'], 'cart');

        // DEBUG
        \Log::info('saveTransportation - After save:', [
            'selectedTransportation' => $selectedTransportation,
            'nextRoute' => $nextRoute,
            'redirect_to' => $request->input('redirect_to'),
        ]);

        if ($this->selectionSummaryItems($request) === []) {
            return redirect()->route($nextRoute)->with('error', 'Pilih minimal satu layanan agar bisa masuk ke keranjang.');
        }

        return redirect()->route($nextRoute)->with('status', $selectedTransportation ? 'Transportasi berhasil disimpan. Lanjutkan ke layanan berikutnya.' : 'Pilihan transportasi dikosongkan. Keranjang diperbarui sesuai layanan aktif.');
    }

    public function culinaries(Request $request): View
    {
        $culinaryPackages = CulinaryPackage::query()
            ->with([
                'culinaryVenue',
                'galleries',
            ])
            ->bookable()
            ->whereHas('culinaryVenue', fn ($query) => $query->active())
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->groupBy('culinaryVenue.name');

        return view('pages.culinaries', [
            'culinaryPackages' => $culinaryPackages,
            'selectedTickets' => $this->selectedTickets($request),
            'selectedAccommodation' => $this->selectedAccommodation($request),
            'selectedTransportation' => $this->selectedTransportation($request),
            'selectedCulinary' => $this->selectedCulinary($request),
            'summaryTotals' => $this->buildTotals($this->selectionSummaryItems($request)),
        ]);
    }

    public function saveCulinary(Request $request): RedirectResponse
    {
        if ($response = $this->ensureServiceFlowCompatible($request)) {
            return $response;
        }

        $validated = $request->validate([
            'culinary_package_id' => ['nullable', 'integer', 'exists:culinary_packages,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:100'],
            'reservation_date' => ['nullable', 'date', 'after_or_equal:today'],
            'arrival_time' => ['nullable', 'date_format:H:i'],
            'culinary_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $selectedCulinary = null;

        if (! empty($validated['culinary_package_id'])) {
            $package = CulinaryPackage::query()
                ->with('culinaryVenue')
                ->bookable()
                ->whereKey($validated['culinary_package_id'])
                ->whereHas('culinaryVenue', fn ($query) => $query->active())
                ->firstOrFail();

            if (blank($validated['reservation_date'] ?? null)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['reservation_date' => 'Tanggal reservasi kuliner wajib diisi.']);
            }

            if (blank($validated['arrival_time'] ?? null)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['arrival_time' => 'Jam kedatangan wajib diisi.']);
            }

            $quantity = (int) ($validated['quantity'] ?? 1);

            $selectedCulinary = [
                'slug' => 'culinary-' . $package->slug,
                'type' => 'culinary',
                'source' => 'single',
                'culinary_package_id' => $package->id,
                'culinary_venue_id' => $package->culinaryVenue?->id,
                'venue_name' => $package->culinaryVenue?->name,
                'name' => $package->name,
                'image' => $package->image_url,
                'description' => $package->description ?: 'Reservasi kuliner pilihan customer.',
                'price' => (float) $package->effective_price,
                'quantity' => $quantity,
                'reservation_date' => $validated['reservation_date'],
                'arrival_time' => $validated['arrival_time'],
                'culinary_notes' => $validated['culinary_notes'] ?? null,
            ];
        }

        $request->session()->put('selected_culinary', $selectedCulinary);
        $this->syncSelectionCheckoutState($request, 'app');
        $request->session()->save();
        $nextRoute = $this->resolveNextRoute($request, ['cart', 'shop', 'transportations', 'trip-builder'], 'cart');

        if ($this->selectionSummaryItems($request) === []) {
            return redirect()->route($nextRoute)->with('error', 'Pilih minimal satu layanan agar bisa masuk ke keranjang.');
        }

        return redirect()->route($nextRoute)->with('status', $selectedCulinary ? 'Reservasi kuliner berhasil disimpan. Lanjutkan ke checkout atau layanan lain.' : 'Pilihan kuliner dikosongkan. Keranjang diperbarui sesuai layanan aktif.');
    }

    public function finalizeSelections(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'payment_option' => ['required', 'in:app,onsite'],
        ]);

        if ($this->selectionSummaryItems($request) === []) {
            return redirect()->route('shop')->with('error', 'Pilih minimal satu layanan: tiket wisata, penginapan, transportasi, atau kuliner.');
        }

        $this->syncSelectionCheckoutState($request, $validated['payment_option']);

        if ($validated['payment_option'] === 'app') {
            return redirect()->route('cart')->with('status', 'Pilihan booking berhasil dimasukkan ke keranjang.');
        }

        return redirect()->route('shop')->with('status', 'Ringkasan booking berhasil disimpan dengan opsi bayar di tempat.');
    }

    public function wishlist(): View
    {
        $items = SiteContent::wishlistItems();

        return view('pages.wishlist', [
            'wishlistItems' => $items,
            'totals' => $this->buildTotals($items),
        ]);
    }
}
