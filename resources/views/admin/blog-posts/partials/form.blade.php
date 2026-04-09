@php($blogPost = $blogPost ?? null)

<div class="form-grid">
  <div class="field"><label for="title">Judul</label><input id="title" name="title" type="text" value="{{ old('title', $blogPost?->title) }}" required></div>
  <div class="field"><label for="slug">Slug</label><input id="slug" name="slug" type="text" value="{{ old('slug', $blogPost?->slug) }}" required></div>
  <div class="field"><label for="author_name">Penulis</label><input id="author_name" name="author_name" type="text" value="{{ old('author_name', $blogPost?->author_name ?? auth()->user()?->name) }}" required></div>
  <div class="field"><label for="published_at">Tanggal Publish</label><input id="published_at" name="published_at" type="datetime-local" value="{{ old('published_at', $blogPost?->published_at?->format('Y-m-d\TH:i')) }}"></div>
  <div class="field-full"><label for="featured_image_upload">Upload Gambar</label><input id="featured_image_upload" name="featured_image_upload" type="file" accept="image/png,image/jpeg,image/webp"><div class="muted">Maksimal 2MB. Format JPG, PNG, atau WEBP.</div><div style="margin-top:10px;"><img id="blog-post-preview" src="{{ old('featured_image') ? old('featured_image') : ($blogPost?->image_url ?: '') }}" alt="Preview gambar artikel" style="{{ old('featured_image') || $blogPost?->image_url ? '' : 'display:none;' }} width:120px; height:120px; object-fit:cover; border-radius:14px; border:1px solid var(--line);"></div>@if ($blogPost?->image_url)<label class="field checkbox" style="margin-top:10px;"><input name="remove_featured_image" type="checkbox" value="1"><span>Hapus gambar lama</span></label>@endif</div>
  <div class="field-full"><label for="featured_image">Atau URL Gambar</label><input id="featured_image" name="featured_image" type="text" value="{{ old('featured_image', $blogPost?->featured_image) }}"></div>
  <div class="field-full"><label for="excerpt">Excerpt</label><textarea id="excerpt" name="excerpt">{{ old('excerpt', $blogPost?->excerpt) }}</textarea></div>
  <div class="field-full"><label for="content">Konten</label><textarea id="content" name="content">{{ old('content', $blogPost?->content) }}</textarea></div>
  <div class="field"><label for="meta_title">Meta Title</label><input id="meta_title" name="meta_title" type="text" value="{{ old('meta_title', $blogPost?->meta_title) }}"></div>
  <div class="field"><label for="meta_description">Meta Description</label><input id="meta_description" name="meta_description" type="text" value="{{ old('meta_description', $blogPost?->meta_description) }}"></div>
  <label class="field checkbox field-full"><input name="is_published" type="checkbox" value="1" {{ old('is_published', $blogPost?->is_published) ? 'checked' : '' }}><span>Publish artikel ini</span></label>
</div>
<script>
  (() => {
    const fileInput = document.getElementById('featured_image_upload');
    const urlInput = document.getElementById('featured_image');
    const preview = document.getElementById('blog-post-preview');
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
