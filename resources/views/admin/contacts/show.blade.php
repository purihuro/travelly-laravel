@extends('admin.layouts.app')
@section('title', 'Detail Pesan Kontak')
@section('page_title', $contactMessage->full_name)
@section('page_description', 'Lihat detail lengkap pesan yang masuk dari halaman kontak.')
@section('page_actions')<a class="btn btn-ghost" href="{{ route('admin.contacts.edit', $contactMessage) }}">Edit Status</a><a class="btn btn-secondary" href="{{ route('admin.contacts.index') }}">Kembali</a>@endsection
@section('content')
<div class="split"><section class="panel"><div class="panel-body stack"><div><span class="muted">Email</span><br>{{ $contactMessage->email }}</div><div><span class="muted">Subjek</span><br>{{ $contactMessage->subject ?: 'Tanpa subjek' }}</div><div><span class="muted">Status</span><br><span class="badge">{{ $contactMessage->status_label }}</span></div><div><span class="muted">Isi Pesan</span><br>{{ $contactMessage->message }}</div></div></section><section class="panel"><div class="panel-body stack"><div><span class="muted">Masuk Pada</span><br>{{ $contactMessage->created_at?->format('d M Y H:i') }}</div><div><span class="muted">Terakhir Diperbarui</span><br>{{ $contactMessage->updated_at?->format('d M Y H:i') }}</div><form method="POST" action="{{ route('admin.contacts.destroy', $contactMessage) }}" onsubmit="return confirm('Hapus pesan ini?');">@csrf @method('DELETE')<button class="btn btn-danger" type="submit">Hapus Pesan</button></form></div></section></div>
@endsection
