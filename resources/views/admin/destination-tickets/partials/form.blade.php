@php($destinationTicket = $destinationTicket ?? null)

<div class="form-grid">
  <div class="field"><label for="name">Nama Destinasi</label><input id="name" name="name" type="text" value="{{ old('name', $destinationTicket?->name) }}" required></div>
  <div class="field"><label for="slug">Slug</label><input id="slug" name="slug" type="text" value="{{ old('slug', $destinationTicket?->slug) }}" required></div>
  <div class="field"><label for="location">Lokasi</label><input id="location" name="location" type="text" value="{{ old('location', $destinationTicket?->location) }}"></div>
  <div class="field"><label for="category">Kategori</label><input id="category" name="category" type="text" value="{{ old('category', $destinationTicket?->category) }}" placeholder="Contoh: Alam, Budaya, Panorama"></div>
  <div class="field"><label for="price">Harga tiket</label><input id="price" name="price" type="number" step="0.01" min="0" value="{{ old('price', $destinationTicket?->price) }}" required></div>
  <div class="field"><label for="open_hours">Jam buka</label><input id="open_hours" name="open_hours" type="text" value="{{ old('open_hours', $destinationTicket?->open_hours) }}" placeholder="Contoh: 08.00 - 17.00"></div>
  <div class="field"><label for="duration_minutes">Estimasi durasi (menit)</label><input id="duration_minutes" name="duration_minutes" type="number" min="1" max="1440" value="{{ old('duration_minutes', $destinationTicket?->duration_minutes) }}"></div>
  <div class="field"><label for="audience">Cocok untuk</label><input id="audience" name="audience" type="text" value="{{ old('audience', $destinationTicket?->audience) }}" placeholder="Contoh: Keluarga / Couple / Grup"></div>
  <div class="field"><label for="sort_order">Urutan tampil</label><input id="sort_order" name="sort_order" type="number" min="0" max="9999" value="{{ old('sort_order', $destinationTicket?->sort_order ?? 0) }}"></div>
  <label class="field checkbox"><input name="is_active" type="checkbox" value="1" {{ old('is_active', $destinationTicket?->is_active ?? true) ? 'checked' : '' }}><span>Aktifkan destinasi di storefront</span></label>
  <div class="field-full"><label for="image_upload">Upload Gambar</label><input id="image_upload" name="image_upload" type="file" accept="image/png,image/jpeg,image/webp"><div class="muted">Maksimal 2MB. Format JPG, PNG, atau WEBP.</div><div style="margin-top:10px;"><img id="destination-ticket-preview" src="{{ old('image', $destinationTicket?->image_url ?: '') }}" alt="Preview gambar destinasi" style="{{ old('image', $destinationTicket?->image_url ?: '') ? '' : 'display:none;' }} width:120px; height:120px; object-fit:cover; border-radius:14px; border:1px solid var(--line);"></div>@if ($destinationTicket?->image_url)<label class="field checkbox" style="margin-top:10px;"><input name="remove_image" type="checkbox" value="1"><span>Hapus gambar lama</span></label>@endif</div>
  <div class="field-full"><label for="image">Atau URL / nama file gambar</label><input id="image" name="image" type="text" value="{{ old('image', $destinationTicket?->image) }}"></div>
  <div class="field-full"><label for="description">Deskripsi</label><textarea id="description" name="description">{{ old('description', $destinationTicket?->description) }}</textarea></div>
</div>
<script>
  (() => {
    const fileInput = document.getElementById('image_upload');
    const urlInput = document.getElementById('image');
    const preview = document.getElementById('destination-ticket-preview');
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
