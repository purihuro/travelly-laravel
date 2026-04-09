<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingTransportation extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'transport_method',
        'transport_label',
        'vehicle_detail',
        'pickup_location',
        'pickup_time',
        'passenger_count',
        'notes',
        'unit_price',
        'quantity',
        'line_total',
    ];

    protected $casts = [
        'pickup_time' => 'datetime',
        'passenger_count' => 'integer',
        'unit_price' => 'decimal:2',
        'quantity' => 'integer',
        'line_total' => 'decimal:2',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function getFormattedLineTotalAttribute(): string
    {
        return '$' . number_format((float) $this->line_total, 2);
    }
}
