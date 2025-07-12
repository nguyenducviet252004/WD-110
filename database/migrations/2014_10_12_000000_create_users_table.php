<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('avatar')->nullable();
            $table->string('username')->unique()->nullable();
            $table->string('password');
            $table->string('fullname')->nullable();
            $table->date('birth_day')->nullable();
            $table->string('phone', 15)->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('address')->nullable();
            $table->tinyInteger('role')->default(0); // 0: user, 1: nhân viên, 2: admin
            $table->boolean('is_active')->default(1); // 0: khóa, 1: active
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // Điều kiện CHECK cho cột role
        DB::statement('ALTER TABLE `users` ADD CONSTRAINT `check_role` CHECK (`role` >= 0 AND `role` <= 2)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
