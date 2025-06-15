<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('voucher_id')->constrained()->cascadeOnDelete();
            $table->integer('usage_count')->default(0);
            $table->boolean('is_used')->default(false);
            $table->datetime('assigned_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'voucher_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_vouchers');
    }
};
