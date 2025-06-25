<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'user_id',
        'product_variant_id',
        'quantity',
    ];

    /** Quan hệ tới User */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Quan hệ tới ProductVariant */
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}

