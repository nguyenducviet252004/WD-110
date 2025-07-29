<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'avatar',
        'category_id',
        'import_price',
        'price',
        'description',
        'is_active',
        'quantity',
        'sell_quantity',
        'view',
    ];

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }

    public function categories(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function promotions()
    {
        return $this->hasMany(Promotion::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

     public function reviews()
    {
        return $this->hasMany(Review::class)->where('is_reviews', 1);
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_size', 'product_id', 'size_id');
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'product_color', 'product_id', 'color_id');
    }
    public function deleteProduct()
    {
        // Xóa avatar
        Storage::disk('public')->delete($this->avatar);

        // Xóa gallery
        foreach ($this->galleries as $gallery) {
            Storage::disk('public')->delete($gallery->image_path);
            $gallery->delete();
        }

        // Xóa bản ghi
        $this->delete();
    }
}
