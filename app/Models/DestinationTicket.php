<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DestinationTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'location',
        'category',
        'open_hours',
        'duration_minutes',
        'audience',
        'description',
        'image',
        'price',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'duration_minutes' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function bookingDestinationItems(): HasMany
    {
        return $this->hasMany(BookingDestinationItem::class, 'destination_slug', 'slug');
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
