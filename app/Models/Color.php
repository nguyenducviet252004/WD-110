<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

     protected $fillable = [
        'name_color',
    ];

      public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    //  public function cartItems()
    // {
    //     return $this->hasMany(CartItem::class);
    // }

    //  public function orderDetails()
    // {
    //     return $this->hasMany(Order_detail::class);
    // }
}
