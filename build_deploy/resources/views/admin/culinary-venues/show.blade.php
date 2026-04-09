@extends('admin.layouts.app')
@section('title', 'Detail Rumah Makan')
@section('page_title', $culinaryVenue->name)
@section('page_description', 'Rincian rumah makan, rincian paket, dan aksi kelola dalam satu halaman.')
@section('page_actions')
  <a class="btn btn-ghost" href="{{ route('admin.culinary-venues.edit', $culinaryVenue) }}">Edit Rumah Makan</a>
  <a class="btn btn-secondary" href="{{ route('admin.culinary-venues.index') }}">Kembali</a>
@endsection
@section('content')
<section class="panel" style="margin-bottom: 18px;">
  <div class="panel-body" style="display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap;">
    <div>
      <strong>Kelola Paket {{ $culinaryVenue->name }}</strong>
      <div class="muted">Mulai dari tambah paket, lalu edit atau hapus langsung di tabel.</div>
    </div>
    <div class="actions">
      <a class="btn btn-primary" href="#quick-package-form">Tambahkan Paket</a>
    </div>
  </div>
</section>

<div class="split">
  <section class="panel">
    <div class="panel-body stack">
      <h2 style="margin:0;">Rincian Rumah Makan</h2>
      <div><span class="muted">Nama Rumah Makan</span><br><strong>{{ $culinaryVenue->name }}</strong></div>
      <div><span class="muted">Slug</span><br>{{ $culinaryVenue->slug }}</div>
      <div><span class="muted">Lokasi</span><br>{{ $culinaryVenue->location ?: '-' }}</div>
      <div><span class="muted">Deskripsi</span><br>{{ $culinaryVenue->description ?: '-' }}</div>
      <div><span class="muted">Status</span><br><span class="badge {{ $culinaryVenue->is_active ? 'badge-success' : 'badge-neutral' }}">{{ $culinaryVenue->is_active ? 'Aktif' : 'Nonaktif' }}</span></div>
      <div><span class="muted">Total Paket</span><br>{{ $culinaryVenue->culinary_packages_count }} paket</div>
      <div><span class="muted">Dipakai di Booking</span><br>{{ $culinaryVenue->booking_culinaries_count }} kali</div>
    </div>
  </section>

  <section class="panel">
    <div class="panel-body stack">
      @if ($culinaryVenue->image_url)
        <div>
          <span class="muted">Gambar</span><br>
          <img src="{{ $culinaryVenue->image_url }}" alt="{{ $culinaryVenue->name }}" style="margin-top:10px;width:100%;max-width:320px;height:220px;object-fit:cover;border-radius:16px;border:1px solid var(--line);">
        </div>
      @endif

      <div>
        <div id="quick-package-form"></div>
        <h2 style="margin:0 0 10px;">Tambah Paket Cepat</h2>
        <p class="muted" style="margin:0 0 12px;">Form ringkas untuk tambah paket tanpa pindah halaman.</p>
        <form method="POST" action="{{ route('admin.culinary-packages.store') }}" class="form-grid">
          @csrf
          <input type="hidden" name="culinary_venue_id" value="{{ $culinaryVenue->id }}">
          <input type="hidden" name="return_venue_id" value="{{ $culinaryVenue->id }}">

          <div class="field"><label for="quick_name">Nama Paket</label><input id="quick_name" name="name" type="text" value="{{ old('name') }}" required></div>
          <div class="field"><label for="quick_slug">Slug</label><input id="quick_slug" name="slug" type="text" value="{{ old('slug') }}" required></div>
          <div class="field"><label for="quick_price">Harga / orang</label><input id="quick_price" name="price_per_person" type="number" min="0" step="0.01" value="{{ old('price_per_person') }}" required></div>
          <div class="field-full"><label for="quick_description">Deskripsi (opsional)</label><textarea id="quick_description" name="description">{{ old('description') }}</textarea></div>

          <input type="hidden" name="availability_status" value="available">
          <label class="field checkbox"><input name="is_active" type="checkbox" value="1" {{ old('is_active', true) ? 'checked' : '' }}><span>Aktifkan paket</span></label>

          <div class="field-full actions"><button class="btn btn-primary" type="submit">Simpan Paket</button><a class="btn btn-ghost" href="{{ route('admin.culinary-packages.create', ['culinary_venue_id' => $culinaryVenue->id]) }}">Form Lengkap</a></div>
        </form>
      </div>
    </div>
  </section>
