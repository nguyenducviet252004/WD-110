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
        Schema::table('order_details', function (Blueprint $table) {
            // Add product_variant_id column if not exists
            if (!Schema::hasColumn('order_details', 'product_variant_id')) {
                $table->foreignId('product_variant_id')->nullable()->after('product_id')
                      ->constrained('product_variants')->nullOnDelete();
            }

            // Add product_name column if not exists
            if (!Schema::hasColumn('order_details', 'product_name')) {
                $table->string('product_name')->after('product_variant_id')->nullable();
            }

            // Add size_name column if not exists
            if (!Schema::hasColumn('order_details', 'size_name')) {
                $table->string('size_name')->after('size_id')->nullable();
            }

            // Add price_sale column if not exists
            if (!Schema::hasColumn('order_details', 'price_sale')) {
                $table->decimal('price_sale', 10, 2)->after('size_name')->nullable();
            }
        });

        // Copy data from price to price_sale if price column exists
        if (Schema::hasColumn('order_details', 'price')) {
            DB::statement('UPDATE order_details SET price_sale = price WHERE price IS NOT NULL');

            // Drop old price column
            Schema::table('order_details', function (Blueprint $table) {
                $table->dropColumn('price');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            // Add back price column
            $table->decimal('price', 10, 2)->after('size_name')->nullable();
        });

        // Copy data back from price_sale to price
        if (Schema::hasColumn('order_details', 'price_sale')) {
            DB::statement('UPDATE order_details SET price = price_sale WHERE price_sale IS NOT NULL');
        }

        Schema::table('order_details', function (Blueprint $table) {
            // Drop foreign key and column
            if (Schema::hasColumn('order_details', 'product_variant_id')) {
                $table->dropForeign(['product_variant_id']);
                $table->dropColumn('product_variant_id');
            }

            // Drop new columns
            $columnsToDrop = ['product_name', 'size_name', 'price_sale'];
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('order_details', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
