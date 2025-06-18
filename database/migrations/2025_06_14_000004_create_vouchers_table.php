<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->datetime('start_date_time');
            $table->datetime('end_date_time');
            $table->decimal('discount', 12, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->decimal('min_order_amount', 12, 2)->nullable();
            $table->integer('used_count')->default(0);
            $table->integer('max_usage')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vouchers');
    }
};
