<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo 10 danh mục ngẫu nhiên
        Category::factory()->count(10)->create();

        // Tạo 3 danh mục không active (để test lọc)
        Category::factory()->count(3)->inactive()->create();
    }
}
