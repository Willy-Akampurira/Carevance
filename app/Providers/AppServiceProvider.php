<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\StockLot;
use App\Models\Drug;
use App\Models\Setting;
use Illuminate\Support\Carbon;

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
            // ── 1. Tenant Safeguard Guard ──────────────────────────────────────
            // If no tenant context is resolved yet (e.g. landing page or setup routing),
            // bypass queries completely and inject defaults to prevent SQL crashes.
            if (!app()->bound('currentTenant')) {
                $view->with([
                    'lowStockCount' => 0,
                    'expiryCount' => 0,
                ]);
                return;
            }

            // ── 2. Tenant-Scoped Low Stock Count ───────────────────────────────
            // Under multi-tenancy, BelongsToTenant automatically hooks into both StockLot
            // and Drug queries to pull metrics exclusively for the current tenant.
            $lowStockCount = StockLot::whereHas('drug', function ($query) {
                    $query->whereColumn('stock_lots.quantity', '<=', 'drugs.reorder_level');
                })
                ->count();

            // ── 3. Dynamic Threshold Calculation ──────────────────────────────
            // Fetches setting records scoped strictly to the current tenant clinic.
            $thresholdDays = (int) (Setting::where('setting_key', 'expiry_threshold')->value('value')
                ?? config('inventory.expiry_threshold', 30));

            $today     = Carbon::today();
            $dateLimit = $today->copy()->addDays($thresholdDays);

            // ── 4. Expiry Metrics Processing ──────────────────────────────────
            $nearingCount = Drug::whereDate('expiry_date', '>=', $today)
                ->whereDate('expiry_date', '<=', $dateLimit)
                ->count();

            $expiredCount = Drug::expired()->count();

            $expiryCount = $nearingCount + $expiredCount;

            // ── 5. Global View Composer Registration ──────────────────────────
            $view->with(compact('lowStockCount', 'expiryCount'));
        });
    }
}