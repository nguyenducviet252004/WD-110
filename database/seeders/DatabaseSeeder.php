<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\ProductSize;
use App\Models\ProductColor;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Category::create(['name' => 'Clothing', 'slug' => 'clothing', 'status' => 1, 'is_active' => 1]);
        ProductSize::create(['name' => 'S']);
        ProductSize::create(['name' => 'M']);
        ProductSize::create(['name' => 'L']);
        ProductColor::create(['name' => 'Red']);
        ProductColor::create(['name' => 'Blue']);
        ProductColor::create(['name' => 'Green']);
    }
}
