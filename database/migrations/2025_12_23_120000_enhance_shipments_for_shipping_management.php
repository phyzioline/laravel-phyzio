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
        Schema::table('shipments', function (Blueprint $table) {
            // Vendor Pickup Location
            $table->string('vendor_name')->nullable()->after('vendor_id');
            $table->string('vendor_phone')->nullable()->after('vendor_name');
            $table->text('vendor_address')->nullable()->after('vendor_phone');
            $table->string('vendor_city')->nullable()->after('vendor_address');
            $table->string('vendor_governorate')->nullable()->after('vendor_city');
            $table->decimal('vendor_lat', 10, 7)->nullable()->after('vendor_governorate');
            $table->decimal('vendor_lng', 10, 7)->nullable()->after('vendor_lat');
            
            // Customer Delivery Location
            $table->string('customer_name')->nullable()->after('vendor_lng');
            $table->string('customer_phone')->nullable()->after('customer_name');
            $table->text('customer_address')->nullable()->after('customer_phone');
            $table->string('customer_city')->nullable()->after('customer_address');
            $table->string('customer_governorate')->nullable()->after('customer_city');
            $table->decimal('customer_lat', 10, 7)->nullable()->after('customer_governorate');
            $table->decimal('customer_lng', 10, 7)->nullable()->after('customer_lat');
            
            // Shipping Provider Details
            $table->string('shipping_provider')->nullable()->after('courier'); // bosta, aramex, dhl, etc.
            $table->string('shipping_provider_id')->nullable()->after('shipping_provider'); // Provider's shipment ID
            $table->string('shipping_label_url')->nullable()->after('shipping_provider_id');
            $table->decimal('shipping_cost', 10, 2)->nullable()->after('shipping_label_url');
            $table->string('shipping_method')->nullable()->after('shipping_cost'); // express, standard, economy
            
            // Package Details
            $table->integer('package_weight')->nullable()->after('shipping_method'); // in grams
            $table->integer('package_length')->nullable()->after('package_weight'); // in cm
            $table->integer('package_width')->nullable()->after('package_length');
            $table->integer('package_height')->nullable()->after('package_width');
            $table->text('package_description')->nullable()->after('package_height');
            
            // Additional Status Fields - Note: Enum change requires column recreation
            // We'll use string instead for flexibility
            $table->string('shipment_status', 50)->default('pending')->change();
            $table->timestamp('ready_to_ship_at')->nullable()->after('shipped_at');
            $table->timestamp('picked_up_at')->nullable()->after('ready_to_ship_at');
            $table->timestamp('in_transit_at')->nullable()->after('picked_up_at');
            $table->timestamp('out_for_delivery_at')->nullable()->after('in_transit_at');
            $table->timestamp('exception_at')->nullable()->after('delivered_at');
            $table->text('exception_reason')->nullable()->after('exception_at');
            
            // Delivery Details
            $table->string('delivered_to')->nullable()->after('exception_reason'); // Person who received
            $table->string('delivery_notes')->nullable()->after('delivered_to');
            
            // Indexes
            $table->index('shipping_provider');
            $table->index('shipping_provider_id');
            $table->index('customer_city');
            $table->index('vendor_city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn([
                'vendor_name', 'vendor_phone', 'vendor_address', 'vendor_city', 'vendor_governorate',
                'vendor_lat', 'vendor_lng',
                'customer_name', 'customer_phone', 'customer_address', 'customer_city', 'customer_governorate',
                'customer_lat', 'customer_lng',
                'shipping_provider', 'shipping_provider_id', 'shipping_label_url', 'shipping_cost', 'shipping_method',
                'package_weight', 'package_length', 'package_width', 'package_height', 'package_description',
                'ready_to_ship_at', 'picked_up_at', 'in_transit_at', 'out_for_delivery_at',
                'exception_at', 'exception_reason', 'delivered_to', 'delivery_notes'
            ]);
        });
    }
};

