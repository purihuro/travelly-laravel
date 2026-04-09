@extends('layouts.app')

@section('title', 'Exflore KBB - Trip Builder')

@push('styles')
<style>
  .builder-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 18px;
    margin-top: 20px;
  }

  .builder-card {
    border: 1px solid #dfe6f2;
    border-radius: 14px;
    padding: 18px;
    background: #fff;
    box-shadow: 0 8px 24px rgba(30, 52, 85, 0.06);
  }

  .builder-card h4 {
    margin-bottom: 10px;
  }

  .builder-plan {
    border: 1px solid #d9e8cf;
    background: #f6fbef;
    border-radius: 12px;
    padding: 14px;
    margin-bottom: 16px;
  }

  .builder-plan table {
    margin-bottom: 0;
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
  }

  .builder-plan thead th {
    background: #eaf4de;
    border-top: 0;
    font-size: 0.82rem;
    text-transform: uppercase;
    letter-spacing: 0.02em;
  }

  .builder-plan td,
  .builder-plan th {
    vertical-align: middle;
  }

  .builder-card__actions {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
  }

  .builder-modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(8, 19, 39, 0.56);
    z-index: 1050;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 16px;
  }

  .builder-modal-backdrop.is-open {
    display: flex;
  }

  .builder-modal {
    width: min(860px, 100%);
    max-height: 92vh;
    overflow-y: auto;
    background: #fff;
    border-radius: 14px;
    padding: 18px;
    box-shadow: 0 22px 42px rgba(13, 33, 67, 0.24);
  }

  .builder-modal__header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 14px;
  }

  .builder-modal__close {
    border: 0;
    background: transparent;
    font-size: 22px;
    line-height: 1;
    color: #28405f;
  }

  .transport-grid-mini {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 12px;
  }

  .transport-card-mini {
    border: 1px solid #dbe6f5;
    border-radius: 12px;
    padding: 12px;
  }

  .transport-card-mini.is-active {
    border-color: #7fad3d;
    box-shadow: 0 0 0 2px rgba(127, 173, 61, 0.15);
  }

  .transport-card-mini__title {
    font-weight: 700;
    margin-bottom: 6px;
  }

  .transport-card-mini__price {
    color: #1f4b3f;
    font-weight: 700;
    margin-bottom: 8px;
  }

  /* Error styling */
  .form-group.has-error .form-control {
    border-color: #dc3545;
    background-color: #fff5f5;
  }

  .form-group.has-error .form-control:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
  }

  .invalid-feedback {
    display: block;
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.25rem;
  }

  .form-group label {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #2c3e50;
  }

  .form-group label .required {
    color: #dc3545;
  }
</style>
@endpush

@section('content')
<div class="hero-wrap hero-bread" style="background-image: url('{{ asset('assets/images/bg_1.jpg') }}');">
  <div class="container">
    <div class="row no-gutters slider-text align-items-center justify-content-center">
      <div class="col-md-9 ftco-animate text-center">
        <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('home') }}">Home</a></span> <span>Trip Builder</span></p>
        <h1 class="mb-0 bread">Buat Paket Liburan Sendiri</h1>
      </div>
    </div>
  </div>
</div>

