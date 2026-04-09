@php($culinaryVenue = $culinaryVenue ?? null)

<div class="form-grid">
  <div class="field"><label for="name">Nama Rumah Makan</label><input id="name" name="name" type="text" value="{{ old('name', $culinaryVenue?->name) }}" required></div>
  <div class="field"><label for="slug">Slug</label><input id="slug" name="slug" type="text" value="{{ old('slug', $culinaryVenue?->slug) }}" required></div>
  <div class="field"><label for="location">Lokasi</label><input id="location" name="location" type="text" value="{{ old('location', $culinaryVenue?->location) }}"></div>
  <div class="field"><label for="phone">Nomor Telepon</label><input id="phone" name="phone" type="tel" value="{{ old('phone', $culinaryVenue?->phone) }}"></div>
  <div class="field"><label for="website">Website</label><input id="website" name="website" type="url" value="{{ old('website', $culinaryVenue?->website) }}"></div>
  <div class="field"><label for="cuisine_type">Tipe Masakan</label><input id="cuisine_type" name="cuisine_type" type="text" placeholder="Contoh: Jawa, Seafood, Fusion" value="{{ old('cuisine_type', $culinaryVenue?->cuisine_type) }}"></div>
  <div class="field"><label for="opening_time">Jam Buka</label><input id="opening_time" name="opening_time" type="time" value="{{ old('opening_time', $culinaryVenue?->opening_time) }}"></div>
  <div class="field"><label for="closing_time">Jam Tutup</label><input id="closing_time" name="closing_time" type="time" value="{{ old('closing_time', $culinaryVenue?->closing_time) }}"></div>
  <div class="field-full"><label for="image_upload">Upload Gambar</label><input id="image_upload" name="image_upload" type="file" accept="image/png,image/jpeg,image/webp"><div class="muted">Maksimal 2MB. Format JPG, PNG, atau WEBP.</div><div style="margin-top:10px;"><img id="culinary-venue-preview" src="{{ $culinaryVenue?->image_url ?: '' }}" alt="Preview gambar rumah makan" style="{{ $culinaryVenue?->image_url ? '' : 'display:none;' }} width:120px; height:120px; object-fit:cover; border-radius:14px; border:1px solid var(--line);"></div>@if ($culinaryVenue?->image_url)<label class="field checkbox" style="margin-top:10px;"><input name="remove_image" type="checkbox" value="1"><span>Hapus gambar lama</span></label>@endif</div>
  <div class="field-full"><label for="description">Deskripsi</label><textarea id="description" name="description">{{ old('description', $culinaryVenue?->description) }}</textarea></div>
  <label class="field checkbox"><input name="is_active" type="checkbox" value="1" {{ old('is_active', $culinaryVenue?->is_active ?? true) ? 'checked' : '' }}><span>Aktifkan rumah makan di storefront</span></label>
</div>
<script>
  (() => {
    const fileInput = document.getElementById('image_upload');
    const preview = document.getElementById('culinary-venue-preview');
    if (!fileInput || !preview) return;

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
        showPreview('');
        return;
      }

      const reader = new FileReader();
      reader.onload = (loadEvent) => showPreview(loadEvent.target?.result || '');
      reader.readAsDataURL(file);
    });

  })();
</script>
