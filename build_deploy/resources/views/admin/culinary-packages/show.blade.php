@extends('admin.layouts.app')

@section('title', 'Detail Paket Kuliner')
@section('page_title', $culinaryPackage->name)
@section('page_description', 'Lihat detail paket makanan, komposisi menu, dan histori penggunaannya pada booking.')

@section('page_actions')
  <a class="btn btn-ghost" href="{{ route('admin.culinary-packages.edit', $culinaryPackage) }}">Edit</a>
  <a class="btn btn-secondary" href="{{ route('admin.culinary-packages.index') }}">Kembali</a>
@endsection

@section('content')
<div class="split">
  <section class="panel">
    <div class="panel-body stack">
      <div><span class="muted">Nama Paket</span><br><strong>{{ $culinaryPackage->name }}</strong></div>
      <div><span class="muted">Slug</span><br>{{ $culinaryPackage->slug }}</div>
      <div><span class="muted">Rumah Makan</span><br>{{ $culinaryPackage->culinaryVenue?->name ?: '-' }}</div>
      <div><span class="muted">Harga Dasar / orang</span><br>${{ number_format((float) $culinaryPackage->price_per_person, 2) }}</div>
      <div><span class="muted">Harga Efektif / orang</span><br><strong>${{ number_format((float) $culinaryPackage->effective_price, 2) }}</strong></div>
      <div><span class="muted">Deskripsi</span><br>{{ $culinaryPackage->description ?: '-' }}</div>
    </div>
  </section>

  <section class="panel">
    <div class="panel-body stack">
      <div><span class="muted">Status</span><br><span class="badge {{ $culinaryPackage->is_active ? 'badge-success' : 'badge-neutral' }}">{{ $culinaryPackage->is_active ? 'Aktif' : 'Nonaktif' }}</span></div>
      <div><span class="muted">Ketersediaan</span><br>{{ ucfirst(str_replace('_', ' ', (string) $culinaryPackage->availability_status)) }}</div>
      <div><span class="muted">Periode</span><br>{{ optional($culinaryPackage->availability_from)->format('d M Y') ?: '-' }} s/d {{ optional($culinaryPackage->availability_to)->format('d M Y') ?: '-' }}</div>
      <div><span class="muted">Booking</span><br>{{ (int) ($culinaryPackage->current_bookings ?? 0) }} / {{ $culinaryPackage->max_bookings ?: 'Tanpa batas' }}</div>
      <div><span class="muted">Dipakai di Booking</span><br>{{ $culinaryPackage->booking_culinaries_count }} kali</div>
    </div>
  </section>
</div>


<section class="panel" style="margin-top: 18px;">
  <div class="panel-body">
    <h2 style="margin-top: 0;">Riwayat Booking Terbaru</h2>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Kode Booking</th>
            <th>Rumah Makan</th>
            <th>Tanggal</th>
            <th>Jumlah</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($culinaryPackage->bookingCulinaries as $item)
            <tr>
              <td>{{ $item->booking?->booking_code ?: '-' }}</td>
              <td>{{ $item->venue_name }}</td>
              <td>{{ $item->reservation_date?->format('d M Y') ?: '-' }} {{ $item->arrival_time ? \Illuminate\Support\Carbon::parse($item->arrival_time)->format('H:i') : '' }}</td>
              <td>{{ $item->quantity }} orang</td>
              <td>${{ number_format((float) $item->line_total, 2) }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="muted">Belum ada histori booking kuliner.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</section>
@endsection