<section class="ftco-section">
  <div class="container">
    <p class="mb-3">Pilih layanan satu per satu melalui popup, lalu cek ringkasan pilihan sebelum lanjut ke keranjang.</p>

    @if (session('status'))
      <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @php
      $initialBuilderModal = null;
      if ($errors->has('accommodation_id') || $errors->has('lodging_type')) {
        $initialBuilderModal = 'accommodation-modal';
      } elseif ($errors->has('culinary_package_id') || $errors->has('quantity') || $errors->has('reservation_date') || $errors->has('arrival_time')) {
        $initialBuilderModal = 'culinary-modal';
      } elseif ($errors->has('destinations') || $errors->has('quantities')) {
        $initialBuilderModal = 'destination-modal';
      } elseif ($errors->has('transport_method') || $errors->has('pickup_location') || $errors->has('pickup_time') || $errors->has('passenger_count') || $errors->has('vehicle_detail')) {
        $initialBuilderModal = 'transport-modal';
      }
    @endphp

    <div class="builder-plan">
      <strong>📋 Rencana Layanan Yang Sudah Dipilih</strong>
      <div class="table-responsive mt-3">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Layanan</th>
              <th>Detail</th>
              <th>Tanggal</th>
              <th>Jam</th>
              <th>Catatan</th>
            </tr>
          </thead>
          <tbody>
            @if (!empty($summaryRows))
              @foreach ($summaryRows as $row)
                <tr>
                  <td><strong>{{ $row['service'] }}</strong></td>
                  <td>{{ $row['detail'] }}</td>
                  <td>{{ $row['date'] }}</td>
                  <td>{{ $row['time'] }}</td>
                  <td>{{ $row['notes'] }}</td>
                </tr>
              @endforeach
            @else
              <tr>
                <td colspan="5" class="text-center text-muted py-4"><small><em>Belum ada layanan yang dipilih. Silakan pilih layanan terlebih dahulu.</em></small></td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>
    </div>

    <div class="builder-grid">
      <div class="builder-card">
        <h4>Transportasi</h4>
        <p>Pilih kendaraan, lokasi jemput, dan waktu keberangkatan.</p>
        <div class="builder-card__actions">
          <button type="button" class="btn btn-primary btn-sm" data-open-modal="transport-modal">Pilih Transportasi</button>
        </div>
      </div>
      <div class="builder-card">
        <h4>Penginapan</h4>
        <p>Pilih hotel, villa, atau homestay sesuai rencana perjalanan.</p>
        <div class="builder-card__actions">
          <button type="button" class="btn btn-primary btn-sm" data-open-modal="accommodation-modal">Pilih Penginapan</button>
        </div>
      </div>
      <div class="builder-card">
        <h4>Kuliner</h4>
        <p>Pilih rumah makan, paket menu, tanggal, dan jam reservasi.</p>
        <div class="builder-card__actions">
          <button type="button" class="btn btn-primary btn-sm" data-open-modal="culinary-modal">Pilih Kuliner</button>
        </div>
      </div>
      <div class="builder-card">
        <h4>Wisata</h4>
        <p>Pilih tiket destinasi wisata dan jumlah peserta perjalanan.</p>
        <div class="builder-card__actions">
          <button type="button" class="btn btn-primary btn-sm" data-open-modal="destination-modal">Pilih Wisata</button>
        </div>
      </div>
    </div>

    <div class="mt-4">
      <a href="{{ route('cart') }}" class="btn btn-outline-secondary">Lihat Keranjang</a>
    </div>
  </div>
</section>

