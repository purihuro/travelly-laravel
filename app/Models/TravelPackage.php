<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class TravelPackage extends Model
{
    use HasFactory;

    protected $appends = [
        'display_price',
        'effective_price',
        'image_url',
    ];

    protected $fillable = [
        'slug',
        'title',
        'category',
        'summary',
        'description',
        'featured_image',
        'base_price',
        'sale_price',
        'duration_days',
        'quota',
        'location',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'duration_days' => 'integer',
        'quota' => 'integer',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function bookingItems(): HasMany
    {
        return $this->hasMany(BookingItem::class);
    }

    public function galleryImages(): HasMany
    {
        return $this->hasMany(TravelPackageGallery::class)->orderBy('sort_order')->orderBy('id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function getEffectivePriceAttribute(): float
    {
        return (float) ($this->sale_price ?? $this->base_price);
    }

    public function getDisplayPriceAttribute(): string
    {
        return '$' . number_format($this->effective_price, 2);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->featured_image) {
            return null;
        }

        if (str_starts_with($this->featured_image, 'http://') || str_starts_with($this->featured_image, 'https://')) {
            return $this->featured_image;
        }

        if (Storage::disk('public')->exists($this->featured_image)) {
            return Storage::disk('public')->url($this->featured_image);
        }

        return asset($this->featured_image);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
