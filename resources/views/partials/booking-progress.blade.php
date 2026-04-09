@php
  $steps = [
      'destinations' => ['number' => '01', 'label' => 'Destinasi', 'desc' => 'Pilih tiket wisata dan jumlah peserta.'],
      'accommodations' => ['number' => '02', 'label' => 'Penginapan', 'desc' => 'Tentukan hotel, villa, atau homestay.'],
      'transportations' => ['number' => '03', 'label' => 'Transportasi', 'desc' => 'Atur kendaraan, titik jemput, dan waktu.'],
      'culinaries' => ['number' => '04', 'label' => 'Kuliner', 'desc' => 'Pilih rumah makan, paket makanan, dan jadwal reservasi.'],
      'shop' => ['number' => '05', 'label' => 'Ringkasan', 'desc' => 'Cek layanan terpilih dan lanjutkan pembayaran.'],
  ];
  $order = array_keys($steps);
  $currentIndex = array_search($currentStep, $order, true);
@endphp

<div class="booking-progress">
  @foreach ($steps as $key => $step)
    @php
      $index = array_search($key, $order, true);
      $classes = 'booking-progress__item';
      if ($key === $currentStep) {
          $classes .= ' is-active';
      } elseif ($index < $currentIndex) {
          $classes .= ' is-complete';
      }
    @endphp
    <div class="{{ $classes }}">
      <span class="booking-progress__badge">{{ $step['number'] }}</span>
      <div>
        <span class="booking-progress__eyebrow">Step {{ $step['number'] }}</span>
        <span class="booking-progress__title">{{ $step['label'] }}</span>
        <p class="booking-progress__desc">{{ $step['desc'] }}</p>
      </div>
    </div>
  @endforeach
</div>
