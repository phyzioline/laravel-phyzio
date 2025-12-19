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
        Schema::table('tearms_conditions', function (Blueprint $table) {
            $table->longText('product_usage_ar')->nullable()->change();
            $table->longText('product_usage_en')->nullable()->change();
            $table->longText('account_security_en')->nullable()->change();
            $table->longText('account_security_ar')->nullable()->change();
            $table->longText('returns_refund_ar')->nullable()->change();
            $table->longText('returns_refund_en')->nullable()->change();
            $table->longText('payment_policy_ar')->nullable()->change();
            $table->longText('payment_policy_en')->nullable()->change();
            $table->longText('legal_compliance_ar')->nullable()->change();
            $table->longText('legal_compliance_en')->nullable()->change();
            $table->longText('contact_support_ar')->nullable()->change();
            $table->longText('contact_support_en')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tearms_conditions', function (Blueprint $table) {
            $table->string('product_usage_ar')->nullable()->change();
            $table->string('product_usage_en')->nullable()->change();
            $table->string('account_security_en')->nullable()->change();
            $table->string('account_security_ar')->nullable()->change();
            $table->string('returns_refund_ar')->nullable()->change();
            $table->string('returns_refund_en')->nullable()->change();
            $table->string('payment_policy_ar')->nullable()->change();
            $table->string('payment_policy_en')->nullable()->change();
            $table->string('legal_compliance_ar')->nullable()->change();
            $table->string('legal_compliance_en')->nullable()->change();
            $table->string('contact_support_ar')->nullable()->change();
            $table->string('contact_support_en')->nullable()->change();
        });
    }
};
