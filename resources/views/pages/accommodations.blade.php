@extends('layouts.app')

@section('title', 'Exflore KBB - Penginapan')

@push('styles')
  @include('partials.booking-flow-styles')
@endpush

@section('content')
<section class="catalog-shell">
  <div class="container">
    <form method="POST" action="{{ route('accommodations.save') }}">
      @csrf
      <div class="catalog-toolbar">
        <div class="catalog-toolbar__copy">
          <span class="booking-kicker">Penginapan</span>
          <h1>Pilih penginapan</h1>
          <p>Bandingkan opsi yang tersedia, lalu simpan satu penginapan yang paling cocok.</p>
        </div>
        <div class="catalog-toolbar__actions">
          <a href="{{ route('cart') }}" class="btn btn-primary py-3 px-4">Lihat Keranjang</a>
          <a href="{{ route('trip-builder') }}" class="btn btn-outline-secondary py-3 px-4">Trip Builder</a>
        </div>
      </div>

      <div class="catalog-filter">
        @foreach (['hotel' => 'Hotel', 'villa' => 'Villa', 'homestay' => 'Homestay', 'none' => 'Tanpa penginapan'] as $typeKey => $typeLabel)
          @php $isSelected = old('lodging_type', $lastLodgingType) === $typeKey; @endphp
          <button type="button" class="catalog-filter__btn lodging-filter-btn {{ $isSelected ? 'is-active' : '' }}" data-lodging-filter="{{ $typeKey }}">
            {{ $typeLabel }}
          </button>
        @endforeach
      </div>

      <input type="hidden" name="lodging_type" value="{{ old('lodging_type', $lastLodgingType) }}" id="lodging_type">
      <input type="hidden" name="accommodation_id" value="{{ old('accommodation_id', $selectedAccommodation['accommodation_id'] ?? '') }}" id="selected_accommodation_id">

      @foreach (['hotel' => 'Hotel', 'villa' => 'Villa', 'homestay' => 'Homestay'] as $typeKey => $typeLabel)
        <div class="lodging-group" data-lodging-type="{{ $typeKey }}" style="display:none;">
          <div class="catalog-grid">
            @foreach (($accommodations[$typeKey] ?? collect()) as $accommodation)
              @php
                $isSelectedAccommodation = (string) old('accommodation_id', $selectedAccommodation['accommodation_id'] ?? '') === (string) $accommodation->id
                  && old('lodging_type', $lastLodgingType) === $typeKey;
              @endphp
              <div class="catalog-card">
                <div class="catalog-card__image">
                  <img src="{{ $accommodation->image_url }}" alt="{{ $accommodation->name }}">
                </div>
                <div class="catalog-card__body">
                  <div class="catalog-card__meta">
                    <span class="catalog-card__tag">{{ $typeLabel }}</span>
                    <span class="catalog-card__price">${{ number_format((float) $accommodation->price_per_night, 2) }}/malam</span>
                  </div>
                  <h3 class="catalog-card__title">{{ $accommodation->name }}</h3>
                  <div class="catalog-card__subtitle">{{ $accommodation->location }} · Kapasitas {{ $accommodation->capacity }} orang</div>
                  @if ($accommodation->highlight)
                    <div class="catalog-card__highlight">{{ $accommodation->highlight }}</div>
                  @endif
                  <p class="catalog-card__description">{{ $accommodation->description }}</p>
                  <div class="catalog-card__details">
                    <div class="catalog-card__detail">
                      <span>Tipe kamar</span>
                      <strong>{{ $accommodation->room_type ?: 'Sesuai ketersediaan' }}</strong>
                    </div>
                    <div class="catalog-card__detail">
                      <span>Kapasitas</span>
                      <strong>{{ $accommodation->capacity }} orang</strong>
                    </div>
                  </div>
                  @if ($accommodation->amenities_list !== [])
                    <div class="catalog-card__amenities">
                      @foreach (array_slice($accommodation->amenities_list, 0, 4) as $amenity)
                        <span class="catalog-card__amenity">{{ $amenity }}</span>
                      @endforeach
                    </div>
                  @endif
                  <div class="catalog-card__choice {{ $isSelectedAccommodation ? 'is-active' : '' }}" data-accommodation-choice data-accommodation-type="{{ $typeKey }}" data-accommodation-id="{{ $accommodation->id }}">
                    <button type="button" class="catalog-card__buy-btn {{ $isSelectedAccommodation ? 'is-active' : '' }}" data-accommodation-buy>
                      {{ $isSelectedAccommodation ? 'Penginapan Dipilih' : 'Pilih Penginapan' }}
                    </button>
                    <small class="d-block mt-3">Klik untuk memilih penginapan ini.</small>
                  </div>
                  <div class="catalog-card__status">
                    <span class="catalog-card__status-badge {{ $isSelectedAccommodation ? 'is-visible' : '' }}" data-accommodation-badge>Sudah dipilih</span>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      @endforeach

      <div class="lodging-group" data-lodging-type="none" style="display:none;">
        <div class="booking-panel">
          <div class="booking-inline-note mt-0">
            Kamu bisa lanjut tanpa memilih penginapan jika ingin mengatur penginapan sendiri.
          </div>
        </div>
      </div>

      @error('accommodation_id')
        <small class="text-danger d-block mt-3">{{ $message }}</small>
      @enderror

      <div class="catalog-summary">
        <div class="catalog-summary__stats">
          <div>
            <strong>{{ $selectedAccommodation['name'] ?? 'Belum dipilih' }}</strong>
            <span>penginapan aktif saat ini</span>
          </div>
          <div>
            <strong>{{ $selectedAccommodation ? ucfirst($selectedAccommodation['accommodation_type']) : 'Opsional' }}</strong>
            <span>kategori penginapan</span>
          </div>
        </div>
        <div class="catalog-toolbar__actions">
          <button type="submit" class="btn btn-primary py-3 px-4">Tambah ke Keranjang</button>
          <a href="{{ route('cart') }}" class="btn btn-outline-secondary py-3 px-4">Buka Keranjang</a>
        </div>
      </div>
    </form>
  </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const hiddenType = document.getElementById('lodging_type');
  const hiddenAccommodationId = document.getElementById('selected_accommodation_id');
  const filterButtons = document.querySelectorAll('.lodging-filter-btn');
  const groups = document.querySelectorAll('.lodging-group');
  const choices = document.querySelectorAll('[data-accommodation-choice]');

  function syncLodgingGroups() {
    const active = hiddenType?.value || 'none';

    filterButtons.forEach((button) => {
      button.classList.toggle('is-active', button.dataset.lodgingFilter === active);
    });

    groups.forEach((group) => {
      const visible = group.dataset.lodgingType === active;
      group.style.display = visible ? 'block' : 'none';
    });

    choices.forEach((choice) => {
      const selected = hiddenAccommodationId?.value === choice.dataset.accommodationId && active === choice.dataset.accommodationType;
      choice.classList.toggle('is-active', selected);
      const button = choice.querySelector('[data-accommodation-buy]');
      const badge = choice.closest('.catalog-card')?.querySelector('[data-accommodation-badge]');
      if (button) {
        button.classList.toggle('is-active', selected);
        button.textContent = selected ? 'Penginapan Dipilih' : 'Pilih Penginapan';
      }
      if (badge) {
        badge.classList.toggle('is-visible', selected);
      }
    });
  }

  filterButtons.forEach((button) => {
    button.addEventListener('click', function () {
      const type = button.dataset.lodgingFilter;
      if (hiddenType) {
        hiddenType.value = type;
      }

      if (type === 'none' && hiddenAccommodationId) {
        hiddenAccommodationId.value = '';
      }

      syncLodgingGroups();
    });
  });

  choices.forEach((choice) => {
    const button = choice.querySelector('[data-accommodation-buy]');
    if (!button) {
      return;
    }

    button.addEventListener('click', function () {
      if (hiddenType) {
        hiddenType.value = choice.dataset.accommodationType;
      }

      if (hiddenAccommodationId) {
        hiddenAccommodationId.value = choice.dataset.accommodationId;
      }

      syncLodgingGroups();
    });
  });

  syncLodgingGroups();
});
</script>
@endpush
@endsection
