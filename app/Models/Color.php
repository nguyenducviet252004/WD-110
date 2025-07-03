<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

    protected $table = 'product_colors';

    protected $fillable = [
        'name',
        'code'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_color', 'color_id', 'product_id');
    }
}