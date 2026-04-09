@extends('admin.layouts.app')
@section('title', 'Tambah Artikel')
@section('page_title', 'Tambah Artikel')
@section('page_description', 'Buat artikel baru untuk halaman blog Exflore KBB.')
@section('page_actions')<a class="btn btn-ghost" href="{{ route('admin.blog-posts.index') }}">Kembali</a>@endsection
@section('content')
<section class="panel"><div class="panel-body"><form method="POST" action="{{ route('admin.blog-posts.store') }}" class="stack" enctype="multipart/form-data">@csrf @include('admin.blog-posts.partials.form', ['blogPost' => null])<div class="actions"><button class="btn btn-primary" type="submit">Simpan Artikel</button></div></form></div></section>
@endsection
