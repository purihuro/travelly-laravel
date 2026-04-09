@extends('layouts.app')

@section('title', 'Exflore KBB - Paket Wisata')

@push('styles')
<style>
  .section-head {
    margin-bottom: 20px;
  }

  .package-card {
    border: 1px solid #e8ecf2;
    border-radius: 14px;
    overflow: hidden;
    background: #fff;
    margin-bottom: 28px;
    box-shadow: 0 10px 25px rgba(30, 52, 85, 0.06);
  }

  .package-card__image {
    min-height: 230px;
    background-size: cover;
    background-position: center;
  }

  .package-card__body {
    padding: 24px;
  }

  .package-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 14px;
  }

  .package-meta__tag {
    background: #f3f7ff;
    color: #1c3f72;
    border-radius: 999px;
    padding: 5px 12px;
    font-size: 0.82rem;
    font-weight: 600;
  }

  .package-destination-list {
    margin: 0;
    padding-left: 18px;
  }

  .package-destination-list li {
    margin-bottom: 6px;
  }

  .package-form {
    margin-top: 20px;
    padding: 16px;
    border-radius: 12px;
    background: #f8fbff;
    border: 1px solid #dfe8f6;
  }

  .package-note {
    font-size: 0.92rem;
    color: #385073;
    margin-top: 10px;
  }

  .custom-trip-box {
    position: relative;
    overflow: hidden;
    border-radius: 14px;
    padding: 26px;
    margin: 0 0 26px;
    border: 1px solid #d7e4f7;
    background: linear-gradient(135deg, #f4f8ff 0%, #eef5ff 52%, #ffffff 100%);
    box-shadow: 0 14px 28px rgba(28, 63, 114, 0.08);
  }

  .custom-trip-box::after {
    content: '';
    position: absolute;
    right: -42px;
    bottom: -42px;
    width: 140px;
    height: 140px;
    border-radius: 999px;
    background: rgba(28, 63, 114, 0.08);
    pointer-events: none;
  }

  .custom-trip-box__kicker {
    display: inline-block;
    margin-bottom: 10px;
    font-size: 0.78rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    font-weight: 700;
    color: #1c3f72;
    background: #e8f0fd;
    border-radius: 999px;
    padding: 5px 11px;
  }

  .custom-trip-box__layout {
    display: flex;
    gap: 20px;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    position: relative;
    z-index: 1;
  }

  .custom-trip-box__content {
    flex: 1 1 420px;
    min-width: 280px;
  }

  .custom-trip-box__actions {
    display: flex;
    flex-direction: column;
    gap: 8px;
    min-width: 220px;
  }

  .custom-trip-box__actions .btn {
    width: 100%;
  }

  .custom-trip-box__hint {
    margin: 0;
    font-size: 0.88rem;
    color: #4b6587;
    text-align: center;
  }

  @media (max-width: 768px) {
    .custom-trip-box {
      padding: 20px;
    }

    .custom-trip-box__actions {
      width: 100%;
      min-width: 0;
    }
  }
</style>
@endpush

@section('content')
<div class="hero-wrap hero-bread" style="background-image: url('{{ asset('assets/images/bg_1.jpg') }}');">
  <div class="container">
    <div class="row no-gutters slider-text align-items-center justify-content-center">
      <div class="col-md-9 ftco-animate text-center">
        <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('home') }}">Home</a></span> <span>Paket Wisata</span></p>
        <h1 class="mb-0 bread">Pilih Paket Wisata</h1>
      </div>
    </div>
  </div>
</div>

<section class="ftco-section">
  <div class="container">
    <div class="section-head">
      <h2 class="mb-2">Paket Wisata Siap Pilih</h2>
      <p class="mb-0">Pilih paket wisata yang sudah tersedia dan lakukan pemesanan dengan mudah.</p>
    </div>

    <div class="custom-trip-box">
      <div class="custom-trip-box__layout">
        <div class="custom-trip-box__content">
          <span class="custom-trip-box__kicker">Mode Fleksibel</span>
          <h3 class="mb-2">Buat Paket Liburan Sendiri</h3>
          <p class="mb-0">Atur perjalanan sesuai kebutuhan. Pilih layanan wisata, penginapan, transportasi, dan kuliner dengan alur yang lebih fleksibel.</p>
        </div>
        <div class="custom-trip-box__actions">
          <a href="{{ route('trip-builder') }}" class="btn btn-primary py-3 px-4">Atur Trip Sendiri</a>
          <p class="custom-trip-box__hint">Cocok jika ingin kombinasi layanan sendiri.</p>
        </div>
      </div>
    </div>

    @if ($packages === [])
      <div class="alert alert-info">Belum ada paket wisata aktif. Silakan tambah dulu dari admin.</div>
    @endif

    @foreach ($packages as $package)
      @php
        if (str_starts_with($package['image'], 'http://') || str_starts_with($package['image'], 'https://')) {
            $imageUrl = $package['image'];
        } elseif (str_contains($package['image'], 'assets/')) {
            $imageUrl = asset($package['image']);
        } else {
            $imageUrl = asset('storage/' . ltrim($package['image'], '/'));
        }
      @endphp
      <div class="package-card">
        <div class="row no-gutters">
          <div class="col-lg-5">
            <div class="package-card__image" style="background-image:url('{{ $imageUrl }}');"></div>
          </div>
          <div class="col-lg-7">
            <div class="package-card__body">
              <h3 class="mb-2">{{ $package['title'] }}</h3>
              <div class="package-meta">
                <span class="package-meta__tag">Harga {{ $package['display_price'] }} / orang</span>
                <span class="package-meta__tag">Durasi {{ $package['duration_days'] }} hari</span>
                <span class="package-meta__tag">{{ $package['location'] ?: 'Multi lokasi' }}</span>
              </div>
              <p>{{ $package['description'] ?: 'Paket wisata siap pilih dengan itinerary terencana.' }}</p>

              <form method="POST" action="{{ route('cart.add') }}" class="package-form">
                @csrf
                <input type="hidden" name="tour_package" value="tour_package">
                <input type="hidden" name="package_id" value="{{ $package['id'] }}">
                <div class="row">
                  <div class="col-md-4">
                    <label class="form-label">Jumlah Peserta</label>
                    <small class="d-block mb-1 text-muted">Masukkan jumlah orang yang ikut dalam perjalanan</small>
                    <input type="number" class="form-control" name="qty" min="1" max="100" value="{{ old('qty', 1) }}" required>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">Tanggal Keberangkatan</label>
                    <small class="d-block mb-1 text-muted">Pilih tanggal Anda memulai perjalanan</small>
                    <input type="date" class="form-control" name="departure_date" value="{{ old('departure_date') }}" min="{{ now()->format('Y-m-d') }}" required>
                  </div>
                  <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Tambah ke Keranjang</button>
                  </div>
                </div>
                @if ($errors->any())
                  <small class="text-danger d-block mt-2">Periksa kembali jumlah peserta dan tanggal keberangkatan.</small>
                @endif
                <p class="package-note mb-0">Paket ini sudah termasuk destinasi wisata sesuai pilihan.</p>
              </form>

              <h5 class="mt-4 mb-2">Destinasi yang termasuk</h5>
              <ul class="package-destination-list">
                @foreach ($package['destinations'] as $destination)
                  <li>{{ $destination }}</li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      </div>
    @endforeach

  </div>
</section>
@endsection
