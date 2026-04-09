@extends('layouts.app')

@section('title', 'Exflore KBB - Checkout')

@section('content')
<div class="hero-wrap hero-bread" style="background-image: url('{{ asset('assets/images/bg_1.jpg') }}');">
  <div class="container">
    <div class="row no-gutters slider-text align-items-center justify-content-center">
      <div class="col-md-9 ftco-animate text-center">
        <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('home') }}">Home</a></span> <span>Checkout</span></p>
        <h1 class="mb-0 bread">Checkout</h1>
      </div>
    </div>
  </div>
</div>

<section class="ftco-section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-xl-7 ftco-animate">
        <form action="{{ route('checkout.place') }}" method="POST" class="billing-form">
          @csrf
          <h3 class="mb-4 billing-heading">Data Pemesan</h3>
          <div class="row align-items-end">
            <div class="col-md-6"><div class="form-group"><label>Nama Depan</label><input type="text" name="first_name" class="form-control" value="{{ old('first_name', $checkoutForm['first_name']) }}" required></div></div>
            <div class="col-md-6"><div class="form-group"><label>Nama Belakang</label><input type="text" name="last_name" class="form-control" value="{{ old('last_name', $checkoutForm['last_name']) }}" required></div></div>
            <div class="w-100"></div>
            <div class="col-md-12"><div class="form-group"><label>Negara</label><div class="select-wrap"><div class="icon"><span class="ion-ios-arrow-down"></span></div><select name="country" class="form-control">@foreach ($countries as $country)<option value="{{ $country }}" {{ $country === old('country', $checkoutForm['country']) ? 'selected' : '' }}>{{ $country }}</option>@endforeach</select></div></div></div>
            <div class="w-100"></div>
            <div class="col-md-6"><div class="form-group"><label>Alamat</label><input type="text" name="address_line_1" class="form-control" value="{{ old('address_line_1', $checkoutForm['address_line_1']) }}" required></div></div>
            <div class="col-md-6"><div class="form-group"><label>Alamat Tambahan (Opsional)</label><input type="text" name="address_line_2" class="form-control" value="{{ old('address_line_2', $checkoutForm['address_line_2']) }}"></div></div>
            <div class="w-100"></div>
            <div class="col-md-6"><div class="form-group"><label>Kota</label><input type="text" name="city" class="form-control" value="{{ old('city', $checkoutForm['city']) }}" required></div></div>
            <div class="col-md-6"><div class="form-group"><label>Kode Pos</label><input type="text" name="postal_code" class="form-control" value="{{ old('postal_code', $checkoutForm['postal_code']) }}"></div></div>
            <div class="w-100"></div>
            <div class="col-md-6"><div class="form-group"><label>No. Telepon</label><input type="text" name="phone" class="form-control" value="{{ old('phone', $checkoutForm['phone']) }}" required></div></div>
            <div class="col-md-6"><div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $checkoutForm['email']) }}" required></div></div>
            <div class="col-md-12 mt-4">
              <div class="cart-detail p-3 p-md-4">
                <h3 class="billing-heading mb-4">Metode Pembayaran</h3>
                @foreach ($paymentMethods as $method)
                  <div class="form-group"><div class="col-md-12"><div class="radio"><label><input type="radio" name="payment_method" value="{{ $method }}" class="mr-2" {{ old('payment_method', $paymentMethods[0]) === $method ? 'checked' : '' }}> {{ $method }}</label></div></div></div>
                @endforeach
                @if ($onsiteReservation)
                  <div class="alert alert-info" style="background:#edf8f1;border:1px solid #cfe8d7;padding:12px 14px;border-radius:10px;">{{ $onsiteReservation['message'] }}</div>
                @endif
                <div class="form-group"><div class="col-md-12"><div class="checkbox"><label><input type="checkbox" value="1" class="mr-2" required> Saya telah membaca dan menyetujui syarat serta ketentuan</label></div></div></div>
                <p><button type="submit" class="btn btn-primary py-3 px-4">Konfirmasi Pemesanan</button></p>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="col-xl-5">
        <div class="row mt-5 pt-3">
          <div class="col-md-12 d-flex mb-5">
            <div class="cart-detail cart-total p-3 p-md-4 w-100">
              <h3 class="billing-heading mb-4">Ringkasan Pesanan</h3>
              @foreach ($cartItems as $item)
                <p class="d-flex"><span>{{ $item['name'] }} x {{ $item['quantity'] }} @if (($item['type'] ?? 'destination') === 'accommodation')<small>({{ ucfirst($item['accommodation_type']) }})</small>@elseif (($item['type'] ?? 'destination') === 'transportation')<small>({{ $item['passenger_count'] ?? 1 }} penumpang)</small>@elseif (($item['type'] ?? 'destination') === 'culinary')<small>({{ $item['venue_name'] ?? 'Kuliner' }} · {{ $item['reservation_date'] ?? '-' }} {{ $item['arrival_time'] ?? '' }})</small>@endif</span><span>${{ number_format($item['price'] * $item['quantity'], 2) }}</span></p>
              @endforeach
              <hr>
              <p class="d-flex"><span>Subtotal</span><span>${{ number_format($totals['subtotal'], 2) }}</span></p>
              <p class="d-flex"><span>Biaya layanan</span><span>${{ number_format($totals['delivery'], 2) }}</span></p>
              <p class="d-flex"><span>Diskon</span><span>${{ number_format($totals['discount'], 2) }}</span></p>
              <hr>
              <p class="d-flex total-price"><span>Total</span><span>${{ number_format($totals['total'], 2) }}</span></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
