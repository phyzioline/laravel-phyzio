<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\StockReservationService;

class ReleaseExpiredStockReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:release-expired {--timeout=15 : Minutes after which to release reservations}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Release stock reservations for orders with expired pending payments';

    /**
     * Execute the console command.
     */
    public function handle(StockReservationService $stockService)
    {
        $timeout = (int) $this->option('timeout');
        
        $this->info("Releasing stock reservations older than {$timeout} minutes...");
        
        $count = $stockService->releaseExpiredReservations($timeout);
        
        if ($count > 0) {
            $this->info("✅ Released stock for {$count} expired order(s)");
        } else {
            $this->info("No expired reservations found");
        }
        
        return Command::SUCCESS;
    }
}
