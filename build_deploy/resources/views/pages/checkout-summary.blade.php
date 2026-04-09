@extends('layouts.app')

@section('title', 'Exflore KBB - Invoice Booking')

@section('content')
<div class="hero-wrap hero-bread" style="background-image: url('{{ asset('assets/images/bg_1.jpg') }}');">
	<div class="container">
		<div class="row no-gutters slider-text align-items-center justify-content-center">
			<div class="col-md-9 ftco-animate text-center">
				<p class="breadcrumbs"><span class="mr-2"><a href="{{ route('home') }}">Home</a></span> <span>Invoice</span></p>
				<h1 class="mb-0 bread">Invoice Booking</h1>
			</div>
		</div>
	</div>
</div>

<section class="ftco-section">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-10">
				<div class="cart-detail p-4 p-md-5">
					<div class="d-flex justify-content-between align-items-start mb-4">
						<div>
							<h3 class="billing-heading mb-2">Summary Booking</h3>
							<p class="mb-0">Kode Booking: <strong>{{ $booking->booking_code }}</strong></p>
						</div>
						<a href="{{ route('destinations') }}" class="btn btn-secondary">Belanja Layanan Lagi</a>
					</div>

					<div class="row mb-4">
						<div class="col-md-6">
							<p class="mb-1"><strong>Nama:</strong> {{ $booking->customer_full_name }}</p>
							<p class="mb-1"><strong>Email:</strong> {{ $booking->customer_email }}</p>
							<p class="mb-1"><strong>Phone:</strong> {{ $booking->customer_phone }}</p>
						</div>
						<div class="col-md-6">
							<p class="mb-1"><strong>Status:</strong> {{ $booking->status_badge }}</p>
							<p class="mb-1"><strong>Pembayaran:</strong> {{ $booking->payment_badge }}</p>
							<p class="mb-1"><strong>Metode:</strong> {{ $booking->payment_method }}</p>
						</div>
					</div>

					@if ($booking->items->isNotEmpty())
						<h4 class="mb-3">Paket Wisata</h4>
						<div class="table-wrap">
							<table class="table">
								<thead class="thead-primary">
									<tr>
										<th>Paket</th>
										<th>Harga / orang</th>
										<th>Peserta</th>
										<th>Total</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($booking->items as $item)
										<tr>
											<td>{{ $item->package_title }}</td>
											<td>${{ number_format((float) $item->unit_price, 2) }}</td>
											<td>{{ $item->quantity }}</td>
											<td>{{ $item->formatted_line_total }}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					@endif

					<h4 class="mb-3">Tiket Destinasi</h4>
					<div class="table-wrap">
						<table class="table">
							<thead class="thead-primary">
								<tr>
									<th>Destinasi</th>
									<th>Lokasi</th>
									<th>Harga</th>
									<th>Qty</th>
									<th>Total</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($booking->destinationItems as $item)
									<tr>
										<td>{{ $item->destination_name }}</td>
										<td>{{ $item->location ?: '-' }}</td>
										<td>${{ number_format((float) $item->unit_price, 2) }}</td>
										<td>{{ $item->quantity }}</td>
										<td>${{ number_format((float) $item->line_total, 2) }}</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>

					@if ($booking->accommodation)
						<h4 class="mb-3 mt-4">Penginapan</h4>
						<div class="table-wrap">
							<table class="table">
								<thead class="thead-primary">
									<tr>
										<th>Penginapan</th>
										<th>Tipe</th>
										<th>Lokasi</th>
										<th>Harga</th>
										<th>Total</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>{{ $booking->accommodation->accommodation_name }}</td>
										<td>{{ ucfirst($booking->accommodation->accommodation_type) }}</td>
										<td>{{ $booking->accommodation->location ?: '-' }}</td>
										<td>${{ number_format((float) $booking->accommodation->unit_price, 2) }}</td>
										<td>{{ $booking->accommodation->formatted_line_total }}</td>
									</tr>
								</tbody>
							</table>
						</div>
					@endif

					@if ($booking->transportation)
						<h4 class="mb-3 mt-4">Transportasi</h4>
						<div class="table-wrap">
							<table class="table">
								<thead class="thead-primary">
									<tr>
										<th>Metode</th>
										<th>Lokasi jemput</th>
										<th>Waktu jemput</th>
										<th>Penumpang</th>
										<th>Harga</th>
										<th>Total</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>
											{{ $booking->transportation->transport_label }}
											@if ($booking->transportation->vehicle_detail)
												<br><small>{{ $booking->transportation->vehicle_detail }}</small>
											@endif
										</td>
										<td>{{ $booking->transportation->pickup_location ?: '-' }}</td>
										<td>{{ $booking->transportation->pickup_time?->format('d M Y H:i') ?: '-' }}</td>
										<td>{{ $booking->transportation->passenger_count ?? 1 }} orang</td>
										<td>${{ number_format((float) $booking->transportation->unit_price, 2) }}</td>
										<td>{{ $booking->transportation->formatted_line_total }}</td>
									</tr>
									@if ($booking->transportation->notes)
										<tr>
											<td colspan="6"><strong>Catatan:</strong> {{ $booking->transportation->notes }}</td>
										</tr>
									@endif
								</tbody>
							</table>
						</div>
					@endif

					@if ($booking->culinary)
						<h4 class="mb-3 mt-4">Kuliner</h4>
						<div class="table-wrap">
							<table class="table">
								<thead class="thead-primary">
									<tr>
										<th>Rumah Makan</th>
										<th>Paket</th>
										<th>Reservasi</th>
										<th>Harga / orang</th>
										<th>Jumlah</th>
										<th>Total</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>{{ $booking->culinary->venue_name }}</td>
										<td>{{ $booking->culinary->package_name }}</td>
										<td>{{ $booking->culinary->reservation_date?->format('d M Y') ?: '-' }} {{ $booking->culinary->arrival_time ? \Illuminate\Support\Carbon::parse($booking->culinary->arrival_time)->format('H:i') : '' }}</td>
										<td>${{ number_format((float) $booking->culinary->unit_price, 2) }}</td>
										<td>{{ $booking->culinary->quantity }} orang</td>
										<td>{{ $booking->culinary->formatted_line_total }}</td>
									</tr>
									@if ($booking->culinary->notes)
										<tr>
											<td colspan="6"><strong>Catatan:</strong> {{ $booking->culinary->notes }}</td>
										</tr>
									@endif
								</tbody>
							</table>
						</div>
					@endif

					<div class="row justify-content-end mt-4">
						<div class="col-md-5">
							<p class="d-flex"><span>Subtotal</span><span>${{ number_format((float) $booking->subtotal, 2) }}</span></p>
							<p class="d-flex"><span>Biaya layanan</span><span>${{ number_format((float) $booking->service_fee, 2) }}</span></p>
							<p class="d-flex"><span>Diskon</span><span>${{ number_format((float) $booking->discount_amount, 2) }}</span></p>
							<hr>
							<p class="d-flex total-price"><span>Total</span><span>${{ number_format((float) $booking->total_amount, 2) }}</span></p>
						</div>
					</div>

					<div class="alert alert-info mt-4" style="background:#edf8f1;border:1px solid #cfe8d7;padding:12px 14px;border-radius:10px;">
						Invoice dan summary booking sudah tersedia di halaman ini setelah checkout selesai.
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection
