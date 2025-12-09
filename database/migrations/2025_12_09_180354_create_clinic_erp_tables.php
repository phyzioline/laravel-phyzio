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
        // 1. Update Patients Table
        if (Schema::hasTable('patients')) {
            Schema::table('patients', function (Blueprint $table) {
                if (!Schema::hasColumn('patients', 'primary_condition')) $table->string('primary_condition')->nullable();
                if (!Schema::hasColumn('patients', 'referring_doctor')) $table->string('referring_doctor')->nullable();
                if (!Schema::hasColumn('patients', 'insurance_provider')) $table->string('insurance_provider')->nullable();
                if (!Schema::hasColumn('patients', 'insurance_number')) $table->string('insurance_number')->nullable();
                if (!Schema::hasColumn('patients', 'emergency_contact_name')) $table->string('emergency_contact_name')->nullable();
                if (!Schema::hasColumn('patients', 'emergency_contact_phone')) $table->string('emergency_contact_phone')->nullable();
                if (!Schema::hasColumn('patients', 'status')) $table->string('status')->default('active'); // active, discharged, on_hold
            });
        }

        // 2. Update Treatment Plans (Just ensure key fields exist)
        if (Schema::hasTable('treatment_plans')) {
            Schema::table('treatment_plans', function (Blueprint $table) {
               if (!Schema::hasColumn('treatment_plans', 'modality_list')) $table->json('modality_list')->nullable(); // Arrays of modalities
               if (!Schema::hasColumn('treatment_plans', 'goals_list')) $table->json('goals_list')->nullable(); 
            });
        }

        // 3. Create Invoices Table
        if (!Schema::hasTable('invoices')) {
            Schema::create('invoices', function (Blueprint $table) {
                $table->id();
                $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
                $table->string('invoice_number')->unique();
                $table->decimal('amount', 10, 2);
                $table->date('due_date')->nullable();
                $table->string('status')->default('pending'); // pending, paid, overdue
                $table->string('payment_method')->nullable(); // cash, credit_card, insurance
                $table->string('pdf_path')->nullable();
                $table->timestamps();
            });
        }

        // 4. Create Inventory Table
        if (!Schema::hasTable('clinic_inventory')) {
            Schema::create('clinic_inventory', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('category')->nullable(); // consumables, equipment
                $table->integer('quantity')->default(0);
                $table->string('unit')->default('pcs'); // pcs, box, ml
                $table->integer('reorder_level')->default(5);
                $table->string('supplier')->nullable();
                $table->date('expiry_date')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinic_inventory');
        Schema::dropIfExists('invoices');
        // We generally don't drop columns in down for updates unless strictly needed, to avoid data loss on rollback of this specific file if other migrations depend on it.
    }
};
