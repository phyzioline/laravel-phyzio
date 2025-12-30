<?php

namespace App\Console\Commands;

use App\Services\InventoryAlertService;
use Illuminate\Console\Command;

class CheckLowStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inventory:check-low-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check all products for low stock and send alerts to vendors';

    /**
     * Execute the console command.
     */
    public function handle(InventoryAlertService $alertService)
    {
        $this->info('Checking products for low stock...');
        
        $alertCount = $alertService->checkAllProducts();
        
        if ($alertCount > 0) {
            $this->info("Sent {$alertCount} low stock alert(s) to vendors.");
        } else {
            $this->info('No low stock alerts needed at this time.');
        }
        
        return Command::SUCCESS;
    }
}

