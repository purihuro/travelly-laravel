@extends('admin.layouts.app')
@section('title', 'Edit Paket Wisata')
@section('page_title', 'Edit Paket Wisata')
@section('page_description', 'Perbarui informasi paket travel secara langsung dari panel admin.')
@section('page_actions')<a class="btn btn-secondary" href="{{ route('admin.travel-packages.show', $travelPackage) }}">Lihat Detail</a><a class="btn btn-ghost" href="{{ route('admin.travel-packages.index') }}">Kembali</a>@endsection
@section('content')
<section class="panel"><div class="panel-body"><form method="POST" action="{{ route('admin.travel-packages.update', $travelPackage) }}" class="stack" enctype="multipart/form-data">@csrf @method('PUT') @include('admin.travel-packages.partials.form', ['travelPackage' => $travelPackage])<div class="actions"><button class="btn btn-primary" type="submit">Update Paket</button></div></form></div></section>
@endsection
