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
        // Drop table if it exists (from previous failed migration)
        if (Schema::hasTable('patient_attachments')) {
            Schema::dropIfExists('patient_attachments');
        }
        
        Schema::create('patient_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->onDelete('set null');
            
            // File information
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type')->nullable(); // pdf, image, xray, report, etc.
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable(); // in bytes
            
            // Metadata
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->enum('category', ['xray', 'mri', 'lab_report', 'doctor_note', 'prescription', 'insurance', 'other'])->default('other');
            $table->date('document_date')->nullable(); // Date of the document (e.g., X-ray date)
            
            // Relationships
            $table->foreignId('appointment_id')->nullable()->constrained('clinic_appointments')->onDelete('set null');
            $table->foreignId('invoice_id')->nullable()->constrained('patient_invoices')->onDelete('set null');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('patient_id');
            $table->index('clinic_id');
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_attachments');
    }
};

