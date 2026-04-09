@extends('admin.layouts.app')
@section('title', 'Tambah Penginapan')
@section('page_title', 'Tambah Penginapan')
@section('page_description', 'Tambahkan hotel, villa, atau homestay baru ke sistem.')
@section('page_actions')
  <a class="btn btn-secondary" href="{{ route('admin.accommodations.index') }}">Kembali</a>
@endsection
@section('content')
<form method="POST" action="{{ route('admin.accommodations.store') }}" enctype="multipart/form-data">
  @csrf
  <section class="panel"><div class="panel-body stack">@include('admin.accommodations.partials.form', ['accommodation' => null])<div class="actions"><button class="btn btn-primary" type="submit">Simpan Penginapan</button></div></div></section>
</form>
@endsection
