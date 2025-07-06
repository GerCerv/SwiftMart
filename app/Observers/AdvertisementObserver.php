<?php

namespace App\Observers;

use App\Models\Advertisement;
use Illuminate\Support\Carbon;

class AdvertisementObserver
{
    /**
     * Handle the Advertisement "retrieved" event.
     */
    public function retrieved(Advertisement $advertisement): void
    {
        if ($advertisement->status !== 'expired' && Carbon::now()->gt($advertisement->expiration_date)) {
            $advertisement->status = 'expired';
            $advertisement->saveQuietly(); // Use saveQuietly to prevent observer recursion
        }
    }
}