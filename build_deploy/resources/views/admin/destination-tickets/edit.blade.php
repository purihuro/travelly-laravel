@extends('admin.layouts.app')
@section('title', 'Edit Tiket Wisata')
@section('page_title', 'Edit Tiket Wisata')
@section('page_description', 'Perbarui data destinasi tiket wisata yang tampil di halaman customer.')
@section('page_actions')
  <a class="btn btn-secondary" href="{{ route('admin.destination-tickets.show', $destinationTicket) }}">Lihat Detail</a>
  <a class="btn btn-ghost" href="{{ route('admin.destination-tickets.index') }}">Kembali</a>
@endsection
@section('content')
<section class="panel"><div class="panel-body"><form method="POST" action="{{ route('admin.destination-tickets.update', $destinationTicket) }}" enctype="multipart/form-data">@csrf @method('PUT') @include('admin.destination-tickets.partials.form')<div class="actions" style="margin-top:18px;"><button class="btn btn-primary" type="submit">Simpan Perubahan</button><a class="btn btn-ghost" href="{{ route('admin.destination-tickets.index') }}">Batal</a></div></form></div></section>
@endsection
