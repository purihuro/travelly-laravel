<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CulinaryVenue extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'location',
        'phone',
        'website',
        'cuisine_type',
        'description',
        'image',
        'opening_time',
        'closing_time',
        'rating',
        'review_count',
        'capacity',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'rating' => 'decimal:2',
        'review_count' => 'integer',
        'capacity' => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function culinaryMenuItems(): HasMany
    {
        return $this->hasMany(CulinaryMenuItem::class);
    }

    public function culinaryPackages(): HasMany
    {
        return $this->hasMany(CulinaryPackage::class);
    }

    public function bookingCulinaries(): HasMany
    {
        return $this->hasMany(BookingCulinary::class);
    }

    public function getImageUrlAttribute(): string
    {
        if (! $this->image) {
            return asset('assets/images/product-1.jpg');
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        if (str_starts_with($this->image, 'storage/')) {
            return asset($this->image);
        }

        return asset('assets/images/' . ltrim($this->image, '/'));
    }
}
