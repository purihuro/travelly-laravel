@php($travelPackage = $travelPackage ?? null)

<div class="form-grid">
  <div class="field"><label for="title">Judul Paket</label><input id="title" name="title" type="text" value="{{ old('title', $travelPackage?->title) }}" required></div>
  <div class="field"><label for="slug">Slug</label><input id="slug" name="slug" type="text" value="{{ old('slug', $travelPackage?->slug) }}" required></div>
  <div class="field"><label for="category">Kategori</label><input id="category" name="category" type="text" value="{{ old('category', $travelPackage?->category) }}" required></div>
  <div class="field"><label for="location">Lokasi</label><input id="location" name="location" type="text" value="{{ old('location', $travelPackage?->location) }}"></div>
  <div class="field"><label for="base_price">Harga Dasar</label><input id="base_price" name="base_price" type="number" step="0.01" min="0" value="{{ old('base_price', $travelPackage?->base_price) }}" required></div>
  <div class="field"><label for="sale_price">Harga Promo</label><input id="sale_price" name="sale_price" type="number" step="0.01" min="0" value="{{ old('sale_price', $travelPackage?->sale_price) }}"></div>
  <div class="field"><label for="duration_days">Durasi (hari)</label><input id="duration_days" name="duration_days" type="number" min="1" value="{{ old('duration_days', $travelPackage?->duration_days ?? 1) }}" required></div>
  <div class="field"><label for="quota">Kuota</label><input id="quota" name="quota" type="number" min="0" value="{{ old('quota', $travelPackage?->quota ?? 0) }}" required></div>
  <div class="field-full"><label for="featured_image_upload">Upload Gambar Utama</label><input id="featured_image_upload" name="featured_image_upload" type="file" accept="image/png,image/jpeg,image/webp"><div class="muted">Maksimal 2MB. Format JPG, PNG, atau WEBP.</div><div style="margin-top:10px;"><img id="travel-package-preview" src="{{ old('featured_image') ? old('featured_image') : ($travelPackage?->image_url ?: '') }}" alt="Preview gambar paket" style="{{ old('featured_image') || $travelPackage?->image_url ? '' : 'display:none;' }} width:120px; height:120px; object-fit:cover; border-radius:14px; border:1px solid var(--line);"></div>@if ($travelPackage?->image_url)<label class="field checkbox" style="margin-top:10px;"><input name="remove_featured_image" type="checkbox" value="1"><span>Hapus gambar utama</span></label>@endif</div>
  <div class="field-full"><label for="featured_image">Atau URL Gambar Utama</label><input id="featured_image" name="featured_image" type="text" value="{{ old('featured_image', $travelPackage?->featured_image) }}"></div>
  <div class="field-full"><label for="gallery_images_upload">Upload Galeri Paket</label><input id="gallery_images_upload" name="gallery_images_upload[]" type="file" accept="image/png,image/jpeg,image/webp" multiple><div class="muted">Bisa pilih banyak gambar sekaligus.</div><div id="travel-gallery-preview" style="display:flex; gap:10px; flex-wrap:wrap; margin-top:12px;"></div></div>
  @if ($travelPackage?->galleryImages?->count())
    <div class="field-full"><label>Galeri Tersimpan</label><div style="display:flex; gap:14px; flex-wrap:wrap;">@foreach ($travelPackage->galleryImages as $galleryImage)<label style="display:flex; flex-direction:column; gap:8px; width:140px;"><img src="{{ $galleryImage->image_url }}" alt="Gallery image" style="width:140px; height:100px; object-fit:cover; border-radius:14px; border:1px solid var(--line);"><span class="checkbox"><input type="checkbox" name="remove_gallery_images[]" value="{{ $galleryImage->id }}"> Hapus</span></label>@endforeach</div></div>
  @endif
  <div class="field-full"><label for="summary">Ringkasan</label><textarea id="summary" name="summary">{{ old('summary', $travelPackage?->summary) }}</textarea></div>
  <div class="field-full"><label for="description">Deskripsi</label><textarea id="description" name="description">{{ old('description', $travelPackage?->description) }}</textarea></div>
  <label class="field checkbox"><input name="is_featured" type="checkbox" value="1" {{ old('is_featured', $travelPackage?->is_featured) ? 'checked' : '' }}><span>Tandai sebagai paket unggulan</span></label>
  <label class="field checkbox"><input name="is_active" type="checkbox" value="1" {{ old('is_active', $travelPackage?->is_active ?? true) ? 'checked' : '' }}><span>Aktifkan paket di storefront</span></label>
</div>
<script>
  (() => {
    const fileInput = document.getElementById('featured_image_upload');
    const urlInput = document.getElementById('featured_image');
    const preview = document.getElementById('travel-package-preview');
    const galleryInput = document.getElementById('gallery_images_upload');
    const galleryPreview = document.getElementById('travel-gallery-preview');
    if (!fileInput || !urlInput || !preview || !galleryInput || !galleryPreview) return;

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

    galleryInput.addEventListener('change', (event) => {
      galleryPreview.innerHTML = '';
      const files = Array.from(event.target.files || []);
      files.forEach((file) => {
        const reader = new FileReader();
        reader.onload = (loadEvent) => {
          const img = document.createElement('img');
          img.src = loadEvent.target?.result || '';
          img.alt = file.name;
          img.style.cssText = 'width:120px;height:90px;object-fit:cover;border-radius:12px;border:1px solid var(--line);';
          galleryPreview.appendChild(img);
        };
        reader.readAsDataURL(file);
      });
    });
  })();
</script>
