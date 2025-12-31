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
        // Daily Expenses Table
        Schema::create('daily_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
            $table->string('expense_number')->unique(); // Auto-generated expense ID
            $table->date('expense_date');
            $table->enum('category', [
                'rent',
                'salaries',
                'utilities',
                'medical_supplies',
                'equipment_maintenance',
                'marketing',
                'transportation',
                'miscellaneous'
            ]);
            $table->text('description');
            $table->enum('payment_method', [
                'cash',
                'bank_transfer',
                'pos_card',
                'mobile_wallet'
            ]);
            $table->decimal('amount', 10, 2);
            $table->string('vendor_supplier')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->string('attachment')->nullable(); // Invoice/Receipt image or PDF path
            $table->softDeletes(); // Soft delete for audit
            $table->timestamps();
            
            $table->index(['clinic_id', 'expense_date']);
            $table->index(['clinic_id', 'category']);
            $table->index('expense_date');
        });

        // Enhanced Patient Invoices Table (if not exists, update if exists)
        if (!Schema::hasTable('patient_invoices')) {
            Schema::create('patient_invoices', function (Blueprint $table) {
                $table->id();
                $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
                $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
                $table->string('invoice_number')->unique();
                $table->string('treatment_plan')->nullable(); // Treatment plan or sessions package
                $table->decimal('total_cost', 10, 2);
                $table->decimal('discount', 10, 2)->default(0);
                $table->decimal('final_amount', 10, 2); // Final payable amount
                $table->date('invoice_date');
                $table->date('due_date')->nullable();
                $table->enum('status', ['unpaid', 'partially_paid', 'paid'])->default('unpaid');
                $table->text('notes')->nullable();
                $table->softDeletes();
                $table->timestamps();
                
                $table->index(['clinic_id', 'patient_id']);
                $table->index(['clinic_id', 'status']);
                $table->index('invoice_date');
            });
        } else {
            // Add missing columns if table exists
            Schema::table('patient_invoices', function (Blueprint $table) {
                if (!Schema::hasColumn('patient_invoices', 'clinic_id')) {
                    $table->foreignId('clinic_id')->nullable()->after('id')->constrained('clinics')->onDelete('cascade');
                }
                if (!Schema::hasColumn('patient_invoices', 'treatment_plan')) {
                    $table->string('treatment_plan')->nullable()->after('patient_id');
                }
                if (!Schema::hasColumn('patient_invoices', 'total_cost')) {
                    $table->decimal('total_cost', 10, 2)->after('treatment_plan');
                }
                if (!Schema::hasColumn('patient_invoices', 'discount')) {
                    $table->decimal('discount', 10, 2)->default(0)->after('total_cost');
                }
                if (!Schema::hasColumn('patient_invoices', 'final_amount')) {
                    $table->decimal('final_amount', 10, 2)->after('discount');
                }
                if (!Schema::hasColumn('patient_invoices', 'status')) {
                    $table->enum('status', ['unpaid', 'partially_paid', 'paid'])->default('unpaid')->after('invoice_date');
                }
            });
        }

        // Patient Payments Table
        Schema::create('patient_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('invoice_id')->nullable()->constrained('patient_invoices')->onDelete('cascade');
            $table->string('payment_number')->unique();
            $table->date('payment_date');
            $table->decimal('payment_amount', 10, 2);
            $table->enum('payment_method', [
                'cash',
                'bank_transfer',
                'pos_card',
                'mobile_wallet'
            ]);
            $table->foreignId('received_by')->constrained('users')->onDelete('restrict');
            $table->text('notes')->nullable();
            $table->string('receipt_path')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            $table->index(['clinic_id', 'patient_id']);
            $table->index(['clinic_id', 'invoice_id']);
            $table->index('payment_date');
        });

        // Financial Audit Log Table
        Schema::create('financial_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
            $table->string('action'); // created, updated, deleted
            $table->string('entity_type'); // daily_expense, patient_invoice, patient_payment
            $table->unsignedBigInteger('entity_id');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->text('old_values')->nullable(); // JSON
            $table->text('new_values')->nullable(); // JSON
            $table->text('notes')->nullable();
            $table->timestamp('created_at');
            
            $table->index(['clinic_id', 'entity_type', 'entity_id']);
            $table->index('created_at');
        });

        // Monthly Expense Summaries (for performance optimization)
        Schema::create('monthly_expense_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
            $table->year('year');
            $table->tinyInteger('month'); // 1-12
            $table->decimal('total_expenses', 10, 2)->default(0);
            $table->decimal('rent', 10, 2)->default(0);
            $table->decimal('salaries', 10, 2)->default(0);
            $table->decimal('utilities', 10, 2)->default(0);
            $table->decimal('medical_supplies', 10, 2)->default(0);
            $table->decimal('equipment_maintenance', 10, 2)->default(0);
            $table->decimal('marketing', 10, 2)->default(0);
            $table->decimal('transportation', 10, 2)->default(0);
            $table->decimal('miscellaneous', 10, 2)->default(0);
            $table->integer('expense_count')->default(0);
            $table->decimal('average_daily_expense', 10, 2)->default(0);
            $table->decimal('highest_expense_day', 10, 2)->default(0);
            $table->decimal('lowest_expense_day', 10, 2)->default(0);
            $table->timestamp('last_calculated_at')->nullable();
            $table->timestamps();
            
            $table->unique(['clinic_id', 'year', 'month']);
            $table->index(['clinic_id', 'year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_expense_summaries');
        Schema::dropIfExists('financial_audit_logs');
        Schema::dropIfExists('patient_payments');
        Schema::dropIfExists('patient_invoices');
        Schema::dropIfExists('daily_expenses');
    }
};

