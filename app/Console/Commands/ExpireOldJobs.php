<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Job;
use Carbon\Carbon;

class ExpireOldJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically close jobs that are older than 60 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cutoffDate = Carbon::now()->subDays(60);

        $jobs = Job::where('status', 'open')
            ->where('created_at', '<', $cutoffDate)
            ->get();

        $count = 0;

        foreach ($jobs as $job) {
            $job->update(['status' => 'closed']);
            $count++;
            $this->info("Closed job ID: {$job->id} (Created: {$job->created_at})");
        }

        $this->info("Successfully closed {$count} old jobs.");
    }
}
