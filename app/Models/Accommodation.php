<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Accommodation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'room_type',
        'location',
        'highlight',
        'amenities',
        'description',
        'image',
        'price_per_night',
        'capacity',
        'is_active',
    ];

    protected $casts = [
        'price_per_night' => 'decimal:2',
        'capacity' => 'integer',
        'is_active' => 'boolean',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function bookingAccommodations(): HasMany
    {
        return $this->hasMany(BookingAccommodation::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'hotel' => 'Hotel',
            'villa' => 'Villa',
            'homestay' => 'Homestay',
            default => ucfirst((string) $this->type),
        };
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

    public function getAmenitiesListAttribute(): array
    {
        return collect(explode(',', (string) $this->amenities))
            ->map(fn ($item) => trim($item))
            ->filter()
            ->values()
            ->all();
    }
}