</div>

<section class="panel" style="margin-top: 18px;">
  <div class="panel-body">
    <h2 style="margin-top: 0;">Paket Kuliner Terbaru</h2>
    <p class="muted" style="margin:0 0 12px;">Edit cepat harga dan status langsung dari tabel berikut.</p>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Paket</th>
            <th>Harga / orang</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($culinaryVenue->culinaryPackages as $package)
            @php($quickFormId = 'quick-update-package-' . $package->id)
            <tr>
              <td><strong>{{ $package->name }}</strong><div class="muted" style="margin-top:4px;">{{ $package->description ? \Illuminate\Support\Str::limit($package->description, 90) : 'Tanpa deskripsi' }}</div></td>
              <td>
                  <input form="{{ $quickFormId }}" name="price_per_person" type="number" min="0" step="0.01" value="{{ old('price_per_person', $package->price_per_person) }}" style="max-width:130px;">
              </td>
              <td>
                <label class="checkbox" style="display:inline-flex; align-items:center; gap:6px;">
                  <input form="{{ $quickFormId }}" type="checkbox" name="is_active" value="1" {{ $package->is_active ? 'checked' : '' }}>
                  <span>{{ $package->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                </label>
              </td>
              <td>
                <div class="actions">
                  <form id="{{ $quickFormId }}" method="POST" action="{{ route('admin.culinary-packages.quick-update', $package) }}" style="display:none;">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="return_venue_id" value="{{ $culinaryVenue->id }}">
                  </form>
                  <button class="btn btn-primary" type="submit" form="{{ $quickFormId }}">Simpan</button>
                  <a class="btn btn-ghost" href="{{ route('admin.culinary-packages.edit', $package) }}">Lanjut Edit</a>
                  <form method="POST" action="{{ route('admin.culinary-packages.destroy', $package) }}" onsubmit="return confirm('Hapus paket ini?');" style="display:inline-flex;">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="return_venue_id" value="{{ $culinaryVenue->id }}">
                    <button class="btn btn-danger" type="submit">Hapus</button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="muted">Belum ada paket kuliner.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</section>

<section class="panel" style="margin-top: 18px;">
  <div class="panel-body">
    <h2 style="margin-top: 0;">Riwayat Booking Terbaru</h2>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Kode Booking</th>
            <th>Paket</th>
            <th>Tanggal</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($culinaryVenue->bookingCulinaries as $item)
            <tr>
              <td>{{ $item->booking?->booking_code ?: '-' }}</td>
              <td>{{ $item->package_name }}</td>
              <td>{{ $item->reservation_date?->format('d M Y') ?: '-' }} {{ $item->arrival_time ? \Illuminate\Support\Carbon::parse($item->arrival_time)->format('H:i') : '' }}</td>
              <td>${{ number_format((float) $item->line_total, 2) }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="muted">Belum ada histori booking kuliner.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</section>

<script>
  (() => {
    const nameInput = document.getElementById('quick_name');
    const slugInput = document.getElementById('quick_slug');
    if (!nameInput || !slugInput) return;

    const slugify = (value) => value
      .toString()
      .toLowerCase()
      .trim()
      .replace(/[^a-z0-9\s-]/g, '')
      .replace(/\s+/g, '-')
      .replace(/-+/g, '-');

    let editedSlugManually = slugInput.value.trim().length > 0;

    slugInput.addEventListener('input', () => {
      editedSlugManually = true;
    });

    nameInput.addEventListener('input', () => {
      if (editedSlugManually) return;
      slugInput.value = slugify(nameInput.value);
    });
  })();
</script>
@endsection
