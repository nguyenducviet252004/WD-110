<?php

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('name')->unique();
            $table->string('avatar');
            $table->foreignIdFor(Category::class)->constrained();
            $table->decimal('import_price', 10, 2);
            $table->decimal('price', 10, 2);
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('sell_quantity')->default(0);
            $table->unsignedInteger('view')->default(0);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(1); //0 la kdh, 1 là hd

            $table->timestamps();
        });

        DB::statement('ALTER TABLE `products` ADD CONSTRAINT `check_price` CHECK (`price` >= `import_price`)'); //dkien: giá bán phải lớn hơn giá nhập
        DB::statement('ALTER TABLE `products` ADD CONSTRAINT `check_quantity` CHECK (`quantity` >= 0)'); //dkien: sl>=0  
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
