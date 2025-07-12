<?php

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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->decimal('discount_value', 10, 2);
            $table->text('description')->nullable();
            $table->integer('quantity')->default(1);
            $table->integer('used_times')->default(0);
            $table->decimal('total_min', 10, 2)->default(0); // Giá trị đơn hàng tối thiểu để áp dụng
            $table->decimal('total_max', 10, 2)->nullable(); // Giá trị đơn hàng tối đa để áp dụng
            $table->dateTime('start_day')->nullable();
            $table->dateTime('end_day')->nullable();
            $table->boolean('is_active')->default(1); //0 la kdh, 1 là hd
            $table->timestamps();
        });
        DB::statement('ALTER TABLE `vouchers` ADD CONSTRAINT `check_end_day` CHECK (`end_day` >=  `start_day` )');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
