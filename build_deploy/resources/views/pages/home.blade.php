@extends('layouts.app')

@section('title', 'Exflore KBB - Website Pemesanan Wisata')

@push('styles')
<style>
  .service-card-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.92);
    color: #1f4b3f;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    margin-bottom: 8px;
  }

  .service-card-icon svg {
    width: 24px;
    height: 24px;
    stroke: currentColor;
    fill: none;
    stroke-width: 2;
    stroke-linecap: round;
    stroke-linejoin: round;
  }

  .service-card-title {
    display: block;
    line-height: 1.2;
  }
</style>
@endpush

@section('content')
<section id="home-section" class="hero">
  <div class="home-slider owl-carousel">
    @foreach ($homepage['hero_slides'] as $slide)
      @php
        $slideImage = str_contains($slide['image'], 'assets/') ? asset($slide['image']) : asset('storage/' . $slide['image']);
      @endphp
      <div class="slider-item" style="background-image: url('{{ $slideImage }}');">
        <div class="overlay"></div>
        <div class="container">
          <div class="row slider-text justify-content-center align-items-center" data-scrollax-parent="true">
            <div class="col-md-12 ftco-animate text-center">
              <h1 class="mb-2">{{ $slide['title'] }}</h1>
              <h2 class="subheading mb-4">{{ $slide['subtitle'] }}</h2>
              <p><a href="{{ route($slide['button_route']) }}" class="btn btn-primary">{{ $slide['button'] }}</a></p>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</section>

<section class="ftco-section">
  <div class="container">
    <div class="row no-gutters ftco-services">
      @foreach ($homepage['service_features'] as $feature)
        <div class="col-md-3 text-center d-flex align-self-stretch ftco-animate">
          <div class="media block-6 services mb-md-0 mb-4">
            <div class="icon {{ $feature['bg_class'] }} {{ $feature['is_active'] ? 'active' : '' }} d-flex justify-content-center align-items-center mb-2">
              <span class="{{ $feature['icon'] }}"></span>
            </div>
            <div class="media-body">
              <h3 class="heading">{{ $feature['title'] }}</h3>
              <span>{{ $feature['text'] }}</span>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<section class="ftco-section ftco-category ftco-no-pt">
  <div class="container">
    <div class="row">
      <div class="col-md-8">
        <div class="row">
          <div class="col-md-6 order-md-last align-items-stretch d-flex">
            @php $mainCategoryImage = str_contains($homepage['category_showcase']['main_image'], 'assets/') ? asset($homepage['category_showcase']['main_image']) : asset('storage/' . $homepage['category_showcase']['main_image']); @endphp
            <div class="category-wrap-2 ftco-animate img align-self-stretch d-flex" style="background-image: url('{{ $mainCategoryImage }}');">
              <div class="text text-center">
                <h2>{{ $homepage['category_showcase']['main_title'] }}</h2>
                <p>{{ $homepage['category_showcase']['main_text'] }}</p>
                <p><a href="{{ route($homepage['category_showcase']['button_route']) }}" class="btn btn-primary">{{ $homepage['category_showcase']['button_label'] }}</a></p>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            @php
              $serviceIcons = [
                  'destinations' => 'ticket',
                  'accommodations' => 'bed',
                  'transportations' => 'bus',
                  'culinaries' => 'utensils',
              ];
            @endphp
            @foreach (array_slice($homepage['category_showcase']['cards'], 0, 2) as $card)
              @php $cardImage = str_contains($card['image'], 'assets/') ? asset($card['image']) : asset('storage/' . $card['image']); @endphp
              <div class="category-wrap ftco-animate img {{ $loop->first ? 'mb-4' : '' }} d-flex align-items-end" style="background-image: url('{{ $cardImage }}');">
                <div class="text px-3 py-1">
                  <h2 class="mb-0">
                    <a href="{{ route($card['route'] ?? 'shop') }}">
                      @php
                        $iconType = $card['icon'] ?? ($serviceIcons[$card['route'] ?? ''] ?? 'spark');
                      @endphp
                      <span class="service-card-icon" aria-hidden="true">
                        @switch($iconType)
                          @case('ticket')
                            <svg viewBox="0 0 24 24"><path d="M3 9V7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v2a2 2 0 0 0 0 4v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-2a2 2 0 0 0 0-4z"/><path d="M9 9h.01M9 15h.01"/></svg>
                            @break
                          @case('bed')
                            <svg viewBox="0 0 24 24"><path d="M3 11v8"/><path d="M21 11v8"/><path d="M3 16h18"/><path d="M3 11h18"/><path d="M7 11V9a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v2"/></svg>
                            @break
                          @case('bus')
                            <svg viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="12" rx="2"/><path d="M4 10h16"/><path d="M7 16v3"/><path d="M17 16v3"/><circle cx="8" cy="13" r="1"/><circle cx="16" cy="13" r="1"/></svg>
                            @break
                          @case('utensils')
                            <svg viewBox="0 0 24 24"><path d="M4 3v8"/><path d="M7 3v8"/><path d="M4 7h3"/><path d="M6 11v10"/><path d="M14 3v6a2 2 0 0 0 4 0V3"/><path d="M16 11v10"/></svg>
                            @break
                          @default
                            <svg viewBox="0 0 24 24"><path d="M12 3l1.7 3.4L17 8l-2.4 2.3.6 3.2L12 12l-3.2 1.5.6-3.2L7 8l3.3-1.6L12 3z"/></svg>
                        @endswitch
                      </span>
                      <span class="service-card-title">{{ $card['title'] }}</span>
                    </a>
                  </h2>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
      <div class="col-md-4">
        @foreach (array_slice($homepage['category_showcase']['cards'], 2, 2) as $card)
          @php $cardImage = str_contains($card['image'], 'assets/') ? asset($card['image']) : asset('storage/' . $card['image']); @endphp
          <div class="category-wrap ftco-animate img {{ $loop->first ? 'mb-4' : '' }} d-flex align-items-end" style="background-image: url('{{ $cardImage }}');">
            <div class="text px-3 py-1">
              <h2 class="mb-0">
                <a href="{{ route($card['route'] ?? 'shop') }}">
                  @php
                    $iconType = $card['icon'] ?? ($serviceIcons[$card['route'] ?? ''] ?? 'spark');
                  @endphp
                  <span class="service-card-icon" aria-hidden="true">
                    @switch($iconType)
                      @case('ticket')
                        <svg viewBox="0 0 24 24"><path d="M3 9V7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v2a2 2 0 0 0 0 4v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-2a2 2 0 0 0 0-4z"/><path d="M9 9h.01M9 15h.01"/></svg>
                        @break
                      @case('bed')
                        <svg viewBox="0 0 24 24"><path d="M3 11v8"/><path d="M21 11v8"/><path d="M3 16h18"/><path d="M3 11h18"/><path d="M7 11V9a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v2"/></svg>
                        @break
                      @case('bus')
                        <svg viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="12" rx="2"/><path d="M4 10h16"/><path d="M7 16v3"/><path d="M17 16v3"/><circle cx="8" cy="13" r="1"/><circle cx="16" cy="13" r="1"/></svg>
                        @break
                      @case('utensils')
                        <svg viewBox="0 0 24 24"><path d="M4 3v8"/><path d="M7 3v8"/><path d="M4 7h3"/><path d="M6 11v10"/><path d="M14 3v6a2 2 0 0 0 4 0V3"/><path d="M16 11v10"/></svg>
                        @break
                      @default
                        <svg viewBox="0 0 24 24"><path d="M12 3l1.7 3.4L17 8l-2.4 2.3.6 3.2L12 12l-3.2 1.5.6-3.2L7 8l3.3-1.6L12 3z"/></svg>
                    @endswitch
                  </span>
                  <span class="service-card-title">{{ $card['title'] }}</span>
                </a>
              </h2>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</section>

