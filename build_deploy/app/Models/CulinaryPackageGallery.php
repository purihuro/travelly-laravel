<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class CulinaryPackageGallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'culinary_package_id',
        'image_path',
        'sort_order',
    ];

    protected $appends = [
        'image_url',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function culinaryPackage(): BelongsTo
    {
        return $this->belongsTo(CulinaryPackage::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image_path) {
            return null;
        }

        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }

        if (Storage::disk('public')->exists($this->image_path)) {
            return Storage::disk('public')->url($this->image_path);
        }

        return asset($this->image_path);
    }
}
