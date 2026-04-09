@extends('admin.layouts.app')
@section('title', 'Edit Artikel')
@section('page_title', 'Edit Artikel')
@section('page_description', 'Perbarui artikel dan status publikasinya.')
@section('page_actions')<a class="btn btn-secondary" href="{{ route('admin.blog-posts.show', $blogPost) }}">Lihat Detail</a><a class="btn btn-ghost" href="{{ route('admin.blog-posts.index') }}">Kembali</a>@endsection
@section('content')
<section class="panel"><div class="panel-body"><form method="POST" action="{{ route('admin.blog-posts.update', $blogPost) }}" class="stack" enctype="multipart/form-data">@csrf @method('PUT') @include('admin.blog-posts.partials.form', ['blogPost' => $blogPost])<div class="actions"><button class="btn btn-primary" type="submit">Update Artikel</button></div></form></div></section>
@endsection
