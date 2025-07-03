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
        // Call other seeders
        $this->call([
            CategorySeeder::class
        ]);
        // Seed categories
        if (!Category::where('slug', 'clothing')->exists()) {
            Category::create(['name' => 'Clothing', 'slug' => 'clothing', 'status' => 1, 'is_active' => 1]);
        }

        // Seed sizes
        $sizes = ['S', 'M', 'L'];
        foreach ($sizes as $size) {
            if (!ProductSize::where('name', $size)->exists()) {
                ProductSize::create(['name' => $size]);
            }
        }

        // Seed colors
        $colors = [
            ['name' => 'Red', 'code' => '#FF0000'],
            ['name' => 'Blue', 'code' => '#0000FF'],
            ['name' => 'Green', 'code' => '#00FF00']
        ];
        foreach ($colors as $color) {
            if (!ProductColor::where('name', $color['name'])->exists()) {
                ProductColor::create($color);
            }
        }
    }
}