<div class="builder-modal-backdrop" id="transport-modal" aria-hidden="true">
  <div class="builder-modal" role="dialog" aria-modal="true" aria-labelledby="transport-modal-title">
    <div class="builder-modal__header">
      <h3 id="transport-modal-title" class="mb-0">Layanan Transportasi</h3>
      <button type="button" class="builder-modal__close" data-close-modal="transport-modal" aria-label="Tutup">&times;</button>
    </div>

    <form method="POST" action="{{ route('transportations.save') }}">
      @csrf
      <input type="hidden" name="redirect_to" value="trip-builder">

      <div class="transport-grid-mini mb-3">
        @foreach ($transportationOptions as $transportKey => $transportOption)
          @php
            $defaultMethod = $lastTransportationMethod ?? 'shuttle';
            $isSelected = old('transport_method', $defaultMethod) === $transportKey;
          @endphp
          <label class="transport-card-mini {{ $isSelected ? 'is-active' : '' }}" data-transport-mini="{{ $transportKey }}">
            <input type="radio" name="transport_method" value="{{ $transportKey }}" class="d-none transport-mini-radio" {{ $isSelected ? 'checked' : '' }}>
            <div class="transport-card-mini__title">{{ $transportOption['label'] }}</div>
            <div class="transport-card-mini__price">${{ number_format((float) $transportOption['price'], 2) }}</div>
            <p class="mb-0">{{ $transportOption['description'] }}</p>
          </label>
        @endforeach
      </div>

      <div id="transport-detail-fields">
        <div class="form-group {{ $errors->has('vehicle_detail') ? 'has-error' : '' }}">
          <label>Detail kendaraan <span class="required">*</span> (khusus sewa kendaraan)</label>
          <input type="text" class="form-control {{ $errors->has('vehicle_detail') ? 'is-invalid' : '' }}" name="vehicle_detail" value="{{ old('vehicle_detail', $selectedTransportation['vehicle_detail'] ?? '') }}" placeholder="Contoh: Avanza / Hiace">
          @if ($errors->has('vehicle_detail'))
            <div class="invalid-feedback">{{ $errors->first('vehicle_detail') }}</div>
          @endif
        </div>

        <div class="form-group {{ $errors->has('pickup_location') ? 'has-error' : '' }}">
          <label>Lokasi jemput <span class="required">*</span></label>
          <input type="text" class="form-control {{ $errors->has('pickup_location') ? 'is-invalid' : '' }}" name="pickup_location" value="{{ old('pickup_location', $selectedTransportation['pickup_location'] ?? '') }}" placeholder="Alamat / titik jemput">
          @if ($errors->has('pickup_location'))
            <div class="invalid-feedback">{{ $errors->first('pickup_location') }}</div>
          @endif
        </div>

        <div class="form-group {{ $errors->has('pickup_time') ? 'has-error' : '' }}">
          <label>Waktu jemput <span class="required">*</span></label>
          <input type="datetime-local" class="form-control {{ $errors->has('pickup_time') ? 'is-invalid' : '' }}" name="pickup_time" value="{{ old('pickup_time', isset($selectedTransportation['pickup_time']) ? \Illuminate\Support\Carbon::parse($selectedTransportation['pickup_time'])->format('Y-m-d\\TH:i') : '') }}">
          @if ($errors->has('pickup_time'))
            <div class="invalid-feedback">{{ $errors->first('pickup_time') }}</div>
          @endif
        </div>

        <div class="form-group {{ $errors->has('passenger_count') ? 'has-error' : '' }}">
          <label>Jumlah penumpang <span class="required">*</span></label>
          <input type="number" min="1" max="100" class="form-control {{ $errors->has('passenger_count') ? 'is-invalid' : '' }}" name="passenger_count" value="{{ old('passenger_count', $selectedTransportation['passenger_count'] ?? 1) }}">
          @if ($errors->has('passenger_count'))
            <div class="invalid-feedback">{{ $errors->first('passenger_count') }}</div>
          @endif
        </div>

        <div class="form-group">
          <label>Catatan</label>
          <textarea class="form-control" rows="3" name="transport_notes" placeholder="Catatan tambahan optional">{{ old('transport_notes', $selectedTransportation['transport_notes'] ?? '') }}</textarea>
        </div>
      </div>

      <div class="d-flex" style="gap:10px;">
        <button type="submit" class="btn btn-primary">Lanjutkan</button>
        <button type="button" class="btn btn-outline-secondary" data-close-modal="transport-modal">Batal</button>
      </div>
    </form>
  </div>
</div>

