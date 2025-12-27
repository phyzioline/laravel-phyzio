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
        Schema::create('required_documents', function (Blueprint $table) {
            $table->id();
            $table->string('role'); // vendor, company, therapist
            $table->string('document_type'); // commercial_register, tax_card, license_document, id_document, account_statement, card_image
            $table->boolean('mandatory')->default(true);
            $table->string('label'); // Display name
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->unique(['role', 'document_type']);
            $table->index('role');
        });

        // Seed default required documents
        $this->seedRequiredDocuments();
    }

    /**
     * Seed default required documents
     */
    private function seedRequiredDocuments(): void
    {
        $documents = [
            // Vendor documents
            ['role' => 'vendor', 'document_type' => 'commercial_register', 'mandatory' => true, 'label' => 'Commercial Register', 'description' => 'Valid commercial registration document', 'order' => 1],
            ['role' => 'vendor', 'document_type' => 'tax_card', 'mandatory' => true, 'label' => 'Tax Card', 'description' => 'Valid tax registration card', 'order' => 2],
            ['role' => 'vendor', 'document_type' => 'account_statement', 'mandatory' => false, 'label' => 'Account Statement', 'description' => 'Bank account statement', 'order' => 3],
            ['role' => 'vendor', 'document_type' => 'card_image', 'mandatory' => false, 'label' => 'ID Card', 'description' => 'National ID card image', 'order' => 4],
            
            // Company documents
            ['role' => 'company', 'document_type' => 'commercial_register', 'mandatory' => true, 'label' => 'Commercial Register', 'description' => 'Valid commercial registration document', 'order' => 1],
            ['role' => 'company', 'document_type' => 'tax_card', 'mandatory' => true, 'label' => 'Tax Card', 'description' => 'Valid tax registration card', 'order' => 2],
            
            // Therapist documents
            ['role' => 'therapist', 'document_type' => 'license_document', 'mandatory' => true, 'label' => 'Professional License', 'description' => 'Valid professional license document', 'order' => 1],
            ['role' => 'therapist', 'document_type' => 'id_document', 'mandatory' => true, 'label' => 'National ID', 'description' => 'National ID document', 'order' => 2],
        ];

        foreach ($documents as $doc) {
            DB::table('required_documents')->insert(array_merge($doc, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('required_documents');
    }
};

