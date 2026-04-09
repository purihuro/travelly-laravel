@extends('admin.layouts.app')
@section('title', 'Edit Paket Kuliner')
@section('page_title', 'Edit Paket Kuliner')
@section('page_description', 'Perbarui data paket makanan untuk layanan kuliner customer.')
@section('page_actions')
  <a class="btn btn-secondary" href="{{ route('admin.culinary-venues.index') }}">Kelola Rumah Makan</a>
  <a class="btn btn-secondary" href="{{ route('admin.culinary-packages.show', $culinaryPackage) }}">Lihat Detail</a>
  <a class="btn btn-ghost" href="{{ route('admin.culinary-packages.index') }}">Kembali</a>
@endsection
@section('content')
<section class="panel"><div class="panel-body"><form method="POST" action="{{ route('admin.culinary-packages.update', $culinaryPackage) }}" enctype="multipart/form-data">@csrf @method('PUT') @include('admin.culinary-packages.partials.form', ['allowInlineVenueCreate' => false])<div class="actions" style="margin-top:18px;"><button class="btn btn-primary" type="submit">Simpan Perubahan</button><a class="btn btn-ghost" href="{{ route('admin.culinary-packages.index') }}">Batal</a></div></form></div></section>
@endsection
