<?php

namespace App\Http\Controllers;

use App\Models\Drug;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class ExpiryController extends Controller
{
    /**
     * Helper: get effective threshold (hybrid).
     * Uses settings override if present, else falls back to config default.
     */
    protected function getThresholdDays(): int
    {
        return (int) (Setting::where('setting_key', 'expiry_threshold')->value('value')
            ?? config('inventory.expiry_threshold', 30));
    }

    /**
     * Display a listing of all drugs with expiry information (lot-level).
     */
    public function index(Request $request)
    {
        $q = trim($request->get('q', ''));

        $drugs = Drug::with('stockLots')
            ->when($q, function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%");
            })
            ->paginate(15);

        return view('expiry.index', compact('drugs', 'q'));
    }

    /**
     * Show drugs that are nearing expiry (within threshold days).
     */
    public function nearing(Request $request)
    {
        $thresholdDays = $this->getThresholdDays();
        $today         = Carbon::today();
        $dateLimit     = $today->copy()->addDays($thresholdDays);
        $q             = trim($request->get('q', ''));

        $drugs = Drug::with('stockLots')
            ->whereHas('stockLots', function ($qLot) use ($today, $dateLimit) {
                $qLot->whereDate('expiry_date', '>=', $today)
                     ->whereDate('expiry_date', '<=', $dateLimit);
            })
            ->when($q, function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%");
            })
            ->paginate(15);

        $drugs->getCollection()->transform(function ($drug) use ($today) {
            $nearestLot = $drug->stockLots()->orderBy('expiry_date')->first();
            $drug->days_to_expiry = $nearestLot?->expiry_date
                ? $today->diffInDays($nearestLot->expiry_date, false)
                : null;
            return $drug;
        });

        return view('expiry.nearing', compact('drugs', 'thresholdDays', 'q'));
    }

    /**
     * Show expired drugs.
     */
    public function expired(Request $request)
    {
        $q = trim($request->get('q', ''));

        $drugs = Drug::with('stockLots')
            ->expired() // scopeExpired now checks stockLots
            ->when($q, function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%");
            })
            ->paginate(15);

        $drugs->getCollection()->transform(function ($drug) {
            $expiredLot = $drug->stockLots()
                ->whereDate('expiry_date', '<=', now())
                ->orderBy('expiry_date')
                ->first();

            $drug->days_since_expiry = $expiredLot?->expiry_date
                ? Carbon::today()->diffInDays($expiredLot->expiry_date)
                : null;

            return $drug;
        });

        return view('expiry.expired', compact('drugs', 'q'));
    }

    /**
     * Show unified expiry notifications (nearing + expired).
     */
    public function notifications(Request $request)
    {
        $q             = trim($request->get('q', ''));
        $thresholdDays = $this->getThresholdDays();
        $today         = Carbon::today();
        $dateLimit     = $today->copy()->addDays($thresholdDays);

        // Build nearing + expired collections
        $nearing = Drug::with('stockLots')
            ->whereHas('stockLots', function ($qLot) use ($today, $dateLimit) {
                $qLot->whereDate('expiry_date', '>=', $today)
                     ->whereDate('expiry_date', '<=', $dateLimit);
            })
            ->get()
            ->map(function ($drug) use ($today) {
                $nearestLot = $drug->stockLots()->orderBy('expiry_date')->first();
                $drug->days_to_expiry = $nearestLot?->expiry_date
                    ? $today->diffInDays($nearestLot->expiry_date, false)
                    : null;
                $drug->notification_type = 'nearing';
                return $drug;
            });

        $expired = Drug::with('stockLots')
            ->expired()
            ->get()
            ->map(function ($drug) use ($today) {
                $expiredLot = $drug->stockLots()->whereDate('expiry_date', '<=', $today)->orderBy('expiry_date')->first();
                $drug->days_since_expiry = $expiredLot?->expiry_date
                    ? $today->diffInDays($expiredLot->expiry_date)
                    : null;
                $drug->notification_type = 'expired';
                return $drug;
            });

        $notifications = $nearing->merge($expired)->sortBy(function ($drug) {
            return $drug->stockLots()->orderBy('expiry_date')->first()?->expiry_date;
        });

        // Apply search filter
        if ($q !== '') {
            $notifications = $notifications->filter(function ($drug) use ($q) {
                return str_contains(strtolower($drug->name), strtolower($q));
            });
        }

        // Convert to paginator
        $page     = $request->get('page', 1);
        $perPage  = 15;
        $paginator = new LengthAwarePaginator(
            $notifications->forPage($page, $perPage),
            $notifications->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('expiry.notifications', [
            'notifications' => $paginator,
            'thresholdDays' => $thresholdDays,
            'q'             => $q,
        ]);
    }

    /**
     * Show the form for setting expiry threshold.
     */
    public function threshold()
    {
        $current = $this->getThresholdDays();
        return view('expiry.threshold', compact('current'));
    }

    /**
     * Update expiry threshold (frontend override).
     */
    public function updateThreshold(Request $request)
    {
        $validated = $request->validate([
            'expiry_threshold' => 'required|integer|min:1|max:365',
        ]);

        Setting::updateOrCreate(
            ['setting_key' => 'expiry_threshold'],
            ['value' => $validated['expiry_threshold']]
        );

        return redirect()->route('expiry.threshold')
                         ->with('success', 'Expiry threshold updated successfully.');
    }
}
