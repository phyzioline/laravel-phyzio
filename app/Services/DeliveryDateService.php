<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Order;

class DeliveryDateService
{
    /**
     * Calculate estimated delivery date based on location and shipping method.
     */
    public function calculateDeliveryDate($city, $governorate = null, $shippingMethod = 'standard')
    {
        // Base delivery days based on shipping method
        $baseDays = match($shippingMethod) {
            'express' => 1,
            'standard' => 2,
            'economy' => 4,
            default => 2,
        };
        
        // Add processing time (1 day for order processing)
        $processingDays = 1;
        
        // Calculate total days
        $totalDays = $baseDays + $processingDays;
        
        // Start from tomorrow (orders placed today start processing tomorrow)
        $deliveryDate = Carbon::now()->addDays($totalDays);
        
        // Skip weekends (Friday and Saturday in Egypt)
        while ($deliveryDate->isFriday() || $deliveryDate->isSaturday()) {
            $deliveryDate->addDay();
        }
        
        return $deliveryDate;
    }
    
    /**
     * Get delivery date message for display.
     */
    public function getDeliveryMessage($city, $governorate = null, $shippingMethod = 'standard')
    {
        $date = $this->calculateDeliveryDate($city, $governorate, $shippingMethod);
        
        $dayName = $date->format('l'); // Full day name
        $formattedDate = $date->format('M d'); // Dec 25
        
        return [
            'date' => $date,
            'message' => "Arrives {$dayName}, {$formattedDate}",
            'short_message' => "Arrives {$formattedDate}",
            'days' => Carbon::now()->diffInDays($date),
        ];
    }
    
    /**
     * Get FREE delivery message if applicable.
     */
    public function getFreeDeliveryMessage($total, $threshold = 500)
    {
        if ($total >= $threshold) {
            return "FREE Delivery on orders over {$threshold} EGP";
        }
        
        $remaining = $threshold - $total;
        return "Add {$remaining} EGP more for FREE Delivery";
    }
}

