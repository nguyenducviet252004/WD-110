<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'is_active',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }


    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }
}
