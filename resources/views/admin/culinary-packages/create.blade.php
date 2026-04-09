@extends('admin.layouts.app')
@section('title', 'Tambah Paket Kuliner')
@section('page_title', 'Tambah Paket Kuliner')
@section('page_description', 'Tambahkan paket makanan baru untuk reservasi kuliner customer.')
@section('page_actions')
  <a class="btn btn-secondary" href="{{ route('admin.culinary-venues.index') }}">Kelola Rumah Makan</a>
  <a class="btn btn-secondary" href="{{ route('admin.culinary-packages.index') }}">Kembali</a>
@endsection
@section('content')
<section class="panel"><div class="panel-body"><form method="POST" action="{{ route('admin.culinary-packages.store') }}" enctype="multipart/form-data">@csrf @include('admin.culinary-packages.partials.form')<div class="actions" style="margin-top:18px;"><button class="btn btn-primary" type="submit">Simpan Paket</button><a class="btn btn-ghost" href="{{ route('admin.culinary-packages.index') }}">Batal</a></div></form></div></section>
@endsection
