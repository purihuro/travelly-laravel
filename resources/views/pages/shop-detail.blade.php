@extends('layouts.app')

@section('title', 'Exflore KBB - Detail Paket Wisata')

@section('content')
<div class="hero-wrap hero-bread" style="background-image: url('{{ asset('assets/images/bg_1.jpg') }}');">
  <div class="container">
    <div class="row no-gutters slider-text align-items-center justify-content-center">
      <div class="col-md-10 ftco-animate text-center">
        <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('home') }}">Home</a></span> <span class="mr-2"><a href="{{ route('shop') }}">Paket Wisata</a></span> <span>Detail Paket</span></p>
        <h1 class="mb-0 bread">{{ $package['title'] }}</h1>
      </div>
    </div>
  </div>
</div>

<section class="ftco-section">
  <div class="container">
    @php
      if (str_starts_with($package['image'], 'http://') || str_starts_with($package['image'], 'https://')) {
          $imageUrl = $package['image'];
      } elseif (str_contains($package['image'], 'assets/')) {
          $imageUrl = asset($package['image']);
      } else {
          $imageUrl = asset('storage/' . ltrim($package['image'], '/'));
      }
    @endphp

    <div class="row">
      <div class="col-lg-6">
        <img src="{{ $imageUrl }}" alt="{{ $package['title'] }}" class="img-fluid rounded shadow-sm mb-4">
      </div>
      <div class="col-lg-6">
        <h2>{{ $package['title'] }}</h2>
        <p><strong>Harga:</strong> {{ $package['display_price'] }} / orang</p>
        <p><strong>Durasi:</strong> {{ $package['duration_days'] }} hari</p>
        <p><strong>Lokasi:</strong> {{ $package['location'] ?: '-' }}</p>
        <p>{{ $package['summary'] ?: 'Paket wisata terkurasi dengan alur perjalanan yang terstruktur.' }}</p>

        <h4 class="mt-4">Destinasi yang termasuk</h4>
        <ul>
          @foreach ($package['destinations'] as $destination)
            <li>{{ $destination }}</li>
          @endforeach
        </ul>

        <form method="POST" action="{{ route('shop.package.add') }}" class="border rounded p-3 mt-4">
          @csrf
          <input type="hidden" name="travel_package_id" value="{{ $package['id'] }}">
          <div class="form-group">
            <label>Jumlah peserta</label>
            <input type="number" class="form-control" name="participants" min="1" max="100" value="{{ old('participants', 1) }}" required>
          </div>
          <div class="form-group">
            <label>Tanggal berangkat</label>
            <input type="date" class="form-control" name="departure_date" value="{{ old('departure_date') }}" min="{{ now()->format('Y-m-d') }}" required>
          </div>
          <button type="submit" class="btn btn-primary">Tambah ke Keranjang</button>
        </form>
      </div>
    </div>

    <div class="mt-4">
      <h4>Rangkuman perjalanan hari per hari</h4>
      <ol>
        <li>Hari 1: Keberangkatan dan check-in area tujuan.</li>
        @if ((int) $package['duration_days'] > 2)
          <li>Hari 2 sampai hari {{ (int) $package['duration_days'] - 1 }}: Kunjungan destinasi utama dalam paket.</li>
        @elseif ((int) $package['duration_days'] === 2)
          <li>Hari 2: Kunjungan destinasi utama dalam paket.</li>
        @endif
        <li>Hari {{ $package['duration_days'] }}: Agenda akhir perjalanan dan kembali ke rumah.</li>
      </ol>
    </div>
  </div>
</section>
@endsection
