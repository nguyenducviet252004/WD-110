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
        // Fix gallery image paths that contain full URLs
        DB::statement("
            UPDATE galleries
            SET image_path = REPLACE(image_path, 'http://localhost:8000/storage/', '')
            WHERE image_path LIKE 'http://localhost:8000/storage/%'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back the URL prefix if needed
        DB::statement("
            UPDATE galleries
            SET image_path = CONCAT('http://localhost:8000/storage/', image_path)
            WHERE image_path NOT LIKE 'http://%'
        ");
    }
};
