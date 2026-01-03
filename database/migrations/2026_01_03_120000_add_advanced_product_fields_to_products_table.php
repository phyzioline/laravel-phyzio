<?php

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
        Schema::table('products', function (Blueprint $table) {
            // Product Details - Additional fields
            $table->string('brand_name')->nullable()->after('product_name_en');
            $table->string('model_number')->nullable()->after('brand_name');
            $table->string('manufacturer')->nullable()->after('model_number');
            $table->text('bullet_points')->nullable()->after('long_description_en');
            $table->string('generic_keywords')->nullable()->after('bullet_points');
            $table->string('product_type')->nullable()->after('generic_keywords');
            
            // Offer/Pricing fields
            $table->decimal('compare_at_price', 10, 2)->nullable()->after('product_price');
            $table->decimal('cost_price', 10, 2)->nullable()->after('compare_at_price');
            $table->integer('min_quantity')->default(1)->after('amount');
            $table->integer('max_quantity')->nullable()->after('min_quantity');
            $table->boolean('track_inventory')->default(true)->after('max_quantity');
            $table->string('barcode')->nullable()->after('sku');
            $table->string('ean')->nullable()->after('barcode');
            $table->string('upc')->nullable()->after('ean');
            
            // Variations support
            $table->boolean('has_variations')->default(false)->after('upc');
            $table->json('variation_attributes')->nullable()->after('has_variations'); // e.g., ["Color", "Size"]
            
            // Safety & Compliance
            $table->string('country_of_origin')->nullable()->after('variation_attributes');
            $table->text('warranty_description')->nullable()->after('country_of_origin');
            $table->text('seller_warranty_description')->nullable()->after('warranty_description');
            $table->boolean('batteries_required')->nullable()->after('seller_warranty_description');
            $table->string('battery_iec_code')->nullable()->after('batteries_required');
            $table->string('dangerous_goods_regulations')->nullable()->after('battery_iec_code');
            $table->decimal('item_weight', 10, 2)->nullable()->after('dangerous_goods_regulations');
            $table->string('item_weight_unit')->default('grams')->after('item_weight');
            $table->boolean('age_restriction_required')->default(false)->after('item_weight_unit');
            $table->string('responsible_person_email')->nullable()->after('age_restriction_required');
            
            // Additional metadata
            $table->string('condition')->default('new')->after('responsible_person_email'); // new, used, refurbished
            $table->text('special_features')->nullable()->after('condition');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'brand_name', 'model_number', 'manufacturer', 'bullet_points', 'generic_keywords', 'product_type',
                'compare_at_price', 'cost_price', 'min_quantity', 'max_quantity', 'track_inventory',
                'barcode', 'ean', 'upc', 'has_variations', 'variation_attributes',
                'country_of_origin', 'warranty_description', 'seller_warranty_description',
                'batteries_required', 'battery_iec_code', 'dangerous_goods_regulations',
                'item_weight', 'item_weight_unit', 'age_restriction_required', 'responsible_person_email',
                'condition', 'special_features'
            ]);
        });
    }
};

