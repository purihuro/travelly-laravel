@extends('layouts.app')

@section('title', 'Exflore KBB - Wishlist')

@section('content')
<div class="hero-wrap hero-bread" style="background-image: url('{{ asset('assets/images/bg_1.jpg') }}');">
  <div class="container">
    <div class="row no-gutters slider-text align-items-center justify-content-center">
      <div class="col-md-9 ftco-animate text-center">
        <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('home') }}">Home</a></span> <span>Wishlist</span></p>
        <h1 class="mb-0 bread">Wishlist Paket</h1>
      </div>
    </div>
  </div>
</div>

<section class="ftco-section ftco-cart">
  <div class="container">
    <div class="row">
      <div class="col-md-12 ftco-animate">
        <div class="cart-list">
          <table class="table">
            <thead class="thead-primary">
              <tr class="text-center">
                <th>&nbsp;</th>
                <th>Daftar Paket</th>
                <th>&nbsp;</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($wishlistItems as $item)
                <tr class="text-center">
                  <td class="product-remove"><a href="{{ route('shop') }}"><span class="ion-ios-close"></span></a></td>
                  <td class="image-prod"><div class="img" style="background-image:url('{{ asset('assets/images/' . $item['image']) }}');"></div></td>
                  <td class="product-name">
                    <h3><a href="{{ route('product.single', ['slug' => $item['slug']]) }}">{{ $item['name'] }}</a></h3>
                    <p>{{ $item['description'] }}</p>
                  </td>
                  <td class="price">${{ number_format($item['price'], 2) }}</td>
                  <td class="quantity">
                    <div class="input-group mb-3">
                      <input type="text" name="quantity" class="quantity form-control input-number" value="{{ $item['quantity'] }}" min="1" max="100">
                    </div>
                  </td>
                  <td class="total">${{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="row justify-content-end">
      <div class="col-lg-4 mt-5 cart-wrap ftco-animate">
        <div class="cart-total mb-3">
          <h3>Ringkasan Wishlist</h3>
          <p class="d-flex"><span>Jumlah item</span><span>{{ count($wishlistItems) }}</span></p>
          <p class="d-flex"><span>Estimasi nilai</span><span>${{ number_format($totals['total'], 2) }}</span></p>
          <hr>
          <p class="d-flex total-price"><span>Action</span><span>Ready</span></p>
        </div>
        <p><a href="{{ route('cart') }}" class="btn btn-primary py-3 px-4">Pindah ke Cart</a></p>
      </div>
    </div>
  </div>
</section>
@endsection
