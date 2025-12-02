<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of purchase orders.
     */
    public function index(Request $request)
    {
        $status  = $request->query('status');
        $search  = $request->query('search');
        $perPage = (int) $request->query('per_page', 10);

        $query = PurchaseOrder::with('supplier')->latest();

        if ($status && in_array($status, ['pending','approved','received','cancelled'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('supplier', fn($s) => $s->where('name', 'like', "%{$search}%"));
            });
        }

        $orders = $query->paginate($perPage)->withQueryString();

        return view('purchase_orders.index', compact('orders','status','search','perPage'));
    }

    /**
     * Show the form for creating a new purchase order.
     */
    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        return view('purchase_orders.create', compact('suppliers'));
    }

    /**
     * Store a newly created purchase order in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date|after_or_equal:order_date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.drug_id' => 'nullable|exists:drugs,id',
            'items.*.description' => 'required_without:items.*.drug_id|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $orderNumber = 'PO-' . Str::upper(Str::random(8));

        $order = PurchaseOrder::create([
            'supplier_id' => $validated['supplier_id'],
            'order_number' => $orderNumber,
            'order_date' => $validated['order_date'],
            'expected_delivery_date' => $validated['expected_delivery_date'] ?? null,
            'status' => 'pending',
            'total_amount' => 0,
            'notes' => $validated['notes'] ?? null,
        ]);

        $total = 0;
        foreach ($validated['items'] as $item) {
            $lineTotal = (int)$item['quantity'] * (float)$item['unit_price'];

            PurchaseOrderItem::create([
                'purchase_order_id' => $order->id,
                'drug_id' => $item['drug_id'] ?? null,
                'description' => $item['description'] ?? '',
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                // ❌ no line_total here
            ]);

            $total += $lineTotal;
        }

        $order->update(['total_amount' => $total]);

        return redirect()->route('purchaseOrders.show', $order)
            ->with('success', 'Purchase Order created successfully.');
    }

    /**
     * Display the specified purchase order.
     */
    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['supplier','items.drug']);
        return view('purchase_orders.show', compact('purchaseOrder'));
    }

    /**
     * Show the form for editing the specified purchase order.
     */
    public function edit(PurchaseOrder $purchaseOrder)
    {
        $suppliers = Supplier::orderBy('name')->get();
        $purchaseOrder->load(['items.drug','supplier']);
        return view('purchase_orders.edit', compact('purchaseOrder','suppliers'));
    }

    /**
     * Update the specified purchase order in storage.
     */
    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date|after_or_equal:order_date',
            'status' => 'required|in:pending,approved,received,cancelled',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable|exists:purchase_order_items,id',
            'items.*.drug_id' => 'nullable|exists:drugs,id',
            'items.*.description' => 'required_without:items.*.drug_id|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $purchaseOrder->update([
            'supplier_id' => $validated['supplier_id'],
            'order_date' => $validated['order_date'],
            'expected_delivery_date' => $validated['expected_delivery_date'] ?? null,
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
        ]);

        // Sync items
        $existingIds = collect($validated['items'])->pluck('id')->filter()->all();
        PurchaseOrderItem::where('purchase_order_id', $purchaseOrder->id)
            ->whereNotIn('id', $existingIds)->delete();

        $total = 0;
        foreach ($validated['items'] as $item) {
            $lineTotal = (int)$item['quantity'] * (float)$item['unit_price'];

            if (!empty($item['id'])) {
                PurchaseOrderItem::where('id', $item['id'])->update([
                    'drug_id' => $item['drug_id'] ?? null,
                    'description' => $item['description'] ?? '',
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    // ❌ no line_total here
                ]);
            } else {
                PurchaseOrderItem::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'drug_id' => $item['drug_id'] ?? null,
                    'description' => $item['description'] ?? '',
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    // ❌ no line_total here
                ]);
            }

            $total += $lineTotal;
        }

        $purchaseOrder->update(['total_amount' => $total]);

        return redirect()->route('purchaseOrders.show', $purchaseOrder)
            ->with('success', 'Purchase Order updated successfully.');
    }

    /**
     * Remove the specified purchase order (soft delete).
     */
    public function destroy(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->delete();
        return redirect()->route('purchaseOrders.index')
            ->with('success', 'Purchase Order archived.');
    }

    /**
     * Mark a purchase order as received.
     */
    public function receive(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->update(['status' => 'received']);
        return back()->with('success', 'Purchase Order marked as received.');
    }
}
