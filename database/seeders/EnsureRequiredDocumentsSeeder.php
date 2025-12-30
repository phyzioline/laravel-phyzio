<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnsureRequiredDocumentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
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
            DB::table('required_documents')->updateOrInsert(
                [
                    'role' => $doc['role'], 
                    'document_type' => $doc['document_type']
                ],
                array_merge($doc, [
                    'updated_at' => now(),
                    // Only set created_at if inserting
                ])
            );
        }
        
        $this->command->info('Required documents seeded successfully.');
    }
}
