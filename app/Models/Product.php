<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'thumb_image',
        'price_regular',
        'price_sale',
        'description',
        'content',
        'views',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price_regular' => 'decimal:2',
        'price_sale' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    // Accessor để trả về URL đầy đủ cho thumb_image
    public function getThumbImageAttribute($value)
    {
        return $value ? Storage::url($value) : null;
    }
}
