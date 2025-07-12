<?php

use App\Models\Product;
use App\Models\Ship_address;
use App\Models\User;
use App\Models\Voucher;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained();
            $table->unsignedInteger('quantity');
            $table->decimal('total_amount', 10, 2);
            $table->tinyInteger('payment_method')->default(1);// 0: tiền mặt, 1: chuyển khoản ngân hàng, 2: thanh toán qua thẻ atm
            $table->tinyInteger('ship_method')->default(1);// 0: giao hàng tiêu chuẩn, 1: giao hàng hỏa tốc
            $table->foreignIdFor(Voucher::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Ship_address::class)->nullable()->constrained();
            $table->decimal('discount_value', 8, 2)->nullable();
            $table->tinyInteger('status')->default(0);//0: Đang chờ xử lí, 1: Đã xử lí/ đang chuẩn bị sản phẩm, 2: Đang vận chuyển, 3: Giao hàng thành công, 4: Đơn hàng đã bị hủy
            $table->string('message')->nullable();
            $table->timestamps();
        });
        DB::statement('ALTER TABLE `orders` ADD CONSTRAINT `check_payment_method` CHECK (`payment_method` >= 0 AND `payment_method` <= 2)');
        DB::statement('ALTER TABLE `orders` ADD CONSTRAINT `check_ship_method` CHECK (`ship_method` >= 0 AND `ship_method` <= 1)');
        DB::statement('ALTER TABLE `orders` ADD CONSTRAINT `check_status_orders` CHECK (`status` >= 0 AND `status` <= 5)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
