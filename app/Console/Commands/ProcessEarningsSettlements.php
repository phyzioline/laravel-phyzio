<?php

namespace App\Console\Commands;

use App\Services\EarningsSettlementService;
use Illuminate\Console\Command;

class ProcessEarningsSettlements extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'earnings:settle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process earnings settlements - move pending earnings to available after hold period expires';

    protected $settlementService;

    /**
     * Create a new command instance.
     */
    public function __construct(EarningsSettlementService $settlementService)
    {
        parent::__construct();
        $this->settlementService = $settlementService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting earnings settlement process...');
        
        $result = $this->settlementService->processSettlements();
        
        $this->info("Settled {$result['settled_count']} transactions");
        $this->info("Total amount settled: " . number_format($result['total_amount'], 2));
        
        if (!empty($result['errors'])) {
            $this->warn('Errors encountered:');
            foreach ($result['errors'] as $error) {
                $this->error("  - {$error}");
            }
        }
        
        $this->info('Earnings settlement process completed!');
        
        return Command::SUCCESS;
    }
}