<section class="ftco-section">
  <div class="container">
    <div class="row justify-content-center mb-3 pb-3">
      <div class="col-md-12 heading-section text-center ftco-animate">
        <span class="subheading">{{ $homepage['featured_section']['subheading'] }}</span>
        <h2 class="mb-4">{{ $homepage['featured_section']['title'] }}</h2>
        <p>{{ $homepage['featured_section']['text'] }}</p>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row">
      @foreach ($featuredProducts as $product)
        @include('partials.product-card', ['product' => $product])
      @endforeach
    </div>
    <div class="text-center mt-4">
      <a href="{{ route($homepage['featured_section']['button_route']) }}" class="btn btn-primary py-3 px-4">{{ $homepage['featured_section']['button_label'] }}</a>
    </div>
  </div>
</section>

<section class="ftco-section ftco-no-pt ftco-no-pb py-5 bg-light">
  <div class="container py-4">
    <div class="row d-flex justify-content-center py-5">
      <div class="col-md-6">
        <h2 style="font-size: 22px;" class="mb-0">{{ $homepage['newsletter']['title'] }}</h2>
        <span>{{ $homepage['newsletter']['text'] }}</span>
      </div>
      <div class="col-md-6 d-flex align-items-center">
        <form action="#" class="subscribe-form">
          <div class="form-group d-flex">
            <input type="text" class="form-control" placeholder="{{ $homepage['newsletter']['placeholder'] }}">
            <input type="submit" value="{{ $homepage['newsletter']['button_label'] }}" class="submit px-3">
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
@endsection
