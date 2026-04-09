@extends('admin.layouts.app')
@section('title', 'Edit Penginapan')
@section('page_title', 'Edit Penginapan')
@section('page_description', 'Perbarui detail penginapan yang tampil di pilihan customer.')
@section('page_actions')
  <a class="btn btn-secondary" href="{{ route('admin.accommodations.show', $accommodation) }}">Lihat Detail</a>
  <a class="btn btn-ghost" href="{{ route('admin.accommodations.index') }}">Kembali</a>
@endsection
@section('content')
<form method="POST" action="{{ route('admin.accommodations.update', $accommodation) }}" enctype="multipart/form-data">
  @csrf
  @method('PUT')
  <section class="panel"><div class="panel-body stack">@include('admin.accommodations.partials.form', ['accommodation' => $accommodation])<div class="actions"><button class="btn btn-primary" type="submit">Update Penginapan</button></div></div></section>
</form>
@endsection
