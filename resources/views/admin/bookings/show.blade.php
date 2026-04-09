@extends('admin.layouts.app')
@section('title', 'Detail Booking')
@section('page_title', 'Booking ' . $booking->booking_code)
@section('page_description', 'Lihat detail pemesanan, pelanggan, dan item yang dipesan.')
@section('page_actions')<a class="btn btn-ghost" href="{{ route('admin.bookings.edit', $booking) }}">Edit</a><a class="btn btn-secondary" href="{{ route('admin.bookings.index') }}">Kembali</a>@endsection
@section('content')
<div class="split">
	<section class="panel">
		<div class="panel-body stack">
			<div><span class="muted">Pelanggan</span><br><strong>{{ $booking->customer_full_name }}</strong></div>
			<div><span class="muted">Email</span><br>{{ $booking->customer_email }}</div>
			<div><span class="muted">Telepon</span><br>{{ $booking->customer_phone }}</div>
			<div><span class="muted">Alamat</span><br>{{ trim(($booking->address_line_1 ?: '') . ' ' . ($booking->address_line_2 ?: '')) ?: '-' }}</div>
			<div><span class="muted">Kota / Negara</span><br>{{ trim(($booking->city ?: '') . ' ' . ($booking->country ?: '')) ?: '-' }}</div>
			<div><span class="muted">Catatan</span><br>{{ $booking->notes ?: '-' }}</div>
		</div>
	</section>
	<section class="panel">
		<div class="panel-body stack">
			<div><span class="muted">Status Booking</span><br><strong>{{ $booking->status_badge }}</strong></div>
			<div><span class="muted">Status Pembayaran</span><br><strong>{{ $booking->payment_badge }}</strong></div>
			<div><span class="muted">Metode Pembayaran</span><br>{{ $booking->payment_method }}</div>
			<div><span class="muted">Tanggal Berangkat</span><br>{{ $booking->departure_date?->format('d M Y') ?: '-' }}</div>
			<div><span class="muted">Peserta</span><br>{{ $booking->participants }}</div>
			<div><span class="muted">Total</span><br><strong>${{ number_format((float) $booking->total_amount, 2) }}</strong></div>
		</div>
	</section>
</div>

<section class="panel" style="margin-top: 18px;">
	<div class="panel-body">
		<h2 style="margin-top: 0;">Item Paket</h2>
		<div class="table-wrap">
			<table>
				<thead><tr><th>Paket</th><th>Harga</th><th>Qty</th><th>Total</th></tr></thead>
				<tbody>
					@forelse ($booking->items as $item)
						<tr><td>{{ $item->package_title }}</td><td>${{ number_format((float) $item->unit_price, 2) }}</td><td>{{ $item->quantity }}</td><td>{{ $item->formatted_line_total }}</td></tr>
					@empty
						<tr><td colspan="4" class="muted">Belum ada item paket booking.</td></tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
</section>

<section class="panel" style="margin-top: 18px;">
	<div class="panel-body">
		<h2 style="margin-top: 0;">Tiket Destinasi</h2>
		<div class="table-wrap">
			<table>
				<thead><tr><th>Destinasi</th><th>Lokasi</th><th>Harga</th><th>Qty</th><th>Total</th></tr></thead>
				<tbody>
					@forelse ($booking->destinationItems as $item)
						<tr><td>{{ $item->destination_name }}</td><td>{{ $item->location ?: '-' }}</td><td>${{ number_format((float) $item->unit_price, 2) }}</td><td>{{ $item->quantity }}</td><td>${{ number_format((float) $item->line_total, 2) }}</td></tr>
					@empty
						<tr><td colspan="5" class="muted">Belum ada tiket destinasi pada booking ini.</td></tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
</section>

<section class="panel" style="margin-top: 18px;">
	<div class="panel-body">
		<h2 style="margin-top: 0;">Penginapan</h2>
		<div class="table-wrap">
			<table>
				<thead><tr><th>Nama</th><th>Tipe</th><th>Lokasi</th><th>Harga</th><th>Total</th></tr></thead>
				<tbody>
					@if ($booking->accommodation)
						<tr><td>{{ $booking->accommodation->accommodation_name }}</td><td>{{ ucfirst($booking->accommodation->accommodation_type) }}</td><td>{{ $booking->accommodation->location ?: '-' }}</td><td>${{ number_format((float) $booking->accommodation->unit_price, 2) }}</td><td>{{ $booking->accommodation->formatted_line_total }}</td></tr>
					@else
						<tr><td colspan="5" class="muted">Booking ini tidak memakai penginapan.</td></tr>
					@endif
				</tbody>
			</table>
		</div>
	</div>
</section>

<section class="panel" style="margin-top: 18px;">
	<div class="panel-body">
		<h2 style="margin-top: 0;">Transportasi</h2>
		<div class="table-wrap">
			<table>
				<thead><tr><th>Metode</th><th>Lokasi Jemput</th><th>Waktu Jemput</th><th>Penumpang</th><th>Harga</th><th>Total</th></tr></thead>
				<tbody>
					@if ($booking->transportation)
						<tr>
							<td>{{ $booking->transportation->transport_label }}@if ($booking->transportation->vehicle_detail)<br><small>{{ $booking->transportation->vehicle_detail }}</small>@endif</td>
							<td>{{ $booking->transportation->pickup_location ?: '-' }}</td>
							<td>{{ $booking->transportation->pickup_time?->format('d M Y H:i') ?: '-' }}</td>
							<td>{{ $booking->transportation->passenger_count ?? 1 }} orang</td>
							<td>${{ number_format((float) $booking->transportation->unit_price, 2) }}</td>
							<td>{{ $booking->transportation->formatted_line_total }}</td>
						</tr>
						@if ($booking->transportation->notes)
							<tr><td colspan="6"><strong>Catatan:</strong> {{ $booking->transportation->notes }}</td></tr>
						@endif
					@else
						<tr><td colspan="6" class="muted">Booking ini tidak memakai transportasi.</td></tr>
					@endif
				</tbody>
			</table>
		</div>
	</div>
</section>

<section class="panel" style="margin-top: 18px;">
	<div class="panel-body">
		<h2 style="margin-top: 0;">Kuliner</h2>
		<div class="table-wrap">
			<table>
				<thead><tr><th>Rumah Makan</th><th>Paket</th><th>Reservasi</th><th>Harga / orang</th><th>Jumlah</th><th>Total</th></tr></thead>
				<tbody>
					@if ($booking->culinary)
						<tr>
							<td>{{ $booking->culinary->venue_name }}</td>
							<td>{{ $booking->culinary->package_name }}</td>
							<td>{{ $booking->culinary->reservation_date?->format('d M Y') ?: '-' }} {{ $booking->culinary->arrival_time ? \Illuminate\Support\Carbon::parse($booking->culinary->arrival_time)->format('H:i') : '' }}</td>
							<td>${{ number_format((float) $booking->culinary->unit_price, 2) }}</td>
							<td>{{ $booking->culinary->quantity }} orang</td>
							  <td>{{ $booking->culinary->formatted_line_total }}</td>
						</tr>
						@if ($booking->culinary->notes)
							<tr><td colspan="6"><strong>Catatan:</strong> {{ $booking->culinary->notes }}</td></tr>
						@endif
					@else
						<tr><td colspan="6" class="muted">Booking ini tidak memakai layanan kuliner.</td></tr>
					@endif
				</tbody>
			</table>
		</div>
	</div>
</section>
@endsection
