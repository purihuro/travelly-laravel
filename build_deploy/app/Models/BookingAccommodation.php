<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingAccommodation extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'accommodation_id',
        'accommodation_name',
        'accommodation_type',
        'location',
        'unit_price',
        'quantity',
        'line_total',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'quantity' => 'integer',
        'line_total' => 'decimal:2',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function accommodation(): BelongsTo
    {
        return $this->belongsTo(Accommodation::class);
    }

    public function getFormattedLineTotalAttribute(): string
    {
        return '$' . number_format((float) $this->line_total, 2);
    }
}
