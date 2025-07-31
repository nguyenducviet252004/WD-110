<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'size_id',
        'color_id',
        'quantity',
        'price',
        'price_sale'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'price_sale' => 'decimal:2',
    ];

    /**
     * Relationship with Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relationship with Size
     */
    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    /**
     * Relationship with Color
     */
    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    /**
     * Scope to get available variants (quantity > 0)
     */
    public function scopeAvailable($query)
    {
        return $query->where('quantity', '>', 0);
    }

    /**
     * Get the effective price (sale price if available, otherwise regular price)
     */
    public function getEffectivePriceAttribute()
    {
        return $this->price_sale ?? $this->price;
    }
}
