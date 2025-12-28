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
        // Insurance Providers
        if (!Schema::hasTable('insurance_providers')) {
            Schema::create('insurance_providers', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code')->unique(); // e.g., BCBS, AETNA, CIGNA
                $table->string('payer_id')->nullable(); // CMS Payer ID
                $table->string('contact_phone')->nullable();
                $table->string('contact_email')->nullable();
                $table->string('claims_address')->nullable();
                $table->string('eligibility_endpoint')->nullable(); // API endpoint
                $table->json('settings')->nullable(); // API keys, credentials
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Patient Insurance Information
        if (!Schema::hasTable('patient_insurance')) {
            Schema::create('patient_insurance', function (Blueprint $table) {
                $table->id();
                $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
                $table->foreignId('insurance_provider_id')->constrained('insurance_providers')->onDelete('cascade');
                $table->string('policy_number');
                $table->string('group_number')->nullable();
                $table->string('subscriber_name');
                $table->string('subscriber_relationship'); // self, spouse, child, other
                $table->date('effective_date');
                $table->date('expiration_date')->nullable();
                $table->boolean('is_primary')->default(true);
                $table->json('benefits')->nullable(); // Coverage details
                $table->json('copay_info')->nullable(); // Copay amounts
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->index(['patient_id', 'is_primary']);
            });
        }

        // Insurance Authorizations
        if (!Schema::hasTable('insurance_authorizations')) {
            Schema::create('insurance_authorizations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
                $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
                $table->foreignId('patient_insurance_id')->nullable()->constrained('patient_insurance')->onDelete('set null');
                $table->string('authorization_number')->unique();
                $table->string('referral_number')->nullable();
                $table->date('authorization_date');
                $table->date('expiration_date');
                $table->integer('approved_visits')->default(0);
                $table->integer('used_visits')->default(0);
                $table->integer('remaining_visits')->default(0);
                $table->decimal('approved_amount', 10, 2)->nullable();
                $table->text('diagnosis_codes')->nullable(); // ICD-10 codes
                $table->text('procedure_codes')->nullable(); // CPT codes
                $table->enum('status', ['pending', 'approved', 'denied', 'expired', 'exhausted'])->default('pending');
                $table->text('notes')->nullable();
                $table->json('conditions')->nullable(); // Authorization conditions
                $table->timestamps();

                $table->index(['patient_id', 'status']);
                $table->index(['clinic_id', 'status']);
            });
        }

        // Insurance Claims
        if (!Schema::hasTable('insurance_claims')) {
            Schema::create('insurance_claims', function (Blueprint $table) {
                $table->id();
                $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
                $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
                $table->foreignId('appointment_id')->nullable()->constrained('clinic_appointments')->onDelete('set null');
                $table->foreignId('invoice_id')->nullable()->constrained('invoices')->onDelete('set null');
                $table->foreignId('patient_insurance_id')->constrained('patient_insurance')->onDelete('cascade');
                $table->foreignId('authorization_id')->nullable()->constrained('insurance_authorizations')->onDelete('set null');
                $table->string('claim_number')->unique();
                $table->string('control_number')->nullable(); // Internal control number
                $table->enum('claim_type', ['primary', 'secondary', 'tertiary'])->default('primary');
                $table->date('date_of_service');
                $table->date('date_of_service_end')->nullable();
                $table->decimal('billed_amount', 10, 2);
                $table->decimal('allowed_amount', 10, 2)->nullable();
                $table->decimal('paid_amount', 10, 2)->nullable();
                $table->decimal('patient_responsibility', 10, 2)->nullable();
                $table->text('diagnosis_codes')->nullable(); // ICD-10 codes (comma-separated)
                $table->text('procedure_codes')->nullable(); // CPT codes (comma-separated)
                $table->text('modifiers')->nullable(); // CPT modifiers
                $table->enum('status', ['draft', 'pending', 'submitted', 'accepted', 'rejected', 'denied', 'paid', 'partial', 'pending_review'])->default('draft');
                $table->date('submitted_at')->nullable();
                $table->date('processed_at')->nullable();
                $table->text('denial_reason')->nullable();
                $table->text('denial_code')->nullable(); // Claim adjustment reason code
                $table->text('notes')->nullable();
                $table->json('scrubbing_results')->nullable(); // Pre-submission validation results
                $table->json('era_data')->nullable(); // Electronic Remittance Advice data
                $table->boolean('requires_resubmission')->default(false);
                $table->timestamps();

                $table->index(['clinic_id', 'status']);
                $table->index(['patient_id', 'status']);
                $table->index(['claim_number']);
            });
        }

        // Claim Denials & Appeals
        if (!Schema::hasTable('claim_denials')) {
            Schema::create('claim_denials', function (Blueprint $table) {
                $table->id();
                $table->foreignId('claim_id')->constrained('insurance_claims')->onDelete('cascade');
                $table->string('denial_code'); // CARC (Claim Adjustment Reason Code)
                $table->string('denial_reason');
                $table->text('description')->nullable();
                $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
                $table->enum('status', ['new', 'in_review', 'appealed', 'resolved', 'written_off'])->default('new');
                $table->text('resolution_notes')->nullable();
                $table->date('resolved_at')->nullable();
                $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamps();

                $table->index(['claim_id', 'status']);
            });
        }

        // Eligibility Verifications
        if (!Schema::hasTable('eligibility_verifications')) {
            Schema::create('eligibility_verifications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
                $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
                $table->foreignId('patient_insurance_id')->constrained('patient_insurance')->onDelete('cascade');
                $table->date('verification_date');
                $table->date('service_date')->nullable(); // Date of service being verified
                $table->enum('status', ['pending', 'verified', 'failed', 'expired'])->default('pending');
                $table->boolean('is_eligible')->default(false);
                $table->date('coverage_start_date')->nullable();
                $table->date('coverage_end_date')->nullable();
                $table->json('benefits')->nullable(); // Coverage benefits
                $table->json('copay_info')->nullable();
                $table->json('deductible_info')->nullable();
                $table->text('response_data')->nullable(); // Raw API response
                $table->text('error_message')->nullable();
                $table->timestamps();

                $table->index(['patient_id', 'status']);
                $table->index(['clinic_id', 'verification_date']);
            });
        }

        // Payment Plans
        if (!Schema::hasTable('payment_plans')) {
            Schema::create('payment_plans', function (Blueprint $table) {
                $table->id();
                $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
                $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
                $table->foreignId('invoice_id')->nullable()->constrained('invoices')->onDelete('set null');
                $table->decimal('total_amount', 10, 2);
                $table->decimal('paid_amount', 10, 2)->default(0);
                $table->decimal('remaining_amount', 10, 2);
                $table->integer('installment_count');
                $table->decimal('installment_amount', 10, 2);
                $table->enum('frequency', ['weekly', 'biweekly', 'monthly'])->default('monthly');
                $table->date('start_date');
                $table->date('end_date')->nullable();
                $table->enum('status', ['active', 'completed', 'defaulted', 'cancelled'])->default('active');
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->index(['patient_id', 'status']);
            });
        }

        // Payment Plan Installments
        if (!Schema::hasTable('payment_plan_installments')) {
            Schema::create('payment_plan_installments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('payment_plan_id')->constrained('payment_plans')->onDelete('cascade');
                $table->integer('installment_number');
                $table->decimal('amount', 10, 2);
                $table->date('due_date');
                $table->date('paid_date')->nullable();
                $table->decimal('paid_amount', 10, 2)->default(0);
                $table->enum('status', ['pending', 'paid', 'overdue', 'skipped'])->default('pending');
                $table->foreignId('payment_id')->nullable()->constrained('payments')->onDelete('set null');
                $table->timestamps();

                $table->index(['payment_plan_id', 'status']);
                $table->index('due_date');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_plan_installments');
        Schema::dropIfExists('payment_plans');
        Schema::dropIfExists('eligibility_verifications');
        Schema::dropIfExists('claim_denials');
        Schema::dropIfExists('insurance_claims');
        Schema::dropIfExists('insurance_authorizations');
        Schema::dropIfExists('patient_insurance');
        Schema::dropIfExists('insurance_providers');
    }
};

