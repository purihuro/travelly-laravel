@extends('admin.layouts.app')
@section('title', 'Tambah Paket Wisata')
@section('page_title', 'Tambah Paket Wisata')
@section('page_description', 'Buat paket baru untuk ditampilkan di halaman katalog.')
@section('page_actions')<a class="btn btn-ghost" href="{{ route('admin.travel-packages.index') }}">Kembali</a>@endsection
@section('content')
<section class="panel"><div class="panel-body"><form method="POST" action="{{ route('admin.travel-packages.store') }}" class="stack" enctype="multipart/form-data">@csrf @include('admin.travel-packages.partials.form', ['travelPackage' => null])<div class="actions"><button class="btn btn-primary" type="submit">Simpan Paket</button></div></form></div></section>
@endsection
