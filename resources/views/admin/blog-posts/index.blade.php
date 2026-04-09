@extends('admin.layouts.app')
@section('title', 'Blog')
@section('page_title', 'Blog')
@section('page_description', 'Kelola artikel promosi, panduan wisata, dan konten editorial.')
@section('page_actions')<a class="btn btn-primary" href="{{ route('admin.blog-posts.create') }}">Tambah Artikel</a>@endsection
@section('content')
<section class="panel" style="margin-bottom:18px;"><div class="panel-body"><form method="GET" action="{{ route('admin.blog-posts.index') }}" class="form-grid"><div class="field"><label for="search">Cari</label><input id="search" type="text" name="search" value="{{ request('search') }}" placeholder="Judul, slug, penulis"></div><div class="field"><label for="status">Status</label><select id="status" name="status"><option value="">Semua status</option><option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option><option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option></select></div><div class="field" style="justify-content:end;"><label>&nbsp;</label><div class="actions"><button class="btn btn-primary" type="submit">Filter</button><a class="btn btn-ghost" href="{{ route('admin.blog-posts.index') }}">Reset</a></div></div></form></div></section>
<section class="panel"><div class="panel-body"><div class="table-wrap"><table>
<thead><tr><th>Judul</th><th>Penulis</th><th>Status</th><th>Tanggal Publish</th><th>Aksi</th></tr></thead>
<tbody>
@forelse ($posts as $post)
<tr><td><div style="display:flex; gap:12px; align-items:flex-start;">@if ($post->image_url)<img src="{{ $post->image_url }}" alt="{{ $post->title }}" style="width:64px; height:64px; object-fit:cover; border-radius:12px; border:1px solid var(--line);">@endif<div><strong>{{ $post->title }}</strong><div class="muted">/{{ $post->slug }}</div></div></div></td><td>{{ $post->author_name }}</td><td><span class="badge {{ $post->is_published ? 'badge-success' : 'badge-warning' }}">{{ $post->status_label }}</span></td><td>{{ $post->published_at?->format('d M Y H:i') ?: '-' }}</td><td><div class="actions"><a class="btn btn-secondary" href="{{ route('admin.blog-posts.show', $post) }}">Lihat</a><a class="btn btn-ghost" href="{{ route('admin.blog-posts.edit', $post) }}">Edit</a></div></td></tr>
@empty
<tr><td colspan="5" class="muted">Belum ada artikel blog.</td></tr>
@endforelse
</tbody></table></div><div class="pagination">{{ $posts->links() }}</div></div></section>
@endsection
