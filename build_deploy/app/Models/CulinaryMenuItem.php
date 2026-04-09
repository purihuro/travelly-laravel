<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CulinaryMenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'culinary_venue_id',
        'name',
        'slug',
        'description',
        'category',
        'price',
        'ingredients',
        'allergies',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'ingredients' => 'array',
        'allergies' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }

    public function culinaryVenue(): BelongsTo
    {
        return $this->belongsTo(CulinaryVenue::class);
    }

    public function packageItems(): HasMany
    {
        return $this->hasMany(CulinaryPackageItem::class);
    }

    public function packages()
    {
        return $this->belongsToMany(
            CulinaryPackage::class,
            'culinary_package_items',
            'culinary_menu_item_id',
            'culinary_package_id'
        )->withPivot('quantity', 'notes', 'sort_order')
            ->orderByPivot('sort_order');
    }
}
