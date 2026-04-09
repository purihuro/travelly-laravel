@extends('admin.layouts.app')
@section('title', 'Edit Pesan Kontak')
@section('page_title', 'Edit Pesan Kontak')
@section('page_description', 'Perbarui status tindak lanjut pesan pelanggan.')
@section('page_actions')<a class="btn btn-secondary" href="{{ route('admin.contacts.show', $contactMessage) }}">Lihat Detail</a><a class="btn btn-ghost" href="{{ route('admin.contacts.index') }}">Kembali</a>@endsection
@section('content')
<section class="panel"><div class="panel-body"><form method="POST" action="{{ route('admin.contacts.update', $contactMessage) }}" class="stack">@csrf @method('PUT')<div class="form-grid"><div class="field"><label for="status">Status</label><select id="status" name="status">@foreach (['new', 'read', 'replied', 'archived'] as $status)<option value="{{ $status }}" {{ old('status', $contactMessage->status) === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>@endforeach</select></div><div class="field"><label for="subject">Subjek</label><input id="subject" name="subject" type="text" value="{{ old('subject', $contactMessage->subject) }}"></div><div class="field-full"><label for="message">Pesan</label><textarea id="message" name="message">{{ old('message', $contactMessage->message) }}</textarea></div></div><div class="actions"><button class="btn btn-primary" type="submit">Update Pesan</button></div></form></div></section>
@endsection
