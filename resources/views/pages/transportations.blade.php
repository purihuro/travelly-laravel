@extends('layouts.app')

@section('title', 'Exflore KBB - Transportasi')

@push('styles')
  @include('partials.booking-flow-styles')
@endpush

@section('content')
<section class="catalog-shell">
  <div class="container">
    <form method="POST" action="{{ route('transportations.save') }}">
      @csrf
      <div class="catalog-toolbar">
        <div class="catalog-toolbar__copy">
          <span class="booking-kicker">Transportasi</span>
          <h1>Pilih transportasi</h1>
          <p>Tentukan opsi jemput yang sesuai, lalu lengkapi detail perjalanan.</p>
        </div>
        <div class="catalog-toolbar__actions">
          <a href="{{ route('cart') }}" class="btn btn-primary py-3 px-4">Lihat Keranjang</a>
          <a href="{{ route('trip-builder') }}" class="btn btn-outline-secondary py-3 px-4">Trip Builder</a>
        </div>
      </div>

      <div class="transport-grid">
        @foreach ($transportationOptions as $transportKey => $transportOption)
          @php $isSelected = old('transport_method', $lastTransportationMethod) === $transportKey; @endphp
          <div class="transport-card {{ $isSelected ? 'is-active' : '' }}" data-transport-card data-transport-key="{{ $transportKey }}">
            <div class="transport-card__header">
              <div>
                <h3 class="transport-card__title">{{ $transportOption['label'] }}</h3>
                <p class="transport-card__desc">{{ $transportOption['description'] }}</p>
              </div>
              <span class="transport-card__price">${{ number_format((float) $transportOption['price'], 2) }}</span>
            </div>
            <div class="transport-card__choose">
              <input type="radio" name="transport_method" value="{{ $transportKey }}" class="transport-method-radio d-none" {{ $isSelected ? 'checked' : '' }}>
              <button type="button" class="btn btn-primary transport-select-btn {{ $isSelected ? 'is-active' : '' }}" data-transport-select>
                {{ $isSelected ? 'Transportasi Dipilih' : ($transportKey === 'none' ? 'Tanpa Transportasi' : 'Pilih Transportasi') }}
              </button>
            </div>
          </div>
        @endforeach
      </div>

      <div class="transport-detail-panel transport-detail-group" data-transport-for="all" style="display:none;">
        <h3 class="transport-detail-panel__title">Lengkapi detail penjemputan</h3>
        <p class="transport-detail-panel__desc">Isi lokasi jemput, waktu, jumlah penumpang, dan catatan tambahan untuk memudahkan proses penjemputan.</p>

        <div class="booking-field transport-detail-group" data-transport-for="rental_car" style="display:none;">
          <label for="vehicle_detail">Detail kendaraan</label>
          <input id="vehicle_detail" type="text" class="form-control" name="vehicle_detail" value="{{ old('vehicle_detail', $selectedTransportation['vehicle_detail'] ?? '') }}" placeholder="Contoh: Avanza / Hiace / Elf">
          @error('vehicle_detail')
            <small class="text-danger d-block mt-2">{{ $message }}</small>
          @enderror
        </div>

        <div class="booking-field mt-4">
          <label for="pickup_location">Lokasi jemput</label>
          <input id="pickup_location" type="text" class="form-control" name="pickup_location" value="{{ old('pickup_location', $selectedTransportation['pickup_location'] ?? '') }}" placeholder="Masukkan alamat atau titik jemput">
          @error('pickup_location')
            <small class="text-danger d-block mt-2">{{ $message }}</small>
          @enderror
        </div>

        <div class="booking-field mt-4">
          <label for="pickup_time">Waktu jemput</label>
          <input id="pickup_time" type="datetime-local" class="form-control" name="pickup_time" value="{{ old('pickup_time', isset($selectedTransportation['pickup_time']) ? \Illuminate\Support\Carbon::parse($selectedTransportation['pickup_time'])->format('Y-m-d\TH:i') : '') }}">
          @error('pickup_time')
            <small class="text-danger d-block mt-2">{{ $message }}</small>
          @enderror
        </div>

        <div class="booking-field mt-4">
          <label for="passenger_count">Jumlah penumpang</label>
          <div class="destination-stepper" data-passenger-stepper>
            <button type="button" class="destination-stepper__btn" data-passenger-decrease aria-label="Kurangi penumpang">-</button>
            <div class="destination-stepper__value">
              <input id="passenger_count" type="text" inputmode="numeric" readonly class="form-control" name="passenger_count" value="{{ old('passenger_count', $selectedTransportation['passenger_count'] ?? 1) }}" data-passenger-input>
            </div>
            <button type="button" class="destination-stepper__btn" data-passenger-increase aria-label="Tambah penumpang">+</button>
          </div>
          @error('passenger_count')
            <small class="text-danger d-block mt-2">{{ $message }}</small>
          @enderror
        </div>

        <div class="booking-field mt-4">
          <label for="transport_notes">Catatan transportasi</label>
          <textarea id="transport_notes" class="form-control" name="transport_notes" rows="4" placeholder="Contoh: bawa koper besar, minta child seat, jemput di lobby hotel">{{ old('transport_notes', $selectedTransportation['transport_notes'] ?? '') }}</textarea>
        </div>
      </div>

      @if ($selectedTransportation)
        <div class="booking-inline-note mt-4">
          Transportasi tersimpan saat ini: <strong>{{ $selectedTransportation['name'] }}</strong>.
        </div>
      @endif

      <div class="catalog-summary">
        <div class="catalog-summary__stats">
          <div>
            <strong>{{ $selectedTransportation['name'] ?? 'Belum dipilih' }}</strong>
            <span>transportasi aktif saat ini</span>
          </div>
          <div>
            <strong>{{ $selectedTransportation['pickup_location'] ?? 'Opsional' }}</strong>
            <span>lokasi jemput</span>
          </div>
          <div>
            <strong>{{ $selectedTransportation['passenger_count'] ?? 1 }} orang</strong>
            <span>jumlah penumpang</span>
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
  const transportRadios = document.querySelectorAll('.transport-method-radio');
  const transportDetailGroups = document.querySelectorAll('.transport-detail-group');
  const transportCards = document.querySelectorAll('[data-transport-card]');
  const transportButtons = document.querySelectorAll('[data-transport-select]');
  const passengerInput = document.querySelector('[data-passenger-input]');
  const passengerDecrease = document.querySelector('[data-passenger-decrease]');
  const passengerIncrease = document.querySelector('[data-passenger-increase]');

  function syncTransportationFields() {
    const active = document.querySelector('.transport-method-radio:checked')?.value || 'none';

    transportCards.forEach((card) => {
      const input = card.querySelector('.transport-method-radio');
      const isActive = input?.value === active;
      card.classList.toggle('is-active', isActive);
      const button = card.querySelector('[data-transport-select]');
      if (button) {
        button.classList.toggle('is-active', isActive);
        button.textContent = isActive
          ? 'Transportasi Dipilih'
          : (input?.value === 'none' ? 'Tanpa Transportasi' : 'Pilih Transportasi');
      }
    });

    transportDetailGroups.forEach((group) => {
      const type = group.dataset.transportFor;
      const visible = active !== 'none' && (type === 'all' || type === active);
      group.style.display = visible ? 'block' : 'none';
      group.querySelectorAll('input, textarea').forEach((field) => {
        field.disabled = !visible;
      });
    });
  }

  transportRadios.forEach((radio) => radio.addEventListener('change', syncTransportationFields));
  transportButtons.forEach((button) => {
    button.addEventListener('click', function () {
      const card = button.closest('[data-transport-card]');
      const radio = card?.querySelector('.transport-method-radio');
      if (!radio) {
        return;
      }

      radio.checked = true;
      syncTransportationFields();
    });
  });

  function passengerValue() {
    const parsed = parseInt(passengerInput?.value || '1', 10);
    return Number.isNaN(parsed) ? 1 : parsed;
  }

  if (passengerDecrease && passengerInput) {
    passengerDecrease.addEventListener('click', function () {
      passengerInput.value = String(Math.max(1, passengerValue() - 1));
    });
  }

  if (passengerIncrease && passengerInput) {
    passengerIncrease.addEventListener('click', function () {
      passengerInput.value = String(Math.min(100, passengerValue() + 1));
    });
  }

  syncTransportationFields();
});
</script>
@endpush
@endsection
