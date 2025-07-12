<?php

use App\Models\Order;
use App\Models\User;
use App\Models\Voucher;
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
        Schema::create('voucher_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained();
            $table->unsignedBigInteger('order_id'); // Thay đổi thành unsignedBigInteger
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreignIdFor(Voucher::class)->constrained();
            $table->decimal('discount_value', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voucher_usages');
    }
};
