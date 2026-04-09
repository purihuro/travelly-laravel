<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;

class Booking extends Model
{
    use HasFactory;

    protected $appends = [
        'customer_full_name',
        'status_badge',
        'payment_badge',
    ];

    protected $fillable = [
        'booking_code',
        'customer_first_name',
        'customer_last_name',
        'customer_email',
        'customer_phone',
        'country',
        'city',
        'address_line_1',
        'address_line_2',
        'postal_code',
        'departure_date',
        'participants',
        'payment_method',
        'notes',
        'subtotal',
        'service_fee',
        'discount_amount',
        'total_amount',
        'booking_status',
        'payment_status',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'participants' => 'integer',
        'subtotal' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(BookingItem::class);
    }

    public function destinationItems(): HasMany
    {
        return $this->hasMany(BookingDestinationItem::class);
    }

    public function accommodation(): HasOne
    {
        return $this->hasOne(BookingAccommodation::class);
    }

    public function transportation(): HasOne
    {
        return $this->hasOne(BookingTransportation::class);
    }

    public function culinary(): HasOne
    {
        return $this->hasOne(BookingCulinary::class);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('booking_status', 'pending');
    }

    public function getCustomerFullNameAttribute(): string
    {
        return trim($this->customer_first_name . ' ' . $this->customer_last_name);
    }

    public function getStatusBadgeAttribute(): string
    {
        return ucfirst((string) $this->booking_status);
    }

    public function getPaymentBadgeAttribute(): string
    {
        return ucfirst((string) $this->payment_status);
    }
}
