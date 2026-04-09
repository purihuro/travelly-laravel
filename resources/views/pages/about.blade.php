@extends('layouts.app')

@section('title', 'Exflore KBB - Tentang & Kontak')

@section('content')
@php
  $heroImage = str_contains($aboutContent['hero_image'], 'assets/') ? asset($aboutContent['hero_image']) : asset('storage/' . $aboutContent['hero_image']);
  $introImage = str_contains($aboutContent['intro_image'], 'assets/') ? asset($aboutContent['intro_image']) : asset('storage/' . $aboutContent['intro_image']);
  $counterBg = str_contains($aboutContent['counters_background'], 'assets/') ? asset($aboutContent['counters_background']) : asset('storage/' . $aboutContent['counters_background']);
@endphp

<div class="hero-wrap hero-bread" style="background-image: url('{{ $heroImage }}');">
  <div class="container">
    <div class="row no-gutters slider-text align-items-center justify-content-center">
      <div class="col-md-9 ftco-animate text-center">
        <p class="breadcrumbs">
          <span class="mr-2"><a href="{{ route('home') }}">Home</a></span>
          <span>{{ $aboutContent['hero_breadcrumb'] }}</span>
        </p>
        <h1 class="mb-0 bread">{{ $aboutContent['hero_title'] }}</h1>
      </div>
    </div>
  </div>
</div>

<section class="ftco-section ftco-no-pb ftco-no-pt bg-light">
  <div class="container">
    <div class="row">
      <div class="col-md-5 p-md-5 img img-2 d-flex justify-content-center align-items-center" style="background-image: url('{{ $introImage }}');">
        <a href="{{ $aboutContent['video_url'] }}" class="icon popup-vimeo d-flex justify-content-center align-items-center">
          <span class="icon-play"></span>
        </a>
      </div>
      <div class="col-md-7 py-5 wrap-about pb-md-5 ftco-animate">
        <div class="heading-section-bold mb-4 mt-md-5">
          <div class="ml-md-0">
            <h2 class="mb-4">{{ $aboutContent['intro_heading'] }}</h2>
          </div>
        </div>
        <div class="pb-md-5">
          <p>{{ $aboutContent['paragraph_1'] }}</p>
          <p>{{ $aboutContent['paragraph_2'] }}</p>
          <p><a href="{{ route($aboutContent['cta_route']) }}" class="btn btn-primary">{{ $aboutContent['cta_label'] }}</a></p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="ftco-section ftco-no-pt ftco-no-pb py-5 bg-light">
  <div class="container py-4">
    <div class="row d-flex justify-content-center py-5">
      <div class="col-md-6">
        <h2 style="font-size: 22px;" class="mb-0">{{ $aboutContent['newsletter']['title'] }}</h2>
        <span>{{ $aboutContent['newsletter']['text'] }}</span>
      </div>
      <div class="col-md-6 d-flex align-items-center">
        <form action="#" class="subscribe-form">
          <div class="form-group d-flex">
            <input type="text" class="form-control" placeholder="{{ $aboutContent['newsletter']['placeholder'] }}">
            <input type="submit" value="{{ $aboutContent['newsletter']['button_label'] }}" class="submit px-3">
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<section class="ftco-section ftco-counter img" id="section-counter" style="background-image: url('{{ $counterBg }}');">
  <div class="container">
    <div class="row justify-content-center py-5">
      <div class="col-md-10">
        <div class="row">
          @foreach ($aboutContent['counters'] as $counter)
            <div class="col-md-3 d-flex justify-content-center counter-wrap ftco-animate">
              <div class="block-18 text-center">
                <div class="text">
                  <strong class="number" data-number="{{ $counter['number'] }}">0</strong>
                  <span>{{ $counter['label'] }}</span>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>

<section class="ftco-section testimony-section">
  <div class="container">
    <div class="row justify-content-center mb-5 pb-3">
      <div class="col-md-7 heading-section ftco-animate text-center">
        <span class="subheading">{{ $aboutContent['testimony']['subheading'] }}</span>
        <h2 class="mb-4">{{ $aboutContent['testimony']['title'] }}</h2>
        <p>{{ $aboutContent['testimony']['text'] }}</p>
      </div>
    </div>
  </div>
</section>

<section class="ftco-section bg-light">
  <div class="container">
    <div class="row no-gutters ftco-services">
      @foreach ($homepage['service_features'] as $feature)
        <div class="col-lg-3 text-center d-flex align-self-stretch ftco-animate">
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

<section class="ftco-section contact-section bg-light" id="contact-section">
  <div class="container">
    <div class="row justify-content-center mb-4">
      <div class="col-md-8 text-center heading-section ftco-animate">
        <span class="subheading">Kontak</span>
        <h2 class="mb-2">Hubungi Tim Exflore KBB</h2>
        <p class="mb-0">Kami siap membantu rencana perjalanan Anda melalui kanal kontak berikut.</p>
      </div>
    </div>

    <div class="row d-flex mb-5 contact-info">
      <div class="w-100"></div>
      @foreach ($contactContent['info_cards'] as $card)
        <div class="col-md-3 d-flex">
          <div class="info bg-white p-4 w-100">
            <p>
              <span>{{ $card['label'] }}:</span>
              @if (! empty($card['link']))
                <a href="{{ $card['link'] }}">{{ $card['value'] }}</a>
              @else
                {{ $card['value'] }}
              @endif
            </p>
          </div>
        </div>
      @endforeach
    </div>

    <div class="row block-9">
      <div class="col-md-6 order-md-last d-flex">
        <form action="#" class="bg-white p-5 contact-form w-100">
          <h3 class="mb-4">{{ $contactContent['form_title'] }}</h3>
          <div class="form-group">
            <input type="text" class="form-control" placeholder="{{ $contactContent['placeholders']['name'] }}">
          </div>
          <div class="form-group">
            <input type="text" class="form-control" placeholder="{{ $contactContent['placeholders']['email'] }}">
          </div>
          <div class="form-group">
            <input type="text" class="form-control" placeholder="{{ $contactContent['placeholders']['subject'] }}">
          </div>
          <div class="form-group">
            <textarea cols="30" rows="7" class="form-control" placeholder="{{ $contactContent['placeholders']['message'] }}"></textarea>
          </div>
          <div class="form-group mb-0">
            <input type="submit" value="{{ $contactContent['button_label'] }}" class="btn btn-primary py-3 px-5">
          </div>
        </form>
      </div>
      <div class="col-md-6 d-flex">
        <div id="map" class="bg-white w-100"></div>
      </div>
    </div>
  </div>
</section>
@endsection
