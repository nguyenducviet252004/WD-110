<?php

use Illuminate\Support\Facades\DB;
use App\Models\Product;
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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Product::class)->constrained();
            $table->dateTime('start_day');
            $table->dateTime('end_day');
            $table->decimal('price_discount', 10, 2)->check('price_discount <= price');
            $table->timestamps();
        });
        DB::statement('ALTER TABLE `promotions` ADD CONSTRAINT `check_end_day_promotion` CHECK (`end_day` >=  `start_day` )');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
