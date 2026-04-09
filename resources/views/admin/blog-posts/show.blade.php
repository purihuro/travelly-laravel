@extends('admin.layouts.app')
@section('title', $blogPost->title)
@section('page_title', $blogPost->title)
@section('page_description', 'Lihat isi artikel dan metadata publikasinya.')
@section('page_actions')<a class="btn btn-ghost" href="{{ route('admin.blog-posts.edit', $blogPost) }}">Edit</a><a class="btn btn-secondary" href="{{ route('admin.blog-posts.index') }}">Kembali</a>@endsection
@section('content')
<div class="split">
<section class="panel"><div class="panel-body stack"><div><div class="muted">Slug</div><strong>{{ $blogPost->slug }}</strong></div><div><div class="muted">Excerpt</div><div>{{ $blogPost->excerpt ?: '-' }}</div></div><div><div class="muted">Konten</div><div>{{ $blogPost->content ?: '-' }}</div></div><div><div class="muted">Meta Title</div><div>{{ $blogPost->meta_title ?: '-' }}</div></div><div><div class="muted">Meta Description</div><div>{{ $blogPost->meta_description ?: '-' }}</div></div></div></section>
<section class="panel"><div class="panel-body stack"><div><div class="muted">Penulis</div><strong>{{ $blogPost->author_name }}</strong></div><div><div class="muted">Status</div><span class="badge {{ $blogPost->is_published ? 'badge-success' : 'badge-warning' }}">{{ $blogPost->status_label }}</span></div><div><div class="muted">Tanggal Publish</div><strong>{{ $blogPost->published_at?->format('d M Y H:i') ?: '-' }}</strong></div><div><div class="muted">Gambar</div><div>{{ $blogPost->featured_image ?: '-' }}</div></div><div class="actions"><form method="POST" action="{{ route('admin.blog-posts.destroy', $blogPost) }}" onsubmit="return confirm('Hapus artikel ini?');">@csrf @method('DELETE')<button class="btn btn-danger" type="submit">Hapus Artikel</button></form></div></div></section>
</div>
@endsection
