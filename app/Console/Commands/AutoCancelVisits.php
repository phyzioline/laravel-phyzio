<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\HomeVisit;
use Carbon\Carbon;

class AutoCancelVisits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'visits:auto-cancel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically cancel pending home visits not accepted within 24 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cutoff = Carbon::now()->subHours(24);

        $visits = HomeVisit::where('status', 'pending')
            ->where('created_at', '<', $cutoff)
            ->get();

        $count = 0;

        foreach ($visits as $visit) {
            // Process refund if payment was made (assuming payment holds are implemented)
            // For now, just mark as cancelled
            
            $visit->update([
                'status' => 'cancelled',
                'cancellation_reason' => 'Auto-cancelled: Therapist did not accept within 24 hours'
            ]);
            
            // Notify Patient & Therapist
            // Notification::send(...)

            $count++;
            $this->info("Cancelled visit ID: {$visit->id}");
        }

        $this->info("Successfully cancelled {$count} pending visits.");
    }
}
