<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ProductBadgeService;

class AssignProductBadges extends Command
{
    protected $signature = 'products:assign-badges';
    protected $description = 'Assign dynamic badges to products based on sales metrics (Best Seller, Top Clinic Choice, etc.)';

    public function handle()
    {
        $this->info('Assigning product badges...');
        
        $badgeService = app(ProductBadgeService::class);
        $badgeService->assignBadges();
        
        $this->info('Product badges assigned successfully!');
    }
}

