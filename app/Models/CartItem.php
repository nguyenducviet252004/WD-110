<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = ['cart_id', 'product_id', 'quantity', 'price', 'total', 'size_id', 'color_id'];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    // Quan hệ với Product (một CartItem thuộc một Product)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Tính toán total tự động
    public function calculateTotal()
    {
        $this->total = $this->quantity * $this->price;
    }
    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id');
    } 
    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id');
    }
}