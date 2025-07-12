<?php

use App\Models\Order;
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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Order::class); // Lưu ID của đơn hàng
            $table->string('transaction_id')->nullable(); // Mã giao dịch VNPay (nullable vì có thể không có khi COD)
            $table->string('payment_method'); // Phương thức thanh toán 1cod, 2onl
            $table->decimal('amount', 15, 2); // Số tiền thanh toán
            $table->string('status'); // Trạng thái thanh toán (thành công, thất bại, pending)
            $table->string('response_code')->nullable(); // Mã phản hồi từ VNPay (ví dụ: "00" cho thành công)
            $table->string('secure_hash')->nullable(); // Chữ ký bảo mật trả về từ VNPay
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
