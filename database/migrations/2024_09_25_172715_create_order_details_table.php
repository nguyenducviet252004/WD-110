<?php

use App\Models\Color;
use App\Models\Order;
use App\Models\Product;
use App\Models\Size;
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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id'); // Thay đổi thành unsignedBigInteger
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreignIdFor(Product::class)->nullable()->constrained()->nullOnDelete(); // Cho phép null và null khi xóa sản phẩm
            $table->unsignedInteger('quantity')->nullable(); // Cho phép null
            $table->decimal('price', 10, 2)->nullable(); // Cho phép null
            $table->foreignIdFor(Size::class)->nullable()->constrained()->nullOnDelete(); // Cho phép null
            $table->foreignIdFor(Color::class)->nullable()->constrained()->nullOnDelete(); // Cho phép null
            $table->decimal('total', 10, 2)->nullable(); // Cho phép null
            $table->boolean('is_deleted')->default(false); // Đánh dấu là đã xóa
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
