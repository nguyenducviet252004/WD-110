<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Kiểm tra và thêm các cột cần thiết nếu chưa có
        if (!Schema::hasColumn('products', 'img_thumb')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('img_thumb')->after('name')->nullable();
            });

            // Copy data từ avatar sang img_thumb nếu có
            if (Schema::hasColumn('products', 'avatar')) {
                DB::statement('UPDATE products SET img_thumb = avatar WHERE avatar IS NOT NULL');
            }
        }

        if (!Schema::hasColumn('products', 'slug')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('slug', 255)->after('img_thumb')->nullable();
            });
        }

        if (!Schema::hasColumn('products', 'deleted_at')) {
            Schema::table('products', function (Blueprint $table) {
                $table->timestamp('deleted_at')->nullable();
            });
        }

        // Xóa các cột cũ nếu có
        $columnsToRemove = ['import_price', 'price', 'quantity', 'sell_quantity'];
        foreach ($columnsToRemove as $column) {
            if (Schema::hasColumn('products', $column)) {
                Schema::table('products', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }

        // Xóa cột avatar sau khi đã copy sang img_thumb
        if (Schema::hasColumn('products', 'avatar')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('avatar');
            });
        }

        // Đảm bảo img_thumb không null nếu có dữ liệu
        $productsCount = DB::table('products')->count();
        if ($productsCount > 0) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('img_thumb')->nullable(false)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Thêm lại các cột cũ
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('import_price', 10, 2)->after('category_id')->nullable();
            $table->decimal('price', 10, 2)->after('import_price')->nullable();
            $table->unsignedInteger('quantity')->after('price')->nullable();
            $table->unsignedInteger('sell_quantity')->default(0)->after('quantity');
            $table->string('avatar')->after('name')->nullable();
        });

        // Copy data trở lại
        if (Schema::hasColumn('products', 'img_thumb')) {
            DB::statement('UPDATE products SET avatar = img_thumb WHERE img_thumb IS NOT NULL');
        }

        // Xóa các cột mới
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['img_thumb', 'slug', 'deleted_at']);
        });
    }
};