<div class="builder-modal-backdrop" id="accommodation-modal" aria-hidden="true">
  <div class="builder-modal" role="dialog" aria-modal="true" aria-labelledby="accommodation-modal-title">
    <div class="builder-modal__header">
      <h3 id="accommodation-modal-title" class="mb-0">Layanan Penginapan</h3>
      <button type="button" class="builder-modal__close" data-close-modal="accommodation-modal" aria-label="Tutup">&times;</button>
    </div>

    <form method="POST" action="{{ route('accommodations.save') }}">
      @csrf
      <input type="hidden" name="redirect_to" value="trip-builder">
      <input type="hidden" name="lodging_type" id="trip-accommodation-type" value="{{ $lastLodgingType }}">
      <input type="hidden" name="accommodation_id" id="trip-accommodation-id" value="{{ old('accommodation_id', $selectedAccommodation['accommodation_id'] ?? '') }}">

      <div class="btn-group flex-wrap mb-3" role="group">
        @foreach (['hotel' => 'Hotel', 'villa' => 'Villa', 'homestay' => 'Homestay', 'none' => 'Tanpa penginapan'] as $typeKey => $typeLabel)
          <button type="button" class="btn btn-outline-secondary btn-sm trip-accommodation-filter {{ $lastLodgingType === $typeKey ? 'active' : '' }}" data-accommodation-type="{{ $typeKey }}">{{ $typeLabel }}</button>
        @endforeach
      </div>

      @foreach (['hotel' => 'Hotel', 'villa' => 'Villa', 'homestay' => 'Homestay'] as $typeKey => $typeLabel)
        <div class="trip-accommodation-group" data-accommodation-group="{{ $typeKey }}" style="display:none;">
          <div class="row">
            @foreach (($accommodations[$typeKey] ?? collect()) as $accommodation)
              @php $isSelectedAccommodation = (string) old('accommodation_id', $selectedAccommodation['accommodation_id'] ?? '') === (string) $accommodation->id; @endphp
              <div class="col-md-6 mb-3">
                <label class="catalog-card d-block mb-0 {{ $isSelectedAccommodation ? 'is-active' : '' }}" style="cursor:pointer;">
                  <input type="radio" name="trip_accommodation_choice" class="d-none trip-accommodation-radio" value="{{ $accommodation->id }}" data-accommodation-card="{{ $accommodation->id }}" {{ $isSelectedAccommodation ? 'checked' : '' }}>
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
                    <p class="catalog-card__description">{{ $accommodation->description }}</p>
                    <button type="button" class="btn btn-primary btn-sm mt-2 trip-accommodation-pick" data-accommodation-pick="{{ $accommodation->id }}" data-accommodation-type="{{ $typeKey }}">{{ $isSelectedAccommodation ? 'Penginapan Dipilih' : 'Pilih Penginapan' }}</button>
                  </div>
                </label>
              </div>
            @endforeach
          </div>
        </div>
      @endforeach

      <div class="d-flex" style="gap:10px;">
        <button type="submit" class="btn btn-primary">Lanjutkan</button>
        <button type="button" class="btn btn-outline-secondary" data-close-modal="accommodation-modal">Batal</button>
      </div>
    </form>
  </div>
</div>

<div class="builder-modal-backdrop" id="culinary-modal" aria-hidden="true">
  <div class="builder-modal" role="dialog" aria-modal="true" aria-labelledby="culinary-modal-title">
    <div class="builder-modal__header">
      <h3 id="culinary-modal-title" class="mb-0">Layanan Kuliner</h3>
      <button type="button" class="builder-modal__close" data-close-modal="culinary-modal" aria-label="Tutup">&times;</button>
    </div>

    <form method="POST" action="{{ route('culinaries.save') }}">
      @csrf
      <input type="hidden" name="redirect_to" value="trip-builder">
      <input type="hidden" name="culinary_package_id" id="trip-culinary-package-id" value="{{ $lastCulinaryPackageId }}">

      <div class="btn-group flex-wrap mb-3" role="group">
        <button type="button" class="btn btn-outline-secondary btn-sm active trip-culinary-filter" data-venue-filter="all">Semua Rumah Makan</button>
        @foreach ($culinaryPackages as $venueName => $packages)
          @php $venueId = (string) optional($packages->first()->culinaryVenue)->id; @endphp
          <button type="button" class="btn btn-outline-secondary btn-sm trip-culinary-filter" data-venue-filter="{{ $venueId }}">{{ $venueName }}</button>
        @endforeach
      </div>

      @foreach ($culinaryPackages as $venueName => $packages)
        @php $venueId = (string) optional($packages->first()->culinaryVenue)->id; @endphp
        <div class="trip-culinary-group" data-venue-group="{{ $venueId }}" style="display:none;">
          <div class="row">
            @foreach ($packages as $package)
              @php $isSelected = (string) $lastCulinaryPackageId === (string) $package->id; @endphp
              <div class="col-md-6 mb-3">
                <label class="catalog-card d-block mb-0 {{ $isSelected ? 'is-active' : '' }}" style="cursor:pointer;">
                  <input type="radio" name="trip_culinary_choice" class="d-none trip-culinary-radio" value="{{ $package->id }}" data-culinary-pick="{{ $package->id }}" data-venue-id="{{ $venueId }}" {{ $isSelected ? 'checked' : '' }}>
                  <div class="catalog-card__body">
                    <div class="catalog-card__meta">
                      <span class="catalog-card__tag">{{ $package->culinaryVenue?->name }}</span>
                      <span class="catalog-card__price">${{ number_format((float) $package->price_per_person, 2) }}/orang</span>
                    </div>
                    <h3 class="catalog-card__title">{{ $package->name }}</h3>
                    <p class="catalog-card__description">{{ $package->description ?: 'Paket makanan pilihan untuk reservasi customer.' }}</p>
                    <button type="button" class="btn btn-primary btn-sm mt-2 trip-culinary-pick" data-culinary-pick="{{ $package->id }}">{{ $isSelected ? 'Paket Dipilih' : 'Pilih Paket' }}</button>
                  </div>
                </label>
              </div>
            @endforeach
          </div>
        </div>
      @endforeach

      <div class="booking-panel mt-3">
        <h4 class="mb-3">Detail Reservasi Kuliner</h4>
        <div class="form-row">
          <div class="col-md-4 mb-3 booking-field">
            <label for="trip-culinary-quantity">Jumlah Pesanan / Orang</label>
            <input id="trip-culinary-quantity" type="number" name="quantity" min="1" max="100" class="form-control" value="{{ old('quantity', $selectedCulinary['quantity'] ?? 1) }}">
          </div>
          <div class="col-md-4 mb-3 booking-field">
            <label for="trip-culinary-reservation-date">Tanggal Reservasi</label>
            <input id="trip-culinary-reservation-date" type="date" name="reservation_date" class="form-control" value="{{ old('reservation_date', $selectedCulinary['reservation_date'] ?? '') }}">
          </div>
          <div class="col-md-4 mb-3 booking-field">
            <label for="trip-culinary-arrival-time">Jam Kedatangan</label>
            <input id="trip-culinary-arrival-time" type="time" name="arrival_time" class="form-control" value="{{ old('arrival_time', $selectedCulinary['arrival_time'] ?? '') }}">
          </div>
        </div>
        <div class="booking-field mt-2">
          <label for="trip-culinary-notes">Catatan Tambahan</label>
          <textarea id="trip-culinary-notes" name="culinary_notes" class="form-control" rows="4" placeholder="Contoh: alergi seafood, request area non-smoking, ulang tahun, dan lain-lain.">{{ old('culinary_notes', $selectedCulinary['culinary_notes'] ?? '') }}</textarea>
        </div>
      </div>

      <div class="d-flex mt-3" style="gap:10px;">
        <button type="submit" class="btn btn-primary">Lanjutkan</button>
        <button type="button" class="btn btn-outline-secondary" data-close-modal="culinary-modal">Batal</button>
      </div>
    </form>
  </div>
