<?php

namespace App\Observers;

use App\Models\HotDeal;
use Illuminate\Support\Carbon;

class HotDealObserver
{
    /**
     * Handle the HotDeal "retrieved" event.
     */
    public function retrieved(HotDeal $hotDeal): void
    {
        $now = Carbon::now();
        
        // Check if deal should be active based on dates
        $shouldBeActive = $hotDeal->start_date <= $now && 
                         $hotDeal->end_date >= $now;
        
        // Only update if status doesn't match what it should be
        if ($hotDeal->is_active !== $shouldBeActive) {
            $hotDeal->is_active = $shouldBeActive;
            $hotDeal->saveQuietly(); // Prevent observer recursion
        }
    }
}