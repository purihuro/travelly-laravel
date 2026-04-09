@extends('layouts.app')

@section('title', 'Exflore KBB - Keranjang Booking')

@section('content')
<div class="hero-wrap hero-bread" style="background-image: url('{{ asset('assets/images/bg_1.jpg') }}');">
  <div class="container">
    <div class="row no-gutters slider-text align-items-center justify-content-center">
      <div class="col-md-9 ftco-animate text-center">
        <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('home') }}">Home</a></span> <span>Cart</span></p>
        <h1 class="mb-0 bread">Keranjang Booking</h1>
      </div>
    </div>
  </div>
</div>

<section class="ftco-section ftco-cart">
  <div class="container">
    <div class="row mb-4">
      <div class="col-md-12">
        <div class="cart-detail p-3" style="border:1px solid #d9e4f2;background:#f5f9ff;">
          <strong>Mode Keranjang:</strong> {{ $cartModeLabel ?? 'Single Service' }}
          @if (($cartMode ?? 'single') === 'preset')
            <p class="mb-0 mt-1">Keranjang berisi paket siap pilih tanpa tambahan layanan lain.</p>
          @elseif (($cartMode ?? 'single') === 'custom')
            <p class="mb-0 mt-1">Keranjang berisi kombinasi beberapa layanan. Semua item akan checkout bersama.</p>
          @else
            <p class="mb-0 mt-1">Keranjang berisi satu jenis layanan saja.</p>
          @endif
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 ftco-animate">
        <div class="cart-list">
          <table class="table">
            <thead class="thead-primary">
              <tr class="text-center">
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>Nama Item</th>
                <th>Tipe</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($cartItems as $item)
                @php
                  if (str_starts_with($item['image'], 'http://') || str_starts_with($item['image'], 'https://')) {
                      $itemImage = $item['image'];
                  } elseif (str_contains($item['image'], 'assets/')) {
                      $itemImage = asset($item['image']);
                  } elseif (str_contains($item['image'], '/')) {
                      $itemImage = asset('storage/' . ltrim($item['image'], '/'));
                  } else {
                      $itemImage = asset('assets/images/' . $item['image']);
                  }
                @endphp
                <tr class="text-center">
                  <td class="product-remove"><form method="POST" action="{{ route('cart.remove', ['slug' => $item['slug']]) }}">@csrf @method('DELETE')<button type="submit" style="border:0;background:none;"><span class="ion-ios-close"></span></button></form></td>
                  <td class="image-prod"><div class="img" style="background-image:url('{{ $itemImage }}');"></div></td>
                  <td class="product-name"><h3>{{ $item['name'] }}</h3><p>{{ $item['description'] }}</p>@if (($item['type'] ?? 'destination') === 'transportation')<small><strong>Penumpang:</strong> {{ $item['passenger_count'] ?? 1 }} orang</small>@elseif (($item['type'] ?? 'destination') === 'culinary')<small><strong>Rumah makan:</strong> {{ $item['venue_name'] ?? '-' }} · <strong>Tanggal:</strong> {{ $item['reservation_date'] ?? '-' }} {{ $item['arrival_time'] ?? '' }}</small>@elseif (in_array(($item['type'] ?? 'destination'), ['travel_package', 'tour_package'], true))<small><strong>Keberangkatan:</strong> {{ $item['departure_date'] ?? '-' }} · <strong>Durasi:</strong> {{ $item['duration_days'] ?? 1 }} hari</small>@endif</td>
                  <td>@if (($item['type'] ?? 'destination') === 'accommodation'){{ ucfirst($item['accommodation_type']) }}@elseif (($item['type'] ?? 'destination') === 'transportation')Transportasi@elseif (($item['type'] ?? 'destination') === 'culinary')Kuliner@elseif (in_array(($item['type'] ?? 'destination'), ['travel_package', 'tour_package'], true))Paket wisata@else Tiket destinasi @endif</td>
                  <td class="price">${{ number_format($item['price'], 2) }}</td>
                  <td class="quantity">
                    @if (($item['type'] ?? 'destination') === 'accommodation')
                      <span>1 malam</span>
                    @elseif (($item['type'] ?? 'destination') === 'transportation')
                      <span>1 layanan</span>
                    @elseif (($item['type'] ?? 'destination') === 'culinary')
                      <span>{{ $item['quantity'] }} orang</span>
                    @elseif (in_array(($item['type'] ?? 'destination'), ['travel_package', 'tour_package'], true))
                      <form method="POST" action="{{ route('cart.update', ['slug' => $item['slug']]) }}">@csrf @method('PATCH')<div class="input-group mb-3"><input type="number" name="quantity" class="quantity form-control input-number" value="{{ $item['quantity'] }}" min="1" max="100"></div><button type="submit" class="btn btn-secondary btn-sm">Update</button></form>
                    @else
                      <form method="POST" action="{{ route('cart.update', ['slug' => $item['slug']]) }}">@csrf @method('PATCH')<div class="input-group mb-3"><input type="number" name="quantity" class="quantity form-control input-number" value="{{ $item['quantity'] }}" min="1" max="100"></div><button type="submit" class="btn btn-secondary btn-sm">Update</button></form>
                    @endif
                  </td>
                  <td class="total">${{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center">
                    <p class="mb-2">Keranjang masih kosong.</p>
                    <a href="{{ route('shop') }}" class="btn btn-sm btn-outline-primary mr-2">Lihat Paket Wisata</a>
                    <a href="{{ route('trip-builder') }}" class="btn btn-sm btn-primary">Buat Trip Sendiri</a>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="row justify-content-end"><div class="col-lg-4 mt-5 cart-wrap ftco-animate"><div class="cart-total mb-3"><h3>Total Booking</h3><p class="d-flex"><span>Subtotal</span><span>${{ number_format($totals['subtotal'], 2) }}</span></p><p class="d-flex"><span>Biaya layanan</span><span>${{ number_format($totals['delivery'], 2) }}</span></p><p class="d-flex"><span>Diskon</span><span>${{ number_format($totals['discount'], 2) }}</span></p><hr><p class="d-flex total-price"><span>Total</span><span>${{ number_format($totals['total'], 2) }}</span></p></div><p><a href="{{ route('checkout') }}" class="btn btn-primary py-3 px-4">Lanjutkan ke Pembayaran</a></p></div></div>
    @if ($onsiteReservation)<div class="row justify-content-center mt-5"><div class="col-md-8"><div class="cart-detail p-4"><h3 class="billing-heading mb-3">Catatan Bayar di Tempat</h3><p>{{ $onsiteReservation['message'] }}</p></div></div></div>@endif
  </div>
</section>
@endsection
