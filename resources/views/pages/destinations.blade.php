@extends('layouts.app')

@section('title', 'Exflore KBB - Destinasi')

@push('styles')
  @include('partials.booking-flow-styles')
@endpush

@section('content')
<section class="destination-page-shell">
  <div class="container">
    <form method="POST" action="{{ route('destinations.save') }}">
      @csrf
      <div class="destination-toolbar">
        <div class="destination-toolbar__copy">
          <span class="booking-kicker">Tiket Wisata</span>
          <h1>Pilih destinasi</h1>
          <p>Tentukan tiket yang ingin dibeli, atur jumlahnya, lalu simpan ke keranjang.</p>
        </div>
        <div class="destination-toolbar__actions">
          <a href="{{ route('cart') }}" class="btn btn-primary py-3 px-4">Lihat Keranjang</a>
          <a href="{{ route('trip-builder') }}" class="btn btn-outline-secondary py-3 px-4">Trip Builder</a>
        </div>
      </div>

      <div class="destination-filter">
        <div class="destination-filter__field">
          <label for="destination-search">Cari destinasi</label>
          <input id="destination-search" type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Cari nama, lokasi, atau deskripsi">
        </div>
        <div class="destination-filter__field">
          <label for="destination-location">Lokasi</label>
          <select id="destination-location" name="location" class="form-control">
            <option value="">Semua lokasi</option>
            @foreach ($destinationLocations as $destinationLocation)
              <option value="{{ $destinationLocation }}" {{ request('location') === $destinationLocation ? 'selected' : '' }}>{{ $destinationLocation }}</option>
            @endforeach
          </select>
        </div>
        <div class="destination-filter__field">
          <label for="destination-price">Rentang harga</label>
          <select id="destination-price" name="price" class="form-control">
            <option value="">Semua harga</option>
            <option value="under-10" {{ request('price') === 'under-10' ? 'selected' : '' }}>Di bawah $10</option>
            <option value="10-15" {{ request('price') === '10-15' ? 'selected' : '' }}>$10 - $15</option>
            <option value="above-15" {{ request('price') === 'above-15' ? 'selected' : '' }}>Di atas $15</option>
          </select>
        </div>
        <div class="destination-filter__field" style="display:flex;align-items:end;">
          <div class="destination-toolbar__actions" style="width:100%;">
            <button type="submit" formaction="{{ route('destinations') }}" formmethod="GET" class="btn btn-outline-secondary py-3 px-4" style="width:100%;">Terapkan Filter</button>
          </div>
        </div>
      </div>

      @if ($destinationTickets === [])
        <div class="destination-empty">
          <h3 class="mb-3">Destinasi tidak ditemukan</h3>
          <p class="mb-0">Coba ubah kata kunci pencarian atau filter lokasi dan harga supaya pilihan destinasi yang cocok bisa muncul lagi.</p>
        </div>
      @else
        <div class="destination-grid">
          @foreach ($destinationTickets as $ticket)
            @php $selectedItem = collect($selectedTickets)->firstWhere('slug', $ticket['slug']); @endphp
            <div class="destination-card ftco-animate">
              <div class="destination-card__image">
                <img src="{{ asset('assets/images/' . $ticket['image']) }}" alt="{{ $ticket['name'] }}">
              </div>
              <div class="destination-card__body">
              <div class="destination-card__meta">
                <span class="destination-card__location">{{ $ticket['location'] }}</span>
                <span class="destination-card__price">${{ number_format($ticket['price'], 2) }}</span>
              </div>
              <h3 class="destination-card__title">{{ $ticket['name'] }}</h3>
              <p class="destination-card__description">{{ $ticket['description'] }}</p>
              <div class="destination-card__details">
                <div class="destination-card__detail">
                  <span>Kategori</span>
                  <strong>{{ $ticket['category'] ?: 'Destinasi' }}</strong>
                </div>
                <div class="destination-card__detail">
                  <span>Jam buka</span>
                  <strong>{{ $ticket['open_hours'] ?: 'Lihat info lokasi' }}</strong>
                </div>
                <div class="destination-card__detail">
                  <span>Durasi</span>
                  <strong>{{ !empty($ticket['duration_minutes']) ? $ticket['duration_minutes'] . ' menit' : 'Fleksibel' }}</strong>
                </div>
                <div class="destination-card__detail">
                  <span>Cocok untuk</span>
                  <strong>{{ $ticket['audience'] ?: 'Semua traveler' }}</strong>
                </div>
              </div>
              @php $isSelected = collect(old('destinations', collect($selectedTickets)->pluck('slug')->all()))->contains($ticket['slug']); @endphp
              <div class="destination-card__select {{ $isSelected ? 'is-active' : '' }}" data-destination-select>
                <input type="checkbox" class="d-none" name="destinations[]" value="{{ $ticket['slug'] }}" {{ $isSelected ? 'checked' : '' }}>
                <div class="w-100">
                  <button type="button" class="destination-card__buy-btn {{ $isSelected ? 'is-active' : '' }}" data-buy-ticket>
                      {{ $isSelected ? 'Tiket Dipilih' : 'Pilih Tiket' }}
                    </button>
                    <small class="d-block mt-3">Pilih tiket ini untuk dimasukkan ke keranjang.</small>
                  </div>
                </div>
                <div class="destination-card__qty">
                  <label for="quantity-{{ $ticket['slug'] }}">Jumlah tiket</label>
                <div class="destination-stepper" data-stepper>
                  <button type="button" class="destination-stepper__btn" data-stepper-decrease aria-label="Kurangi tiket">-</button>
                  <div class="destination-stepper__value">
                    <input id="quantity-{{ $ticket['slug'] }}" type="text" inputmode="numeric" readonly class="form-control" name="quantities[{{ $ticket['slug'] }}]" value="{{ old('quantities.' . $ticket['slug'], $selectedItem['quantity'] ?? 1) }}" data-stepper-input>
                  </div>
                  <button type="button" class="destination-stepper__btn" data-stepper-increase aria-label="Tambah tiket">+</button>
                </div>
              </div>
              <div class="destination-card__status">
                <span class="destination-card__status-badge {{ $isSelected ? 'is-visible' : '' }}" data-selected-badge>Sudah dipilih</span>
                <span class="destination-card__subtotal" data-card-subtotal>1 tiket = <strong>${{ number_format((float) (($selectedItem['quantity'] ?? 1) * $ticket['price']), 2) }}</strong></span>
              </div>
            </div>
          </div>
        @endforeach
      </div>
      @endif

      <div class="destination-summary is-sticky">
        <div class="destination-summary__stats">
          <div>
            <strong>{{ count($selectedTickets) }}</strong>
            <span>destinasi terpilih</span>
          </div>
          <div>
            <strong>${{ number_format($ticketTotals['total'], 2) }}</strong>
            <span>total tiket saat ini</span>
          </div>
        </div>
        <div class="destination-toolbar__actions">
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
  const cards = document.querySelectorAll('.destination-card');

  cards.forEach((card) => {
    const selectWrap = card.querySelector('[data-destination-select]');
    const checkbox = selectWrap?.querySelector('input[type="checkbox"]');
    const buyButton = card.querySelector('[data-buy-ticket]');
    const input = card.querySelector('[data-stepper-input]');
    const decrease = card.querySelector('[data-stepper-decrease]');
    const increase = card.querySelector('[data-stepper-increase]');
    const subtotal = card.querySelector('[data-card-subtotal]');
    const badge = card.querySelector('[data-selected-badge]');
    const priceText = card.querySelector('.destination-card__price')?.textContent || '$0';
    const numericPrice = parseFloat(priceText.replace(/[^0-9.]/g, '')) || 0;

    if (!checkbox || !buyButton || !input || !decrease || !increase) {
      return;
    }

    function currentValue() {
      const parsed = parseInt(input.value || '0', 10);
      return Number.isNaN(parsed) ? 0 : parsed;
    }

    function syncCardState() {
      const active = checkbox.checked;
      selectWrap.classList.toggle('is-active', active);
      buyButton.classList.toggle('is-active', active);
      buyButton.textContent = active ? 'Tiket Dipilih' : 'Beli Tiket';
      if (badge) {
        badge.classList.toggle('is-visible', active);
      }
      if (subtotal) {
        const count = Math.max(1, currentValue());
        subtotal.innerHTML = count + ' tiket = <strong>$' + (numericPrice * count).toFixed(2) + '</strong>';
      }
    }

    function setValue(nextValue) {
      input.value = String(Math.max(0, nextValue));
    }

    buyButton.addEventListener('click', function () {
      checkbox.checked = true;
      if (currentValue() < 1) {
        setValue(1);
      }
      syncCardState();
    });

    increase.addEventListener('click', function () {
      checkbox.checked = true;
      setValue(currentValue() + 1);
      syncCardState();
    });

    decrease.addEventListener('click', function () {
      const value = currentValue();

      if (value <= 1) {
        if (window.confirm('Hapus tiket ini dari pilihan?')) {
          checkbox.checked = false;
          setValue(0);
          syncCardState();
        }
        return;
      }

      checkbox.checked = true;
      setValue(value - 1);
      syncCardState();
    });

    checkbox.addEventListener('change', function () {
      if (checkbox.checked && currentValue() < 1) {
        setValue(1);
      }

      syncCardState();
    });

    syncCardState();
  });
});
</script>
@endpush
@endsection
