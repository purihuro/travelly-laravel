@php($accommodation = $accommodation ?? null)

<div class="form-grid">
  <div class="field"><label for="name">Nama Penginapan</label><input id="name" name="name" type="text" value="{{ old('name', $accommodation?->name) }}" required></div>
  <div class="field"><label for="slug">Slug</label><input id="slug" name="slug" type="text" value="{{ old('slug', $accommodation?->slug) }}" required></div>
  <div class="field"><label for="type">Tipe</label><select id="type" name="type" required><option value="hotel" {{ old('type', $accommodation?->type) === 'hotel' ? 'selected' : '' }}>Hotel</option><option value="villa" {{ old('type', $accommodation?->type) === 'villa' ? 'selected' : '' }}>Villa</option><option value="homestay" {{ old('type', $accommodation?->type) === 'homestay' ? 'selected' : '' }}>Homestay</option></select></div>
  <div class="field"><label for="room_type">Tipe kamar / unit</label><input id="room_type" name="room_type" type="text" value="{{ old('room_type', $accommodation?->room_type) }}" placeholder="Contoh: Deluxe Room / Family Villa"></div>
  <div class="field"><label for="location">Lokasi</label><input id="location" name="location" type="text" value="{{ old('location', $accommodation?->location) }}"></div>
  <div class="field"><label for="highlight">Highlight singkat</label><input id="highlight" name="highlight" type="text" value="{{ old('highlight', $accommodation?->highlight) }}" placeholder="Contoh: Dekat pusat kota / private pool"></div>
  <div class="field"><label for="price_per_night">Harga per malam</label><input id="price_per_night" name="price_per_night" type="number" step="0.01" min="0" value="{{ old('price_per_night', $accommodation?->price_per_night) }}" required></div>
  <div class="field"><label for="capacity">Kapasitas</label><input id="capacity" name="capacity" type="number" min="1" max="50" value="{{ old('capacity', $accommodation?->capacity ?? 2) }}" required></div>
  <div class="field-full"><label for="image_upload">Upload Gambar</label><input id="image_upload" name="image_upload" type="file" accept="image/png,image/jpeg,image/webp"><div class="muted">Maksimal 2MB. Format JPG, PNG, atau WEBP.</div><div style="margin-top:10px;"><img id="accommodation-preview" src="{{ old('image', $accommodation?->image_url ?: '') }}" alt="Preview gambar penginapan" style="{{ old('image', $accommodation?->image_url ?: '') ? '' : 'display:none;' }} width:120px; height:120px; object-fit:cover; border-radius:14px; border:1px solid var(--line);"></div>@if ($accommodation?->image_url)<label class="field checkbox" style="margin-top:10px;"><input name="remove_image" type="checkbox" value="1"><span>Hapus gambar lama</span></label>@endif</div>
  <div class="field-full"><label for="image">Atau URL Gambar</label><input id="image" name="image" type="text" value="{{ old('image', $accommodation?->image) }}"></div>
  <div class="field-full"><label for="amenities">Fasilitas utama</label><input id="amenities" name="amenities" type="text" value="{{ old('amenities', $accommodation?->amenities) }}" placeholder="Pisahkan dengan koma, contoh: WiFi, Breakfast, AC, Pool"></div>
  <div class="field-full"><label for="description">Deskripsi</label><textarea id="description" name="description">{{ old('description', $accommodation?->description) }}</textarea></div>
  <label class="field checkbox"><input name="is_active" type="checkbox" value="1" {{ old('is_active', $accommodation?->is_active ?? true) ? 'checked' : '' }}><span>Aktifkan penginapan di storefront</span></label>
</div>
<script>
  (() => {
    const fileInput = document.getElementById('image_upload');
    const urlInput = document.getElementById('image');
    const preview = document.getElementById('accommodation-preview');
    if (!fileInput || !urlInput || !preview) return;

    const showPreview = (src) => {
      if (!src) {
        preview.style.display = 'none';
        preview.removeAttribute('src');
        return;
      }

      preview.src = src;
      preview.style.display = 'block';
    };

    fileInput.addEventListener('change', (event) => {
      const [file] = event.target.files || [];
      if (!file) {
        showPreview(urlInput.value.trim());
        return;
      }

      const reader = new FileReader();
      reader.onload = (loadEvent) => showPreview(loadEvent.target?.result || '');
      reader.readAsDataURL(file);
    });

    urlInput.addEventListener('input', () => {
      if (fileInput.files && fileInput.files.length > 0) return;
      showPreview(urlInput.value.trim());
    });
  })();
</script>
