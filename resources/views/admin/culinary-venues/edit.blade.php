@extends('admin.layouts.app')
@section('title', 'Kelola Rumah Makan')
@section('page_title', 'Kelola ' . $culinaryVenue->name)
@section('page_description', 'Tambah paket di atas, lalu edit atau delete paket langsung di halaman ini.')
@section('page_actions')
  <a class="btn btn-ghost" href="{{ route('admin.culinary-venues.index') }}">Kembali</a>
@endsection
@section('content')
<section class="panel" style="margin-bottom: 18px;">
  <div class="panel-body" style="display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap;">
    <div>
      <strong>{{ $culinaryVenue->name }}</strong>
      <div class="muted">Status: {{ $culinaryVenue->is_active ? 'Aktif' : 'Nonaktif' }} | Total paket: {{ $culinaryVenue->culinary_packages_count }} | Dipakai booking: {{ $culinaryVenue->booking_culinaries_count }}</div>
    </div>
    <div class="actions">
      <a class="btn btn-primary" href="#quick-package-form">Tambahkan Paket</a>
    </div>
  </div>
</section>

<section class="panel" style="margin-bottom: 18px;">
  <div class="panel-body">
    <div id="quick-package-form"></div>
    <h2 style="margin-top:0;">Tambah Paket</h2>
    <form method="POST" action="{{ route('admin.culinary-packages.store') }}" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="return_venue_id" value="{{ $culinaryVenue->id }}">
      @include('admin.culinary-packages.partials.form', [
        'culinaryPackage' => null,
        'culinaryVenues' => $culinaryVenues,
        'menuItemsByVenue' => $menuItemsByVenue,
        'defaultVenueId' => $defaultVenueId,
        'allowInlineVenueCreate' => false,
      ])
      <div class="actions" style="margin-top:18px;"><button class="btn btn-primary" type="submit">Simpan Paket</button></div>
    </form>
  </div>
</section>

<section class="panel" style="margin-bottom: 18px;">
  <div class="panel-body">
    <h2 style="margin-top:0;">Daftar Paket</h2>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Paket</th>
            <th>Harga / orang</th>
            <th>Gambar</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($culinaryVenue->culinaryPackages as $package)
            <tr>
              <td><strong>{{ $package->name }}</strong><div class="muted">{{ $package->description ? \Illuminate\Support\Str::limit($package->description, 80) : 'Tanpa deskripsi' }}</div></td>
              <td>${{ number_format((float) $package->price_per_person, 2) }}</td>
              <td>
                @if ($package->image_url)
                  <img src="{{ $package->image_url }}" alt="{{ $package->name }}" style="width:68px; height:52px; object-fit:cover; border-radius:8px; border:1px solid var(--line);">
                @else
                  <span class="muted">-</span>
                @endif
              </td>
              <td><span class="badge {{ $package->is_active ? 'badge-success' : 'badge-neutral' }}">{{ $package->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
              <td><div class="actions"><a class="btn btn-secondary" href="{{ route('admin.culinary-packages.edit', $package) }}">Edit</a><form method="POST" action="{{ route('admin.culinary-packages.destroy', $package) }}" onsubmit="return confirm('Hapus paket ini?');" style="display:inline-flex;">@csrf @method('DELETE')<input type="hidden" name="return_venue_id" value="{{ $culinaryVenue->id }}"><button class="btn btn-danger" type="submit">Delete</button></form></div></td>
            </tr>
          @empty
            <tr><td colspan="5" class="muted">Belum ada paket kuliner.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</section>

<section class="panel">
  <div class="panel-body">
    <details>
      <summary style="cursor:pointer; font-weight:700;">Edit Profil Rumah Makan (opsional)</summary>
      <div style="margin-top:14px;">
        <form method="POST" action="{{ route('admin.culinary-venues.update', $culinaryVenue) }}" enctype="multipart/form-data">@csrf @method('PUT') @include('admin.culinary-venues.partials.form')<div class="actions" style="margin-top:18px;"><button class="btn btn-secondary" type="submit">Simpan Profil Rumah Makan</button></div></form>
      </div>
    </details>
  </div>
</section>

@endsection
