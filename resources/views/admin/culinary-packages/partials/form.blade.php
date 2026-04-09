@php
  $culinaryPackage = $culinaryPackage ?? null;
  $defaultVenueId = $defaultVenueId ?? null;
  $allowInlineVenueCreate = $allowInlineVenueCreate ?? ($culinaryPackage === null);
@endphp

<div class="form-grid">
  <div class="field">
    <label for="culinary_venue_id">Rumah Makan</label>
    <select id="culinary_venue_id" name="culinary_venue_id">
      <option value="">-- Pilih rumah makan --</option>
      @foreach ($culinaryVenues as $venue)
        <option value="{{ $venue->id }}" {{ (string) old('culinary_venue_id', $culinaryPackage?->culinary_venue_id ?? $defaultVenueId) === (string) $venue->id ? 'selected' : '' }}>{{ $venue->name }}</option>
      @endforeach
    </select>
    <div class="muted">Pilih rumah makan yang sudah ada. Jika belum tersedia, gunakan bagian "Tambah rumah makan baru".</div>
  </div>

  @if ($allowInlineVenueCreate)
    <details class="field-full" style="border:1px solid var(--line); border-radius:12px; padding:12px 14px;">
      <summary style="cursor:pointer; font-weight:700;">Tambah rumah makan baru (opsional)</summary>
      <div class="form-grid" style="margin-top:12px;">
        <div class="field"><label for="venue_name">Nama Rumah Makan Baru</label><input id="venue_name" name="venue_name" type="text" value="{{ old('venue_name') }}" placeholder="Contoh: RM Sari Laut Nusantara"></div>
        <div class="field"><label for="venue_location">Lokasi Rumah Makan Baru</label><input id="venue_location" name="venue_location" type="text" value="{{ old('venue_location') }}" placeholder="Contoh: Malioboro, Yogyakarta"></div>
        <div class="field-full"><label for="venue_description">Deskripsi Rumah Makan Baru (opsional)</label><textarea id="venue_description" name="venue_description">{{ old('venue_description') }}</textarea></div>
      </div>
    </details>
  @endif

  <div class="field"><label for="name">Nama Paket Makanan</label><input id="name" name="name" type="text" value="{{ old('name', $culinaryPackage?->name) }}" required></div>
  <div class="field"><label for="slug">Slug</label><input id="slug" name="slug" type="text" value="{{ old('slug', $culinaryPackage?->slug) }}" required></div>

  <div class="field-full" style="border:1px solid var(--line); border-radius:12px; padding:12px 14px;">
    <label for="gallery_images" style="font-weight:700; margin-bottom:8px; display:block;">Galeri Paket (untuk slideshow popup customer)</label>
    <input id="gallery_images" name="gallery_images[]" type="file" accept="image/png,image/jpeg,image/webp" multiple>
    <div class="muted">Bisa upload beberapa gambar sekaligus. Format JPG, PNG, WEBP. Maks 3MB per gambar.</div>

    @if ($culinaryPackage && $culinaryPackage->galleries->isNotEmpty())
      <div style="margin-top:12px; display:grid; grid-template-columns:repeat(auto-fill,minmax(120px,1fr)); gap:10px;">
        @foreach ($culinaryPackage->galleries as $gallery)
          <label style="border:1px solid var(--line); border-radius:10px; padding:8px; display:flex; flex-direction:column; gap:6px;">
            <img src="{{ $gallery->image_url }}" alt="Gallery {{ $loop->iteration }}" style="width:100%; height:90px; object-fit:cover; border-radius:8px;">
            <span style="font-size:.8rem; color:var(--muted);">Hapus gambar ini</span>
            <input type="checkbox" name="remove_gallery_image_ids[]" value="{{ $gallery->id }}">
          </label>
        @endforeach
      </div>
    @endif
  </div>

  <div class="field"><label for="price_per_person">Harga Dasar per Orang</label><input id="price_per_person" name="price_per_person" type="number" step="0.01" min="0" value="{{ old('price_per_person', $culinaryPackage?->price_per_person) }}" required></div>

  <div class="field-full"><label for="description">Deskripsi</label><textarea id="description" name="description">{{ old('description', $culinaryPackage?->description) }}</textarea></div>

  <label class="field checkbox"><input name="is_active" type="checkbox" value="1" {{ old('is_active', $culinaryPackage?->is_active ?? true) ? 'checked' : '' }}><span>Aktifkan paket di storefront</span></label>
</div>
