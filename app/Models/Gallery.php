<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Gallery extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'image_path',
    ];
    public function getImagePathAttribute()
    {
        return Storage::url($this->attributes['image_path']);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}