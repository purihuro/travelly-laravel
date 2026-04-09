@extends('admin.layouts.app')
@section('title', 'Dashboard Admin')
@section('page_title', 'Dashboard Admin')
@section('page_description', 'Ringkasan cepat aktivitas Exflore KBB, mulai dari konten sampai operasional harian.')
@section('content')
<div class="grid stats">
  <section class="stat-card"><span>Total paket aktif</span><strong>{{ $stats['packages'] }}</strong></section>
  <section class="stat-card"><span>Paket kuliner aktif</span><strong>{{ $stats['active_culinary_packages'] }}</strong></section>
  <section class="stat-card"><span>Booking pending</span><strong>{{ $stats['pending_bookings'] }}</strong></section>
  <section class="stat-card"><span>Pesan belum dibaca</span><strong>{{ $stats['unread_messages'] }}</strong></section>
</div>
<div class="grid stats" style="margin-top: 18px;">
  <section class="stat-card"><span>Hotel aktif</span><strong>{{ $stats['active_hotels'] }}</strong></section>
  <section class="stat-card"><span>Villa aktif</span><strong>{{ $stats['active_villas'] }}</strong></section>
  <section class="stat-card"><span>Homestay aktif</span><strong>{{ $stats['active_homestays'] }}</strong></section>
  <section class="stat-card"><span>Total penginapan aktif</span><strong>{{ $stats['active_hotels'] + $stats['active_villas'] + $stats['active_homestays'] }}</strong></section>
</div>
<div class="split" style="margin-top: 18px; grid-template-columns: 1fr 1fr;">
  <section class="panel"><div class="panel-body"><h2 style="margin-top: 0;">Grafik Booking 7 Hari</h2><div style="display:flex; gap:12px; align-items:flex-end; min-height:220px; margin-top:20px;">@foreach ($bookingChart as $point)<div style="flex:1; text-align:center;"><div style="height: {{ max(14, (int) (($point['count'] / $bookingChartMax) * 170)) }}px; background: linear-gradient(180deg, #2d7a68 0%, #1d4d42 100%); border-radius: 14px 14px 6px 6px;"></div><div style="margin-top:8px; font-weight:600;">{{ $point['count'] }}</div><div class="muted" style="font-size:.84rem;">{{ $point['label'] }}</div></div>@endforeach</div></div></section>
  <section class="panel"><div class="panel-body"><h2 style="margin-top: 0;">Grafik Pesan Masuk 7 Hari</h2><div style="display:flex; gap:12px; align-items:flex-end; min-height:220px; margin-top:20px;">@foreach ($messageChart as $point)<div style="flex:1; text-align:center;"><div style="height: {{ max(14, (int) (($point['count'] / $messageChartMax) * 170)) }}px; background: linear-gradient(180deg, #d48757 0%, #b95d31 100%); border-radius: 14px 14px 6px 6px;"></div><div style="margin-top:8px; font-weight:600;">{{ $point['count'] }}</div><div class="muted" style="font-size:.84rem;">{{ $point['label'] }}</div></div>@endforeach</div></div></section>
</div>
<div class="split" style="margin-top: 18px;">
  <section class="panel"><div class="panel-body"><h2 style="margin-top: 0;">Booking Terbaru</h2><div class="table-wrap"><table><thead><tr><th>Kode</th><th>Pelanggan</th><th>Status</th><th>Total</th></tr></thead><tbody>@forelse ($recentBookings as $booking)<tr><td><a href="{{ route('admin.bookings.show', $booking) }}">{{ $booking->booking_code }}</a></td><td>{{ $booking->customer_full_name }}</td><td>{{ $booking->status_badge }}</td><td>${{ number_format((float) $booking->total_amount, 2) }}</td></tr>@empty<tr><td colspan="4" class="muted">Belum ada data booking.</td></tr>@endforelse</tbody></table></div></div></section>
  <section class="panel"><div class="panel-body"><h2 style="margin-top: 0;">Pesan Kontak Terbaru</h2><div class="stack">@forelse ($recentMessages as $message)<article style="padding-bottom: 14px; border-bottom: 1px solid var(--line);"><strong>{{ $message->full_name }}</strong><div class="muted" style="margin: 6px 0;">{{ $message->email }}</div><div>{{ $message->subject ?: 'Tanpa subjek' }}</div></article>@empty<p class="muted">Belum ada pesan masuk.</p>@endforelse</div></div></section>
</div>
@endsection
