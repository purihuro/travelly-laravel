<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingCulinary extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'culinary_venue_id',
        'culinary_package_id',
        'venue_name',
        'package_name',
        'reservation_date',
        'arrival_time',
        'unit_price',
        'quantity',
        'line_total',
        'notes',
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'arrival_time' => 'string',
        'unit_price' => 'decimal:2',
        'quantity' => 'integer',
        'line_total' => 'decimal:2',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function culinaryVenue(): BelongsTo
    {
        return $this->belongsTo(CulinaryVenue::class);
    }

    public function culinaryPackage(): BelongsTo
    {
        return $this->belongsTo(CulinaryPackage::class);
    }

    public function getFormattedLineTotalAttribute(): string
    {
        return '$' . number_format((float) $this->line_total, 2);
    }
}
