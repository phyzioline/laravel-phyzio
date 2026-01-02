<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class DropPatientAttachmentsTable extends Command
{
    protected $signature = 'drop:patient-attachments';
    protected $description = 'Drop patient_attachments table if it exists';

    public function handle()
    {
        if (Schema::hasTable('patient_attachments')) {
            Schema::dropIfExists('patient_attachments');
            $this->info('patient_attachments table dropped successfully.');
        } else {
            $this->info('patient_attachments table does not exist.');
        }
        
        return 0;
    }
}

