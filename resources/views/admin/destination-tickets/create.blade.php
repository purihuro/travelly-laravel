@extends('admin.layouts.app')
@section('title', 'Tambah Tiket Wisata')
@section('page_title', 'Tambah Tiket Wisata')
@section('page_description', 'Tambahkan destinasi tiket wisata baru untuk halaman customer.')
@section('page_actions')
  <a class="btn btn-secondary" href="{{ route('admin.destination-tickets.index') }}">Kembali</a>
@endsection
@section('content')
<section class="panel"><div class="panel-body"><form method="POST" action="{{ route('admin.destination-tickets.store') }}" enctype="multipart/form-data">@csrf @include('admin.destination-tickets.partials.form')<div class="actions" style="margin-top:18px;"><button class="btn btn-primary" type="submit">Simpan Destinasi</button><a class="btn btn-ghost" href="{{ route('admin.destination-tickets.index') }}">Batal</a></div></form></div></section>
@endsection