</div>

<div class="builder-modal-backdrop" id="destination-modal" aria-hidden="true">
  <div class="builder-modal" role="dialog" aria-modal="true" aria-labelledby="destination-modal-title">
    <div class="builder-modal__header">
      <h3 id="destination-modal-title" class="mb-0">Layanan Wisata</h3>
      <button type="button" class="builder-modal__close" data-close-modal="destination-modal" aria-label="Tutup">&times;</button>
    </div>

    <form method="POST" action="{{ route('destinations.save') }}">
      @csrf
      <input type="hidden" name="redirect_to" value="trip-builder">

      <div class="d-flex justify-content-between align-items-center mb-3">
        <small class="text-muted">Pilih satu atau beberapa destinasi wisata.</small>
        <span class="badge badge-success" id="trip-destination-count">0 dipilih</span>
      </div>

      <div class="row">
        @foreach ($destinationTickets as $ticket)
          @php
            $isSelected = in_array($ticket['slug'], old('destinations', $selectedDestinationSlugs ?? []), true);
            $selectedQuantity = collect($selectedDestinations)->firstWhere('slug', $ticket['slug'])['quantity'] ?? 1;
          @endphp
          <div class="col-md-6 mb-3">
            <div class="catalog-card {{ $isSelected ? 'is-active' : '' }}" style="height:100%;">
              <div class="catalog-card__body">
                <div class="catalog-card__meta">
                  <span class="catalog-card__tag">{{ $ticket['location'] ?? 'Destinasi' }}</span>
                  <span class="catalog-card__price">${{ number_format((float) $ticket['price'], 2) }}</span>
                </div>
                <h3 class="catalog-card__title">{{ $ticket['name'] }}</h3>
                <p class="catalog-card__description">{{ $ticket['description'] }}</p>
                <label class="d-flex align-items-center mb-2" style="gap:10px;">
                  <input type="checkbox" class="trip-destination-checkbox" name="destinations[]" value="{{ $ticket['slug'] }}" data-destination-slug="{{ $ticket['slug'] }}" {{ $isSelected ? 'checked' : '' }}>
                  <span>{{ $isSelected ? 'Wisata Dipilih' : 'Pilih Wisata Ini' }}</span>
                </label>
                <label class="mb-1">Jumlah tiket</label>
                <input type="number" min="1" max="100" class="form-control" name="quantities[{{ $ticket['slug'] }}]" value="{{ old('quantities.' . $ticket['slug'], $selectedQuantity) }}">
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <div class="d-flex mt-3" style="gap:10px;">
        <button type="submit" class="btn btn-primary">Lanjutkan</button>
        <button type="button" class="btn btn-outline-secondary" data-close-modal="destination-modal">Batal</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const initialBuilderModal = @json($initialBuilderModal);

  // Auto-open modal jika ada error validasi
  const openButtons = document.querySelectorAll('[data-open-modal]');
  const closeButtons = document.querySelectorAll('[data-close-modal]');

  function toggleModal(id, open) {
    const modal = document.getElementById(id);
    if (!modal) {
      return;
    }

    modal.classList.toggle('is-open', open);
    modal.setAttribute('aria-hidden', open ? 'false' : 'true');
  }

  if (initialBuilderModal) {
    toggleModal(initialBuilderModal, true);
    const firstErrorField = document.querySelector('.has-error .form-control, .invalid-feedback, .form-control.is-invalid');
    if (firstErrorField && typeof firstErrorField.focus === 'function') {
      setTimeout(() => {
        firstErrorField.focus();
        firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }, 100);
    }
  }

  openButtons.forEach((button) => {
    button.addEventListener('click', function () {
      toggleModal(button.dataset.openModal, true);
    });
  });

  closeButtons.forEach((button) => {
    button.addEventListener('click', function () {
      toggleModal(button.dataset.closeModal, false);
    });
  });

  document.querySelectorAll('.builder-modal-backdrop').forEach((backdrop) => {
    backdrop.addEventListener('click', function (event) {
      if (event.target === backdrop) {
        backdrop.classList.remove('is-open');
        backdrop.setAttribute('aria-hidden', 'true');
      }
    });
  });

  const transportRadios = document.querySelectorAll('.transport-mini-radio');
  const transportCards = document.querySelectorAll('.transport-card-mini');
  const detailFields = document.getElementById('transport-detail-fields');
  const accommodationTypeInput = document.getElementById('trip-accommodation-type');
  const accommodationIdInput = document.getElementById('trip-accommodation-id');
  const accommodationFilters = document.querySelectorAll('.trip-accommodation-filter');
  const accommodationGroups = document.querySelectorAll('.trip-accommodation-group');
  const accommodationPickButtons = document.querySelectorAll('.trip-accommodation-pick');
  const accommodationRadios = document.querySelectorAll('.trip-accommodation-radio');
  const culinaryPackageInput = document.getElementById('trip-culinary-package-id');
  const culinaryFilters = document.querySelectorAll('.trip-culinary-filter');
  const culinaryGroups = document.querySelectorAll('.trip-culinary-group');
  const culinaryPickButtons = document.querySelectorAll('.trip-culinary-pick');
  const culinaryRadios = document.querySelectorAll('.trip-culinary-radio');
  const destinationCheckboxes = document.querySelectorAll('.trip-destination-checkbox');
  const destinationCountBadge = document.getElementById('trip-destination-count');

  function syncTransportMiniState() {
    const active = document.querySelector('.transport-mini-radio:checked')?.value || 'none';

    transportCards.forEach((card) => {
      card.classList.toggle('is-active', card.dataset.transportMini === active);
    });

    if (detailFields) {
      detailFields.style.display = active === 'none' ? 'none' : 'block';
    }
  }

  transportRadios.forEach((radio) => radio.addEventListener('change', syncTransportMiniState));
  syncTransportMiniState();

  function syncAccommodationState() {
    const activeType = accommodationTypeInput?.value || 'hotel';
    const activeAccommodationId = accommodationIdInput?.value || '';

    accommodationFilters.forEach((button) => {
      button.classList.toggle('active', button.dataset.accommodationType === activeType);
    });

    accommodationGroups.forEach((group) => {
      group.style.display = group.dataset.accommodationGroup === activeType ? 'block' : 'none';
    });

    accommodationPickButtons.forEach((button) => {
      const card = button.closest('.catalog-card');
      const radio = card?.querySelector('.trip-accommodation-radio');
      const selected = radio?.value === activeAccommodationId;
      if (card) {
        card.classList.toggle('is-active', selected);
      }
      button.textContent = selected ? 'Penginapan Dipilih' : 'Pilih Penginapan';
    });
  }

  accommodationFilters.forEach((button) => {
    button.addEventListener('click', function () {
      if (accommodationTypeInput) {
        accommodationTypeInput.value = button.dataset.accommodationType || 'hotel';
      }
      if (button.dataset.accommodationType === 'none' && accommodationIdInput) {
        accommodationIdInput.value = '';
      }
      syncAccommodationState();
    });
  });

  accommodationPickButtons.forEach((button) => {
    button.addEventListener('click', function () {
      const accommodationId = button.dataset.accommodationPick || '';
      const card = button.closest('.catalog-card');
      const radio = card?.querySelector('.trip-accommodation-radio');
      if (accommodationIdInput) {
        accommodationIdInput.value = accommodationId;
      }
      if (accommodationTypeInput && button.dataset.accommodationType) {
        accommodationTypeInput.value = button.dataset.accommodationType;
      }
      if (radio) {
        radio.checked = true;
      }
      syncAccommodationState();
    });
  });

  accommodationRadios.forEach((radio) => {
    radio.addEventListener('change', function () {
      if (accommodationIdInput) {
        accommodationIdInput.value = radio.value || '';
      }
      const group = radio.closest('.trip-accommodation-group');
      if (group && accommodationTypeInput) {
        accommodationTypeInput.value = group.dataset.accommodationGroup || accommodationTypeInput.value;
      }
      syncAccommodationState();
    });
  });

  function syncCulinaryState() {
    const activePackageId = culinaryPackageInput?.value || '';

    // Default: tampilkan semua group kuliner saat modal dibuka.
    culinaryGroups.forEach((group) => {
      if (!group.style.display || group.style.display === 'none') {
        group.style.display = 'block';
      }
    });

    culinaryPickButtons.forEach((button) => {
      const card = button.closest('.catalog-card');
      const radio = card?.querySelector('.trip-culinary-radio');
      const selected = radio?.value === activePackageId;
      if (card) {
        card.classList.toggle('is-active', selected);
      }
      button.textContent = selected ? 'Paket Dipilih' : 'Pilih Paket';
    });
  }

  culinaryFilters.forEach((button) => {
    button.addEventListener('click', function () {
      const venueFilter = button.dataset.venueFilter || 'all';
      culinaryFilters.forEach((item) => item.classList.toggle('active', item === button));
      culinaryGroups.forEach((group) => {
        const show = venueFilter === 'all' || group.dataset.venueGroup === venueFilter;
        group.style.display = show ? 'block' : 'none';
      });

      syncCulinaryState();
    });
  });

  culinaryPickButtons.forEach((button) => {
    button.addEventListener('click', function () {
      const packageId = button.dataset.culinaryPick || '';
      const card = button.closest('.catalog-card');
      const radio = card?.querySelector('.trip-culinary-radio');
      if (culinaryPackageInput) {
        culinaryPackageInput.value = packageId;
      }
      if (radio) {
        radio.checked = true;
      }
      syncCulinaryState();
    });
  });

  culinaryRadios.forEach((radio) => {
    radio.addEventListener('change', function () {
      if (culinaryPackageInput) {
        culinaryPackageInput.value = radio.value || '';
      }
      syncCulinaryState();
    });
  });

  function syncDestinationState() {
    let selectedCount = 0;

    destinationCheckboxes.forEach((checkbox) => {
      const card = checkbox.closest('.catalog-card');
      const isSelected = !!checkbox?.checked;

      if (isSelected) {
        selectedCount += 1;
      }

      if (card) {
        card.classList.toggle('is-active', isSelected);
      }
    });

    if (destinationCountBadge) {
      destinationCountBadge.textContent = selectedCount + ' dipilih';
    }
  }

  destinationCheckboxes.forEach((checkbox) => {
    checkbox.addEventListener('change', syncDestinationState);
  });

  syncAccommodationState();
  syncCulinaryState();
  syncDestinationState();
});
</script>
@endpush
@endsection
