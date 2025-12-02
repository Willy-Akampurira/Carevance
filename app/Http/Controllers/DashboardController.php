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

        // Summary metrics for cards
        $metrics = [
            'totalPatients'       => Patient::count(),
            'totalDrugs'          => Drug::count(),
            'outOfStock'          => Drug::where('quantity', '<=', 0)->count(),
            'expiredDrugs'        => Drug::whereDate('expiry_date', '<', $today)->count(),
            'activePrescriptions' => Prescription::where('status', 'active')->count(),
        ];

        // Patients trend (line chart) → count per month
        $patientsTrend = Patient::select(
                DB::raw('COUNT(*) as count'),
                DB::raw('MONTH(created_at) as month')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Drug stock distribution (pie chart)
        $drugStock = [
            'inStock'    => Drug::where('quantity', '>', 0)->count(),
            'outOfStock' => Drug::where('quantity', '=', 0)->count(),
            'expired'    => Drug::whereDate('expiry_date', '<', $today)->count(),
            'reserved'   => Drug::where('reserved', true)->count(),
        ];

        // Prescriptions by category (bar chart)
        $prescriptionsData = Prescription::select('category', DB::raw('COUNT(*) as count'))
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();

        // Recent activity (latest 5 prescriptions)
        $recentActivity = Prescription::with(['patient', 'drug'])
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // Today's report snapshot
        $todaysReport = [
            'patientsToday'              => Patient::whereDate('created_at', $today)->count(),
            'prescriptionsToday'         => Prescription::whereDate('created_at', $today)->count(),
            'activePrescriptionsToday'   => Prescription::whereDate('created_at', $today)->where('status', 'active')->count(),
            'drugsExpiredToday'          => Drug::whereDate('expiry_date', $today)->count(),
            'outOfStockNow'              => Drug::where('quantity', '<=', 0)->count(),
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
