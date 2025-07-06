<?php

namespace App\Providers;
use App\Models\Advertisement;
use App\Observers\AdvertisementObserver;
use Illuminate\Support\ServiceProvider;
use App\Models\HotDeal;
use App\Observers\HotDealObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Advertisement::observe(AdvertisementObserver::class);
        HotDeal::observe(HotDealObserver::class);
    }
}
