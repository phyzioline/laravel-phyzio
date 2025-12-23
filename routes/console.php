<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule auto-payout processing (weekly on Sundays at 2 AM)
Schedule::command('payouts:process-auto')
    ->weekly()
    ->sundays()
    ->at('02:00')
    ->timezone('UTC')
    ->withoutOverlapping()
    ->runInBackground();
