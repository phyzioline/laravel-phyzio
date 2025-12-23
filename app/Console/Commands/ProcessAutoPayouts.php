<?php

namespace App\Console\Commands;

use App\Services\PayoutService;
use App\Services\WalletService;
use Illuminate\Console\Command;

class ProcessAutoPayouts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payouts:process-auto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process settlements and create automatic payout requests for eligible vendors';

    protected $walletService;
    protected $payoutService;

    /**
     * Create a new command instance.
     */
    public function __construct(WalletService $walletService, PayoutService $payoutService)
    {
        parent::__construct();
        $this->walletService = $walletService;
        $this->payoutService = $payoutService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting auto-payout process...');

        // Step 1: Process settlements (move pending to available)
        $this->info('Processing settlements...');
        $settledCount = $this->walletService->processSettlements();
        $this->info("Settled {$settledCount} payments");

        // Step 2: Create auto payouts
        $this->info('Creating auto-payouts...');
        $result = $this->payoutService->createAutoPayouts();

        $this->info("Created {$result['created']} auto-payouts");
        $this->info("Skipped {$result['skipped']} vendors");

        if (!empty($result['errors'])) {
            $this->warn('Errors encountered:');
            foreach ($result['errors'] as $error) {
                $this->error("  - {$error}");
            }
        }

        $this->info('Auto-payout process completed!');
        
        return Command::SUCCESS;
    }
}

