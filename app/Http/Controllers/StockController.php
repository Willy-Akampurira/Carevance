<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockLot;
use App\Models\StockAdjustment;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    /**
     * Show all NEW stock lots (status = 'new' and quantity > 0).
     */
    public function newStock()
    {
        $stockLots = StockLot::with('drug')
            ->where('status', 'new')
            ->where('quantity', '>', 0) // hide exhausted lots
            ->latest()
            ->paginate(10);

        return view('stock.new', compact('stockLots'));
    }

    /**
     * Show all OLD stock lots (status = 'old' and quantity > 0).
     */
    public function oldStock()
    {
        $stockLots = StockLot::with('drug')
            ->where('status', 'old')
            ->where('quantity', '>', 0) // hide exhausted lots
            ->latest()
            ->paginate(10);

        return view('stock.old', compact('stockLots'));
    }

    /**
     * Show LOW stock lots (quantity below reorder level).
     */
    public function low(Request $request)
    {
        $q = trim($request->get('q', ''));

        // Query lots flagged as low stock
        $stockLots = StockLot::with('drug')
            ->whereHas('drug', function ($query) {
                $query->whereColumn('stock_lots.quantity', '<=', 'drugs.reorder_level');
            })
            ->where('quantity', '>', 0) // exclude exhausted lots
            ->when($q, function ($query) use ($q) {
                $query->whereHas('drug', function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%");
                });
            })
            ->orderBy('quantity', 'asc')
            ->paginate(10);

        // Count lots below reorder level for nav bar badge
        $lowStockCount = StockLot::with('drug')
            ->whereHas('drug', function ($query) {
                $query->whereColumn('stock_lots.quantity', '<=', 'drugs.reorder_level');
            })
            ->where('quantity', '>', 0)
            ->count();

        return view('stock.low', compact('stockLots', 'q', 'lowStockCount'));
    }

    /**
     * Show Stock Adjustment page (manual corrections, audits).
     */
    public function adjustment()
    {
        $stockLots = StockLot::with('drug')
            ->where('quantity', '>', 0) // only active lots
            ->get();

        return view('stock.adjustment', compact('stockLots'));
    }

    /**
     * Handle form submission → save adjustment and update lot.
     */
    public function adjustmentStore(Request $request)
    {
        $request->validate([
            'drug_id'      => 'required|exists:drugs,id',
            'stock_lot_id' => 'required|exists:stock_lots,id',
            'adjustment'   => 'required|integer', // +/- value
            'reason'       => 'required|string|max:255',
        ]);

        $lot = StockLot::findOrFail($request->stock_lot_id);

        // Record old quantity
        $oldQuantity = $lot->quantity;

        // Calculate new quantity
        $newQuantity = $oldQuantity + $request->adjustment;

        // Prevent negative stock
        if ($newQuantity < 0) {
            return redirect()->route('stock.adjustment')
                ->withErrors(['adjustment' => 'Adjustment would result in negative stock.']);
        }

        // Update lot in DB
        $lot->update([
            'quantity' => $newQuantity,
        ]);

        // Save adjustment record
        StockAdjustment::create([
            'drug_id'      => $request->drug_id,
            'stock_lot_id' => $request->stock_lot_id,
            'old_quantity' => $oldQuantity,
            'new_quantity' => $newQuantity,
            'reason'       => $request->reason,
            'user_id'      => Auth::id(),
        ]);

        return redirect()->route('stock.adjustment')
            ->with('success', 'Stock adjustment recorded successfully.');
    }

    /**
     * View single adjustment record.
     */
    public function adjustmentShow(string $id)
    {
        $adjustment = StockAdjustment::with(['drug', 'stockLot', 'user'])->findOrFail($id);
        return view('stock.adjustment_show', compact('adjustment'));
    }
}
