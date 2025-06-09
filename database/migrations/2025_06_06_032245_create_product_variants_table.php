<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('product_size_id');
            $table->unsignedBigInteger('product_color_id');
            $table->unsignedInteger('quantity')->default(0);
            $table->string('image', 255)->nullable();
            $table->timestamps();

            $table->unique(['product_id', 'product_size_id', 'product_color_id'], 'product_variants_unique');

            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('cascade');

            $table->foreign('product_size_id')
                  ->references('id')
                  ->on('product_sizes')
                  ->onDelete('cascade');

            $table->foreign('product_color_id')
                  ->references('id')
                  ->on('product_colors')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_variants');
    }
};
