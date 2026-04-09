<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CulinaryPackage extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::saving(function (self $package): void {
            $price = (float) ($package->price_per_person ?? 0);
            $discount = max(0.0, (float) ($package->discount_amount ?? 0));
            $package->final_price = max(0.0, $price - $discount);

            if (blank($package->availability_status)) {
                $package->availability_status = 'available';
            }

            if ($package->max_bookings !== null && (int) $package->current_bookings >= (int) $package->max_bookings) {
                $package->availability_status = 'sold_out';
            }
        });
    }

    protected $fillable = [
        'culinary_venue_id',
        'name',
        'slug',
        'image',
        'description',
        'price_per_person',
        'discount_amount',
        'final_price',
        'preparation_time',
        'serving_size',
        'min_people',
        'max_people',
        'rating',
        'availability_from',
        'availability_to',
        'max_bookings',
        'current_bookings',
        'availability_status',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price_per_person' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_price' => 'decimal:2',
        'min_people' => 'integer',
        'max_people' => 'integer',
        'preparation_time' => 'integer',
        'rating' => 'decimal:2',
        'availability_from' => 'date',
        'availability_to' => 'date',
        'max_bookings' => 'integer',
        'current_bookings' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeBookable(Builder $query): Builder
    {
        $today = now()->toDateString();

        return $query
            ->active()
            ->where('availability_status', 'available')
            ->where(function (Builder $inner) use ($today) {
                $inner->whereNull('availability_from')
                    ->orWhereDate('availability_from', '<=', $today);
            })
            ->where(function (Builder $inner) use ($today) {
                $inner->whereNull('availability_to')
                    ->orWhereDate('availability_to', '>=', $today);
            })
            ->where(function (Builder $inner) {
                $inner->whereNull('max_bookings')
                    ->orWhereColumn('current_bookings', '<', 'max_bookings');
            });
    }

    public function getEffectivePriceAttribute(): float
    {
        if ($this->final_price !== null) {
            return max(0.0, (float) $this->final_price);
        }

        return max(0.0, (float) $this->price_per_person - (float) ($this->discount_amount ?? 0));
    }

    public function getImageUrlAttribute(): string
    {
        if (! $this->image) {
            return $this->culinaryVenue?->image_url ?: asset('assets/images/product-2.jpg');
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        if (str_starts_with($this->image, 'storage/')) {
            return asset($this->image);
        }

        return asset('assets/images/' . ltrim($this->image, '/'));
    }

    public function culinaryVenue(): BelongsTo
    {
        return $this->belongsTo(CulinaryVenue::class);
    }

    public function packageItems(): HasMany
    {
        return $this->hasMany(CulinaryPackageItem::class);
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(CulinaryPackageGallery::class)->orderBy('sort_order');
    }

    public function menuItems()
    {
        return $this->belongsToMany(
            CulinaryMenuItem::class,
            'culinary_package_items',
            'culinary_package_id',
            'culinary_menu_item_id'
        )->withPivot('quantity', 'notes', 'sort_order')
            ->orderByPivot('sort_order');
    }

    public function bookingCulinaries(): HasMany
    {
        return $this->hasMany(BookingCulinary::class);
    }
}
