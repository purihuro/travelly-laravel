@extends('admin.layouts.app')
@section('title', 'Tambah Rumah Makan')
@section('page_title', 'Tambah Rumah Makan')
@section('page_description', 'Tambahkan rumah makan baru agar bisa dipilih customer untuk reservasi kuliner.')
@section('page_actions')
  <a class="btn btn-secondary" href="{{ route('admin.culinary-venues.index') }}">Kembali</a>
@endsection
@section('content')
<section class="panel"><div class="panel-body"><form method="POST" action="{{ route('admin.culinary-venues.store') }}" enctype="multipart/form-data">@csrf @include('admin.culinary-venues.partials.form')<div class="actions" style="margin-top:18px;"><button class="btn btn-primary" type="submit">Simpan Rumah Makan</button><a class="btn btn-ghost" href="{{ route('admin.culinary-venues.index') }}">Batal</a></div></form></div></section>
@endsection
