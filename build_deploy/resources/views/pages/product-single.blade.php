@extends('layouts.app')

@section('title', 'Exflore KBB - Detail Paket')

@section('content')
<div class="hero-wrap hero-bread" style="background-image: url('{{ asset('assets/images/bg_1.jpg') }}');">
  <div class="container">
    <div class="row no-gutters slider-text align-items-center justify-content-center">
      <div class="col-md-9 ftco-animate text-center">
        <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('home') }}">Home</a></span> <span class="mr-2"><a href="{{ route('shop') }}">Paket</a></span> <span>{{ $product['name'] }}</span></p>
        <h1 class="mb-0 bread">{{ $product['name'] }}</h1>
      </div>
    </div>
  </div>
</div>

<section class="ftco-section">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 mb-5 ftco-animate">
        <a href="{{ asset('assets/images/' . $product['image']) }}" class="image-popup"><img src="{{ asset('assets/images/' . $product['image']) }}" class="img-fluid" alt="{{ $product['name'] }}"></a>
      </div>
      <div class="col-lg-6 product-details pl-md-5 ftco-animate">
        <h3>{{ $product['name'] }}</h3>
        <p class="price">
          @if (!empty($product['sale_price']))
            <span class="mr-2 price-dc">{{ $product['price'] }}</span><span>{{ $product['sale_price'] }}</span>
          @else
            <span>{{ $product['price'] }}</span>
          @endif
        </p>
        <p>{{ $product['excerpt'] }}</p>
        <div class="row mt-4">
          <div class="col-md-6">
            <div class="form-group d-flex">
              <div class="select-wrap">
                <div class="icon"><span class="ion-ios-arrow-down"></span></div>
                <select class="form-control">
                  <option>Regular</option>
                  <option>Comfort</option>
                  <option>Premium</option>
                  <option>Private</option>
                </select>
              </div>
            </div>
          </div>
          <div class="w-100"></div>
          <div class="input-group col-md-6 d-flex mb-3">
            <span class="input-group-btn mr-2"><button type="button" class="quantity-left-minus btn"><i class="ion-ios-remove"></i></button></span>
            <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1" min="1" max="100">
            <span class="input-group-btn ml-2"><button type="button" class="quantity-right-plus btn"><i class="ion-ios-add"></i></button></span>
          </div>
          <div class="w-100"></div>
          <div class="col-md-12"><p style="color: #000;">{{ $product['stock_text'] }}</p></div>
        </div>
        <p><a href="{{ route('cart') }}" class="btn btn-black py-3 px-5">Booking Sekarang</a></p>
      </div>
    </div>
  </div>
</section>

<section class="ftco-section">
  <div class="container">
    <div class="row justify-content-center mb-3 pb-3">
      <div class="col-md-12 heading-section text-center ftco-animate">
        <span class="subheading">Paket Terkait</span>
        <h2 class="mb-4">Rekomendasi Lainnya</h2>
        <p>Pilihan paket wisata lain yang sering dipilih bersama destinasi ini.</p>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row">
      @foreach ($relatedProducts as $product)
        @include('partials.product-card', ['product' => $product])
      @endforeach
    </div>
  </div>
</section>
@endsection
