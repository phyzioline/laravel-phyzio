<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Schedule auto-payout processing (weekly on Sundays at 2 AM)
Schedule::command('payouts:process-auto')
    ->weekly()
    ->sundays()
    ->at('02:00')
    ->timezone('UTC')
    ->withoutOverlapping()
    ->runInBackground();

// Schedule earnings settlements (daily at 1 AM)
Schedule::command('earnings:settle')
    ->daily()
    ->at('01:00')
    ->timezone('UTC')
    ->withoutOverlapping()
    ->runInBackground();

// Release expired stock reservations (every 5 minutes)
Schedule::command('stock:release-expired')
    ->everyFiveMinutes()
    ->withoutOverlapping()
    ->runInBackground();

// Auto-close old jobs (daily at 3 AM)
Schedule::command('jobs:expire')
    ->daily()
    ->at('03:00')
    ->timezone('UTC')
    ->withoutOverlapping()
    ->runInBackground();

// Auto-cancel pending visits (hourly)
Schedule::command('visits:auto-cancel')
    ->hourly()
    ->withoutOverlapping()
    ->runInBackground();

// Database backup (daily at 2 AM)
Schedule::command('backup:database --compress')
    ->daily()
    ->at('02:00')
    ->timezone('UTC')
    ->withoutOverlapping()
    ->runInBackground();
