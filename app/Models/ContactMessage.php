<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ContactMessage extends Model
{
    use HasFactory;

    protected $appends = [
        'status_label',
    ];

    protected $fillable = [
        'full_name',
        'email',
        'subject',
        'message',
        'status',
    ];

    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('status', 'new');
    }

    public function getStatusLabelAttribute(): string
    {
        return ucfirst((string) $this->status);
    }
}
