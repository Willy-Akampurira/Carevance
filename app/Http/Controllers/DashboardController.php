<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Drug;
use App\Models\Prescription;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $now   = Carbon::now();

        // Summary metrics for cards → lot-aware
        $metrics = [
            'totalPatients'       => Patient::count(),
            'totalDrugs'          => Drug::count(),
            'outOfStock'          => (int) Drug::whereHas('stockLots', function ($q) {
                                        $q->where('quantity', '<=', 0);
                                    })->count(),
            'expiredDrugs'        => (int) Drug::whereHas('stockLots', function ($q) use ($now) {
                                        $q->where('expiry_date', '<', $now);
                                    })->count(),
            'activePrescriptions' => (int) Prescription::where('status', 'active')->count(),
        ];

        // Patients trend (line chart)
        $patientsTrend = Patient::select(
                DB::raw('COUNT(*) as count'),
                DB::raw('MONTH(entry_date) as month')
            )
            ->whereNotNull('entry_date')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Drug stock distribution (pie chart) → lot-aware
        $drugStock = [
            'inStock'    => (int) Drug::whereHas('stockLots', function ($q) use ($now) {
                                        $q->where('quantity', '>', 0)
                                           ->where('expiry_date', '>=', $now);
                                    })->count(),
            'outOfStock' => (int) Drug::whereHas('stockLots', function ($q) {
                                        $q->where('quantity', '<=', 0);
                                    })->count(),
            'expired'    => (int) Drug::whereHas('stockLots', function ($q) use ($now) {
                                        $q->where('expiry_date', '<', $now);
                                    })->count(),
            'reserved'   => (int) Drug::where('reserved', true)->count(),
        ];

        // Prescriptions by category (bar chart)
        $prescriptionsData = Prescription::select('category', DB::raw('COUNT(*) as count'))
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();

        // Recent activity
        $recentActivity = Prescription::with(['patient', 'drug'])
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // Today's report snapshot → lot-aware expiry
        $todaysReport = [
            'patientsToday'            => (int) Patient::whereDate('entry_date', $today)->count(),
            'prescriptionsToday'       => (int) Prescription::whereDate('created_at', $today)->count(),
            'activePrescriptionsToday' => (int) Prescription::whereDate('created_at', $today)->where('status', 'active')->count(),
            'drugsExpiredToday'        => (int) Drug::whereHas('stockLots', function ($q) use ($today) {
                                            $q->whereDate('expiry_date', '=', $today);
                                        })->count(),
            'outOfStockNow'            => (int) Drug::whereHas('stockLots', function ($q) {
                                            $q->where('quantity', '<=', 0);
                                        })->count(),
        ];

        return view('dashboard', compact(
            'metrics',
            'patientsTrend',
            'drugStock',
            'prescriptionsData',
            'recentActivity',
            'todaysReport'
        ));
    }
}
