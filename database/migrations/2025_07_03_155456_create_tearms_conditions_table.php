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
        Schema::create('tearms_conditions', function (Blueprint $table) {
            $table->id();
            $table->string('product_usage_ar')->nullable();
            $table->string('product_usage_en')->nullable();
            $table->string('account_security_en')->nullable();
            $table->string('account_security_ar')->nullable();
            $table->string('shipping_delivery_ar')->nullable();
            $table->string('shipping_delivery_en')->nullable();
            $table->string('returns_refund_ar')->nullable();
            $table->string('returns_refund_en')->nullable();
            $table->string('payment_policy_ar')->nullable();
            $table->string('payment_policy_en')->nullable();
            $table->string('legal_compliance_ar')->nullable();
            $table->string('legal_compliance_en')->nullable();
            $table->string('contact_support_ar')->nullable();
            $table->string('contact_support_en')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tearms_conditions');
    }
};
