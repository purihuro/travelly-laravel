<aside class="booking-summary">
  <div class="booking-summary__section">
    <span class="booking-kicker">Ringkasan Pilihan</span>
    <p class="booking-lead">Customer bebas memilih hanya satu layanan atau menggabungkan semuanya. Ringkasan ini selalu ikut berubah sesuai pilihan terbaru.</p>
  </div>

  <div class="booking-summary__section">
    <p class="booking-summary__label">Tiket Destinasi</p>
    @forelse ($selectedTickets as $ticket)
      <div class="booking-summary__item">
        <div>
          <strong>{{ $ticket['name'] }}</strong><br>
          <small>{{ $ticket['quantity'] }} tiket</small>
        </div>
        <span>${{ number_format($ticket['price'] * $ticket['quantity'], 2) }}</span>
      </div>
    @empty
      <p class="booking-summary__empty">Belum ada tiket wisata yang dipilih.</p>
    @endforelse
  </div>

  <div class="booking-summary__section">
    <p class="booking-summary__label">Penginapan</p>
    @if ($selectedAccommodation)
      <div class="booking-summary__item">
        <div>
          <strong>{{ $selectedAccommodation['name'] }}</strong><br>
          <small>{{ ucfirst($selectedAccommodation['accommodation_type']) }}</small>
        </div>
        <span>${{ number_format($selectedAccommodation['price'], 2) }}</span>
      </div>
    @else
      <p class="booking-summary__empty">Belum ada penginapan yang dipilih.</p>
    @endif
  </div>

  <div class="booking-summary__section">
    <p class="booking-summary__label">Transportasi</p>
    @if ($selectedTransportation)
      <div class="booking-summary__item">
        <div>
          <strong>{{ $selectedTransportation['name'] }}</strong><br>
          <small>{{ $selectedTransportation['pickup_location'] ?? 'Titik jemput belum diisi' }}</small>
        </div>
        <span>${{ number_format($selectedTransportation['price'], 2) }}</span>
      </div>
    @else
      <p class="booking-summary__empty">Belum ada transportasi yang dipilih.</p>
    @endif
  </div>

  <div class="booking-summary__section">
    <p class="booking-summary__label">Kuliner</p>
    @if ($selectedCulinary)
      <div class="booking-summary__item">
        <div>
          <strong>{{ $selectedCulinary['name'] }}</strong><br>
          <small>{{ $selectedCulinary['venue_name'] ?? 'Rumah makan' }} · {{ $selectedCulinary['reservation_date'] ?? '-' }} {{ $selectedCulinary['arrival_time'] ?? '' }}</small>
        </div>
        <span>${{ number_format($selectedCulinary['price'] * $selectedCulinary['quantity'], 2) }}</span>
      </div>
    @else
      <p class="booking-summary__empty">Belum ada reservasi kuliner yang dipilih.</p>
    @endif
  </div>

  <div class="booking-summary__total">
    <span>Total sementara</span>
    <span>${{ number_format($summaryTotals['total'] ?? 0, 2) }}</span>
  </div>
</aside>
