@extends('layouts.app')

@section('title', 'Exflore KBB - Kuliner')

@push('styles')
  @include('partials.booking-flow-styles')
  <style>
    .culinary-search-wrap {
      background: linear-gradient(135deg, #f3f8ff 0%, #edf5ff 48%, #f9fcff 100%);
      border: 1px solid #d7e6fb;
      border-radius: 14px;
      padding: 16px;
      margin-bottom: 20px;
      box-shadow: 0 10px 28px rgba(28, 63, 114, 0.08);
    }

    .culinary-search-help {
      margin-top: 6px;
      font-size: 0.85rem;
      color: #4e6688;
    }

    .culinary-venue-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
      gap: 18px;
    }

    .culinary-venue-card {
      border: 1px solid #e3eaf6;
      border-radius: 14px;
      overflow: hidden;
      background: #fff;
      box-shadow: 0 8px 20px rgba(24, 44, 84, 0.06);
      display: flex;
      flex-direction: column;
      min-height: 100%;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .culinary-venue-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 14px 30px rgba(24, 44, 84, 0.12);
    }

    .culinary-venue-card__image {
      height: 170px;
      overflow: hidden;
    }

    .culinary-venue-card__image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.3s ease;
    }

    .culinary-venue-card:hover .culinary-venue-card__image img {
      transform: scale(1.04);
    }

    .culinary-venue-card__body {
      padding: 16px;
      display: flex;
      flex-direction: column;
      gap: 8px;
      flex: 1;
    }

    .culinary-venue-card__name {
      font-size: 1.1rem;
      font-weight: 700;
      margin: 0;
    }

    .culinary-venue-card__meta {
      font-size: 0.9rem;
      color: #59708f;
      margin: 0;
    }

    .culinary-venue-card__chips {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
    }

    .culinary-venue-chip {
      display: inline-block;
      font-size: 0.78rem;
      font-weight: 600;
      border-radius: 999px;
      padding: 4px 9px;
      background: #eef4ff;
      color: #36547d;
    }

    .culinary-venue-card__desc {
      font-size: 0.9rem;
      color: #5b6f88;
      margin: 0;
      min-height: 44px;
    }

    .culinary-empty-search {
      display: none;
      border: 1px dashed #c7d8f2;
      border-radius: 12px;
      background: #f8fbff;
      padding: 18px;
      text-align: center;
      color: #4f6585;
      margin-top: 4px;
    }

    .culinary-empty-search.is-visible {
      display: block;
    }

    .culinary-modal {
      position: fixed;
      inset: 0;
      background: rgba(10, 20, 35, 0.62);
      z-index: 1050;
      display: none;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .culinary-modal.is-open {
      display: flex;
      animation: culinaryModalFade 0.2s ease;
    }

    .culinary-modal__dialog {
      width: 100%;
      max-width: 920px;
      max-height: 90vh;
      overflow: auto;
      background: #fff;
      border-radius: 14px;
      box-shadow: 0 20px 50px rgba(10, 20, 35, 0.28);
      animation: culinaryModalZoom 0.22s ease;
    }

    .culinary-modal__head {
      display: flex;
      justify-content: space-between;
      gap: 12px;
      padding: 16px 18px;
      border-bottom: 1px solid #e4ebf6;
      position: sticky;
      top: 0;
      background: #fff;
      z-index: 2;
    }

    .culinary-modal__body {
      padding: 16px 18px 18px;
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
      gap: 14px;
    }

    .culinary-modal__reservation {
      padding: 14px 18px 18px;
      border-top: 1px solid #e4ebf6;
      background: #f9fbff;
    }

    .culinary-modal__reservation-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 10px;
      margin-bottom: 10px;
    }

    .culinary-modal__reservation label {
      font-size: 0.84rem;
      font-weight: 600;
      color: #3f587d;
      margin-bottom: 5px;
      display: block;
    }

    .culinary-modal__reservation textarea {
      min-height: 72px;
      resize: vertical;
    }

    .culinary-modal__checkout {
      margin-top: 14px;
      border-top: 1px solid #dfebff;
      padding-top: 12px;
    }

    .culinary-modal__checkout.is-focus {
      animation: checkoutFocus 0.7s ease;
    }

    .culinary-modal__summary {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      gap: 8px;
      margin-bottom: 12px;
    }

    .culinary-modal__summary strong {
      display: block;
      font-size: 0.96rem;
      color: #183458;
    }

    .culinary-modal__summary span {
      display: block;
      font-size: 0.8rem;
      color: #5e7596;
    }

    .culinary-package-card {
      border: 1px solid #e3eaf6;
      border-radius: 12px;
      overflow: hidden;
      background: #fff;
      display: flex;
      flex-direction: column;
      transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .culinary-package-card.is-selected {
      border-color: #67b679;
      box-shadow: 0 10px 22px rgba(47, 138, 78, 0.14);
    }

    .culinary-package-card__image {
      height: 145px;
      overflow: hidden;
      position: relative;
    }

    .culinary-package-slide {
      display: none;
      width: 100%;
      height: 100%;
    }

    .culinary-package-slide.is-active {
      display: block;
    }

    .culinary-package-slide img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .culinary-package-slider__nav {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      border: 0;
      width: 30px;
      height: 30px;
      border-radius: 999px;
      background: rgba(13, 25, 42, 0.6);
      color: #fff;
      font-size: 16px;
      line-height: 1;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
    }

    .culinary-package-slider__nav[data-slide="prev"] {
      left: 8px;
    }

    .culinary-package-slider__nav[data-slide="next"] {
      right: 8px;
    }

    .culinary-package-slider__dots {
      position: absolute;
      left: 50%;
      bottom: 8px;
      transform: translateX(-50%);
      display: inline-flex;
      gap: 5px;
      padding: 4px 6px;
      border-radius: 999px;
      background: rgba(10, 20, 35, 0.45);
    }

    .culinary-package-slider__dot {
      width: 7px;
      height: 7px;
      border-radius: 999px;
      background: rgba(255, 255, 255, 0.6);
      border: 0;
      padding: 0;
      cursor: pointer;
    }

    .culinary-package-slider__dot.is-active {
      background: #fff;
    }

    .culinary-package-card__body {
      padding: 12px;
      display: flex;
      flex-direction: column;
      gap: 7px;
      flex: 1;
    }

    .culinary-package-card__title {
      margin: 0;
      font-size: 1rem;
      font-weight: 700;
    }

    .culinary-package-card__meta {
      margin: 0;
      color: #415a7c;
      font-size: 0.88rem;
    }

    .culinary-selected-note {
      margin-bottom: 12px;
      padding: 10px 12px;
      border-radius: 10px;
      background: #eef7ef;
      color: #2e5b3f;
      font-size: 0.9rem;
      border: 1px solid #cde7d2;
    }

    .culinary-qty-note {
      font-size: 0.83rem;
      color: #526986;
      margin-top: 4px;
    }

    @keyframes culinaryModalFade {
      from {
        opacity: 0;
      }
      to {
        opacity: 1;
      }
    }

    @keyframes culinaryModalZoom {
      from {
        opacity: 0;
        transform: translateY(8px) scale(0.98);
      }
      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    @keyframes checkoutFocus {
      0% {
        box-shadow: 0 0 0 0 rgba(100, 177, 118, 0);
      }
      35% {
        box-shadow: 0 0 0 8px rgba(100, 177, 118, 0.22);
      }
      100% {
        box-shadow: 0 0 0 0 rgba(100, 177, 118, 0);
      }
    }

    @media (max-width: 768px) {
      .culinary-modal__body {
        grid-template-columns: 1fr;
      }

      .culinary-modal__reservation-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
@endpush

@section('content')
<section class="catalog-shell">
  <div class="container">
    @php
      $selectedPackageId = (string) old('culinary_package_id', $selectedCulinary['culinary_package_id'] ?? '');
      $selectedPackage = null;

      if ($selectedPackageId !== '') {
        foreach ($culinaryPackages as $packagesByVenue) {
          $selectedPackage = $packagesByVenue->firstWhere('id', (int) $selectedPackageId);
          if ($selectedPackage) {
            break;
          }
        }
      }

      if (! $selectedPackage && $culinaryPackages->isNotEmpty()) {
        $selectedPackage = optional($culinaryPackages->first())->first();
        $selectedPackageId = (string) optional($selectedPackage)->id;
      }

      $currentQty = (int) old('quantity', $selectedCulinary['quantity'] ?? 1);
      $currentDate = old('reservation_date', $selectedCulinary['reservation_date'] ?? '');
      $currentTime = old('arrival_time', $selectedCulinary['arrival_time'] ?? '');
      $currentNotes = old('culinary_notes', $selectedCulinary['culinary_notes'] ?? '');
    @endphp

    <form method="POST" action="{{ route('culinaries.save') }}">
      @csrf
      <div class="catalog-toolbar">
        <div class="catalog-toolbar__copy">
          <span class="booking-kicker">Kuliner</span>
          <h1>Cari rumah makan, pilih paket, lalu checkout</h1>
          <p>Mulai dari cari rumah makan dulu. Klik rumah makan untuk lihat popup paket, pilih paketnya, lalu isi jadwal reservasi.</p>
        </div>
        <div class="catalog-toolbar__actions">
          <a href="{{ route('cart') }}" class="btn btn-primary py-3 px-4">Lihat Keranjang</a>
          <a href="{{ route('trip-builder') }}" class="btn btn-outline-secondary py-3 px-4">Trip Builder</a>
        </div>
      </div>

      <div class="culinary-search-wrap">
        <label for="venue-search" class="mb-2 d-block">Cari rumah makan</label>
        <input id="venue-search" type="text" class="form-control" placeholder="Ketik nama rumah makan atau lokasi...">
        <div class="culinary-search-help">Contoh: Lembang, seafood, atau nama rumah makan.</div>
      </div>

      <input type="hidden" name="culinary_package_id" id="selected_culinary_package_id" value="{{ $selectedPackageId }}">
      <input type="hidden" name="quantity" id="reservation_quantity_input" value="{{ $currentQty }}">
      <input type="hidden" name="reservation_date" id="reservation_date_input" value="{{ $currentDate }}">
      <input type="hidden" name="arrival_time" id="arrival_time_input" value="{{ $currentTime }}">
      <input type="hidden" name="culinary_notes" id="culinary_notes_input" value="{{ $currentNotes }}">

      @if ($culinaryPackages->isEmpty())
        <div class="destination-empty">
          <h3 class="mb-3">Belum ada paket kuliner aktif</h3>
          <p class="mb-0">Admin belum menambahkan paket kuliner. Tambahkan data rumah makan dan paket kuliner dari panel admin terlebih dahulu.</p>
        </div>
      @else
        <div class="culinary-venue-grid" id="culinary-venue-grid">
          @foreach ($culinaryPackages as $venueName => $packages)
            @php
              $venue = $packages->first()->culinaryVenue;
              $venueId = (string) optional($venue)->id;
              $searchKey = strtolower(trim($venueName . ' ' . ($venue?->location ?? '') . ' ' . ($venue?->cuisine_type ?? '')));
            @endphp
            <div class="culinary-venue-card" data-venue-card data-venue-search="{{ $searchKey }}">
              <div class="culinary-venue-card__image">
                <img src="{{ $venue?->image_url ?: asset('assets/images/product-1.jpg') }}" alt="{{ $venueName }}">
              </div>
              <div class="culinary-venue-card__body">
                <h3 class="culinary-venue-card__name">{{ $venueName }}</h3>
                <p class="culinary-venue-card__meta">📍 {{ $venue?->location ?: 'Lokasi tidak tersedia' }}</p>
                <div class="culinary-venue-card__chips">
                  <span class="culinary-venue-chip">{{ $packages->count() }} paket</span>
                  @if($venue?->cuisine_type)
                    <span class="culinary-venue-chip">{{ $venue->cuisine_type }}</span>
                  @endif
                </div>
                <p class="culinary-venue-card__desc">{{ $venue?->description ?: 'Pilih rumah makan ini untuk melihat daftar paket yang tersedia.' }}</p>
                <button type="button" class="btn btn-primary mt-auto" data-open-modal="venue-modal-{{ $venueId }}">Lihat Paket & Harga</button>
              </div>
            </div>

            <div class="culinary-modal" id="venue-modal-{{ $venueId }}" data-culinary-modal>
              <div class="culinary-modal__dialog">
                <div class="culinary-modal__head">
                  <div>
                    <h4 class="mb-1">{{ $venueName }}</h4>
                    <small class="text-muted">{{ $packages->count() }} paket tersedia. Pilih satu paket untuk lanjut reservasi.</small>
                  </div>
                  <button type="button" class="btn btn-outline-secondary" data-close-modal>Tutup</button>
                </div>
                <div class="culinary-modal__body">
                  @foreach ($packages as $package)
                    @php $isSelected = $selectedPackageId !== '' && $selectedPackageId === (string) $package->id; @endphp
                    <div class="culinary-package-card {{ $isSelected ? 'is-selected' : '' }}" data-package-card data-package-id="{{ $package->id }}">
                      <div class="culinary-package-card__image">
                        @php
                          $galleryUrls = $package->galleries->pluck('image_url')->filter()->values();
                          if ($galleryUrls->isEmpty()) {
                            $galleryUrls = collect([$package->image_url ?: asset('assets/images/product-1.jpg')]);
                          }
                        @endphp
                        <div data-package-slider data-slide-count="{{ $galleryUrls->count() }}">
                          @foreach ($galleryUrls as $imageUrl)
                            <div class="culinary-package-slide {{ $loop->first ? 'is-active' : '' }}" data-slide-index="{{ $loop->index }}">
                              <img src="{{ $imageUrl }}" alt="{{ $package->name }} {{ $loop->iteration }}">
                            </div>
                          @endforeach
                          @if ($galleryUrls->count() > 1)
                            <button type="button" class="culinary-package-slider__nav" data-slide="prev" aria-label="Gambar sebelumnya">‹</button>
                            <button type="button" class="culinary-package-slider__nav" data-slide="next" aria-label="Gambar berikutnya">›</button>
                            <div class="culinary-package-slider__dots">
                              @foreach ($galleryUrls as $imageUrl)
                                <button type="button" class="culinary-package-slider__dot {{ $loop->first ? 'is-active' : '' }}" data-dot-index="{{ $loop->index }}" aria-label="Pilih gambar {{ $loop->iteration }}"></button>
                              @endforeach
                            </div>
                          @endif
                        </div>
                      </div>
                      <div class="culinary-package-card__body">
                        <h5 class="culinary-package-card__title">{{ $package->name }}</h5>
                        <p class="culinary-package-card__meta">${{ number_format((float) $package->effective_price, 2) }} / orang</p>
                        <p class="mb-2">{{ $package->description ?: 'Paket makanan pilihan customer.' }}</p>
                        <button
                          type="button"
                          class="btn {{ $isSelected ? 'btn-primary' : 'btn-outline-primary' }} mt-auto"
                          data-select-package
                          data-package-id="{{ $package->id }}"
                          data-package-name="{{ $package->name }}"
                          data-venue-name="{{ $venueName }}"
                        >
                          {{ $isSelected ? 'Paket Dipilih' : 'Pilih Paket Ini' }}
                        </button>
                      </div>
                    </div>
                  @endforeach
                </div>
                <div class="culinary-modal__reservation">
                  <h6 class="mb-2">Detail Reservasi Kuliner</h6>
                  <div class="culinary-modal__reservation-grid">
                    <div>
                      <label>Jumlah Pesanan / Orang</label>
                      <input
                        type="number"
                        class="form-control"
                        min="1"
                        max="100"
                        value="{{ $currentQty }}"
                        data-reservation-proxy
                        data-field="quantity"
                      >
                      <small class="culinary-qty-note" data-qty-hint>Jumlah reservasi minimal 1 orang dan maksimal 100 orang.</small>
                    </div>
                    <div>
                      <label>Tanggal Reservasi</label>
                      <input
                        type="date"
                        class="form-control"
                        value="{{ $currentDate }}"
                        data-reservation-proxy
                        data-field="reservation_date"
                      >
                    </div>
                    <div>
                      <label>Jam Kedatangan</label>
                      <input
                        type="time"
                        class="form-control"
                        value="{{ $currentTime }}"
                        data-reservation-proxy
                        data-field="arrival_time"
                      >
                    </div>
                  </div>
                  <div>
                    <label>Catatan Tambahan</label>
                    <textarea
                      class="form-control"
                      placeholder="Contoh: alergi seafood, request area non-smoking, ulang tahun, dan lain-lain."
                      data-reservation-proxy
                      data-field="culinary_notes"
                    >{{ $currentNotes }}</textarea>
                  </div>

                  <div class="culinary-modal__checkout" data-modal-checkout>
                    <div class="culinary-modal__summary">
                      <div>
                        <strong data-selected-venue-text>{{ $selectedCulinary['venue_name'] ?? ($selectedPackage?->culinaryVenue?->name ?? 'Belum dipilih') }}</strong>
                        <span>rumah makan terpilih</span>
                      </div>
                      <div>
                        <strong data-selected-package-text>{{ $selectedCulinary['name'] ?? ($selectedPackage?->name ?? 'Belum dipilih') }}</strong>
                        <span>paket makanan</span>
                      </div>
                      <div>
                        <strong><span data-selected-qty-text>{{ $currentQty }}</span> orang</strong>
                        <span>jumlah reservasi</span>
                      </div>
                    </div>
                    <div class="catalog-toolbar__actions">
                      <button type="submit" class="btn btn-primary py-3 px-4">Tambah ke Keranjang</button>
                      <a href="{{ route('cart') }}" class="btn btn-outline-secondary py-3 px-4">Buka Keranjang</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>

        <div class="culinary-empty-search" id="culinary_empty_search">
          Rumah makan tidak ditemukan. Coba kata kunci lain atau kosongkan pencarian.
        </div>
      @endif
    </form>
  </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const searchInput = document.getElementById('venue-search');
  const venueCards = document.querySelectorAll('[data-venue-card]');
  const openModalButtons = document.querySelectorAll('[data-open-modal]');
  const modals = document.querySelectorAll('[data-culinary-modal]');
  const closeModalButtons = document.querySelectorAll('[data-close-modal]');
  const packageButtons = document.querySelectorAll('[data-select-package]');
  const hiddenPackageInput = document.getElementById('selected_culinary_package_id');
  const hiddenQtyInput = document.getElementById('reservation_quantity_input');
  const hiddenDateInput = document.getElementById('reservation_date_input');
  const hiddenTimeInput = document.getElementById('arrival_time_input');
  const hiddenNotesInput = document.getElementById('culinary_notes_input');
  const reservationProxies = document.querySelectorAll('[data-reservation-proxy]');
  const qtyHints = document.querySelectorAll('[data-qty-hint]');
  const selectedVenueTexts = document.querySelectorAll('[data-selected-venue-text]');
  const selectedPackageTexts = document.querySelectorAll('[data-selected-package-text]');
  const selectedQtyTexts = document.querySelectorAll('[data-selected-qty-text]');
  const emptySearch = document.getElementById('culinary_empty_search');

  document.querySelectorAll('[data-package-slider]').forEach((slider) => {
    const slides = slider.querySelectorAll('.culinary-package-slide');
    const dots = slider.querySelectorAll('.culinary-package-slider__dot');
    const prevButton = slider.querySelector('[data-slide="prev"]');
    const nextButton = slider.querySelector('[data-slide="next"]');

    if (slides.length <= 1) {
      return;
    }

    let activeIndex = 0;

    const renderSlide = (index) => {
      activeIndex = index;
      slides.forEach((slide, slideIndex) => {
        slide.classList.toggle('is-active', slideIndex === activeIndex);
      });
      dots.forEach((dot, dotIndex) => {
        dot.classList.toggle('is-active', dotIndex === activeIndex);
      });
    };

    prevButton?.addEventListener('click', () => {
      const nextIndex = activeIndex === 0 ? slides.length - 1 : activeIndex - 1;
      renderSlide(nextIndex);
    });

    nextButton?.addEventListener('click', () => {
      const nextIndex = activeIndex === slides.length - 1 ? 0 : activeIndex + 1;
      renderSlide(nextIndex);
    });

    dots.forEach((dot) => {
      dot.addEventListener('click', () => {
        const index = Number(dot.getAttribute('data-dot-index') || 0);
        if (Number.isNaN(index)) {
          return;
        }
        renderSlide(index);
      });
    });
  });

  function syncReservationField(field, value) {
    if (field === 'quantity' && hiddenQtyInput) {
      hiddenQtyInput.value = value;
      selectedQtyTexts.forEach((el) => {
        el.textContent = value || '1';
      });
    }

    if (field === 'reservation_date' && hiddenDateInput) {
      hiddenDateInput.value = value;
    }

    if (field === 'arrival_time' && hiddenTimeInput) {
      hiddenTimeInput.value = value;
    }

    if (field === 'culinary_notes' && hiddenNotesInput) {
      hiddenNotesInput.value = value;
    }

    reservationProxies.forEach((proxy) => {
      if (proxy.getAttribute('data-field') === field && proxy.value !== value) {
        proxy.value = value;
      }
    });
  }

  function updateQtyHint() {
    if (!hiddenQtyInput) {
      return;
    }

    const min = 1;
    const max = 100;

    reservationProxies.forEach((proxy) => {
      if (proxy.getAttribute('data-field') === 'quantity') {
        proxy.min = String(min);
        proxy.max = String(max);
      }
    });

    qtyHints.forEach((hint) => {
      hint.textContent = 'Minimal 1 orang, maksimal 100 orang.';
    });

    let current = Number(hiddenQtyInput.value || 0);
    if (!current || current < min) {
      current = min;
    }
    if (current > max) {
      current = max;
    }

    syncReservationField('quantity', String(current));
  }

  function closeModal(modal) {
    if (!modal) {
      return;
    }
    modal.classList.remove('is-open');
  }

  function closeAllModals() {
    modals.forEach((modal) => closeModal(modal));
  }

  openModalButtons.forEach((button) => {
    button.addEventListener('click', function () {
      const targetId = button.getAttribute('data-open-modal');
      const modal = document.getElementById(targetId || '');
      if (!modal) {
        return;
      }
      modal.classList.add('is-open');
    });
  });

  closeModalButtons.forEach((button) => {
    button.addEventListener('click', function () {
      closeModal(button.closest('[data-culinary-modal]'));
    });
  });

  modals.forEach((modal) => {
    modal.addEventListener('click', function (event) {
      if (event.target === modal) {
        closeModal(modal);
      }
    });
  });

  packageButtons.forEach((button) => {
    button.addEventListener('click', function () {
      const packageId = button.getAttribute('data-package-id') || '';
      const packageName = button.getAttribute('data-package-name') || 'Belum dipilih';
      const venueName = button.getAttribute('data-venue-name') || 'Belum dipilih';

      if (hiddenPackageInput) {
        hiddenPackageInput.value = packageId;
      }

      selectedPackageTexts.forEach((el) => {
        el.textContent = packageName;
      });

      selectedVenueTexts.forEach((el) => {
        el.textContent = venueName;
      });

      packageButtons.forEach((itemButton) => {
        itemButton.classList.remove('btn-primary');
        itemButton.classList.add('btn-outline-primary');
        itemButton.textContent = 'Pilih Paket Ini';
        itemButton.closest('[data-package-card]')?.classList.remove('is-selected');
      });

      button.classList.remove('btn-outline-primary');
      button.classList.add('btn-primary');
      button.textContent = 'Paket Dipilih';
      button.closest('[data-package-card]')?.classList.add('is-selected');

      updateQtyHint();

      const activeModal = button.closest('[data-culinary-modal]');
      const checkoutTarget = activeModal?.querySelector('[data-modal-checkout]');
      if (checkoutTarget) {
        checkoutTarget.classList.remove('is-focus');
        checkoutTarget.scrollIntoView({ behavior: 'smooth', block: 'start' });
        window.setTimeout(function () {
          checkoutTarget.classList.add('is-focus');
        }, 220);
      }
    });
  });

  reservationProxies.forEach((proxy) => {
    proxy.addEventListener('input', function () {
      const field = proxy.getAttribute('data-field') || '';

      if (field === 'quantity') {
        const parsed = Number(proxy.value || 0);
        const safeValue = parsed > 0 ? String(parsed) : '1';
        syncReservationField('quantity', safeValue);
        return;
      }

      syncReservationField(field, proxy.value || '');
    });
  });

  if (searchInput) {
    searchInput.addEventListener('input', function () {
      const keyword = (searchInput.value || '').trim().toLowerCase();
      let visibleCount = 0;
      venueCards.forEach((card) => {
        const haystack = card.getAttribute('data-venue-search') || '';
        const visible = haystack.includes(keyword);
        card.style.display = visible ? '' : 'none';
        if (visible) {
          visibleCount += 1;
        }
      });

      if (emptySearch) {
        emptySearch.classList.toggle('is-visible', visibleCount === 0);
      }
    });
  }

  const selectedButton = document.querySelector('[data-select-package].btn-primary');
  if (selectedButton) {
    updateQtyHint();
  } else {
    syncReservationField('quantity', hiddenQtyInput?.value || '1');
  }
});
</script>
@endpush
@endsection
