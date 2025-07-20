<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_detail extends Model
{
    use HasFactory;
     protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'size_id',
        'color_id',
        'total',
        'is_deleted',
    ];

     public function order()
    {
        return $this->belongsTo(Order::class);
    }

     public function product()
    {
        return $this->belongsTo(Product::class);
    }

     public function size()
    {
        return $this->belongsTo(Size::class, 'size_id');
    }

      public function color()
    {
        return $this->belongsTo(Color::class, 'color_id');
    }
}
