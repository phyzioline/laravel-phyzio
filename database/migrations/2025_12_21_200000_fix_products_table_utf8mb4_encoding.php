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
        // Fix character set for Arabic text columns
        DB::statement('ALTER TABLE products MODIFY product_name_ar VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        DB::statement('ALTER TABLE products MODIFY short_description_ar TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        DB::statement('ALTER TABLE products MODIFY long_description_ar TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        
        // Also fix English columns to be safe
        DB::statement('ALTER TABLE products MODIFY product_name_en VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        DB::statement('ALTER TABLE products MODIFY short_description_en TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        DB::statement('ALTER TABLE products MODIFY long_description_en TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to utf8 (not recommended but for rollback)
        DB::statement('ALTER TABLE products MODIFY product_name_ar VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci');
        DB::statement('ALTER TABLE products MODIFY short_description_ar TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci');
        DB::statement('ALTER TABLE products MODIFY long_description_ar TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci');
        DB::statement('ALTER TABLE products MODIFY product_name_en VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci');
        DB::statement('ALTER TABLE products MODIFY short_description_en TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci');
        DB::statement('ALTER TABLE products MODIFY long_description_en TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci');
    }
};

