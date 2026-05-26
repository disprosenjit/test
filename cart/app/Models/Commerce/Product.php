<?php

namespace App\Models\Commerce;

use App\Models\Audit\VesselType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Casts\AsCollection;

class Product extends Model
{
    protected $fillable = [
        'sku',
        'part_number',
        'name',
        'brand_id',
        'category_id',
        'vessel_type_id',
        'description',
        'specifications',
        'price',
        'cost',
        'stock_qty',
        'weight',
        'dimensions',
        'image_url',
        'images',
        'is_active',
    ];

    protected $casts = [
        'specifications' => 'array',
        'images' => 'array',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'cost' => 'decimal:2',
        'rating' => 'decimal:2',
    ];

    // Relationships
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function vesselType(): BelongsTo
    {
        return $this->belongsTo(VesselType::class);
    }

    public function inventory(): HasOne
    {
        return $this->hasOne(Inventory::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_qty', '>', 0);
    }

    public function scopeByBrand($query, $brandId)
    {
        return $query->where('brand_id', $brandId);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByVesselType($query, $vesselTypeId)
    {
        return $query->where('vessel_type_id', $vesselTypeId);
    }

    public function scopeByPriceRange($query, $minPrice, $maxPrice)
    {
        return $query->whereBetween('price', [$minPrice, $maxPrice]);
    }

    public function scopeSearchByPartNumber($query, $partNumber)
    {
        return $query->where('part_number', 'LIKE', "%{$partNumber}%")
                     ->orWhere('sku', 'LIKE', "%{$partNumber}%");
    }

    // Methods
    public function getAvailableQuantity(): int
    {
        return $this->inventory?->available_qty ?? $this->stock_qty;
    }

    public function incrementViewCount()
    {
        $this->increment('view_count');
        return $this;
    }
}
