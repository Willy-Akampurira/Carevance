<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\StockLot;
use App\Models\Drug;
use Illuminate\Support\Carbon;
use App\Models\Setting;

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
        View::composer('layouts.app', function ($view) {
            // Low stock count
            $lowStockCount = StockLot::with('drug')
                ->whereHas('drug', function ($query) {
                    $query->whereColumn('stock_lots.quantity', '<=', 'drugs.reorder_level');
                })
                ->count();

            // Expiry notifications count (nearing + expired)
            $thresholdDays = (int) (Setting::where('setting_key', 'expiry_threshold')->value('value')
                ?? config('inventory.expiry_threshold', 30));

            $today     = Carbon::today();
            $dateLimit = $today->copy()->addDays($thresholdDays);

            $nearingCount = Drug::whereDate('expiry_date', '>=', $today)
                ->whereDate('expiry_date', '<=', $dateLimit)
                ->count();

            $expiredCount = Drug::expired()->count();

            $expiryCount = $nearingCount + $expiredCount;

            // Share globally
            $view->with(compact('lowStockCount', 'expiryCount'));
        });
    }
}
