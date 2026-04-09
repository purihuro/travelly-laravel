<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CulinaryPackageItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'culinary_package_id',
        'culinary_menu_item_id',
        'quantity',
        'notes',
        'sort_order',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'sort_order' => 'integer',
    ];

    public function culinaryPackage(): BelongsTo
    {
        return $this->belongsTo(CulinaryPackage::class);
    }

    public function culinaryMenuItem(): BelongsTo
    {
        return $this->belongsTo(CulinaryMenuItem::class);
    }
}
