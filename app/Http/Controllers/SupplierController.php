<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Delivery;
use App\Models\DeliveryItem;
use App\Models\Drug;
use App\Models\SupplierInvoice;
use App\Models\SupplierPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SupplierController extends Controller
{
    /**
     * Display a listing of the suppliers.
     */
    public function index(Request $request)
    {
        $status  = $request->query('status');
        $search  = $request->query('search');
        $perPage = (int) $request->query('per_page', 10);

        $query = Supplier::query()->latest();

        if ($status && in_array($status, ['active', 'inactive'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $suppliers = $query->paginate($perPage)->withQueryString();

        return view('suppliers.index', compact('suppliers', 'status', 'search', 'perPage'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone'          => 'nullable|string|max:50',
            'email'          => 'nullable|email|max:255|unique:suppliers,email',
            'address'        => 'nullable|string|max:255',
            'status'         => 'required|in:active,inactive',
            'notes'          => 'nullable|string',
        ]);

        Supplier::create($validated);

        return redirect()->route('suppliers.index')
                         ->with('success', 'Supplier added successfully.');
    }

    public function show(int $id)
    {
        $supplier = Supplier::withTrashed()->findOrFail($id);
        return view('suppliers.show', compact('supplier'));
    }

    public function edit(int $id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, int $id)
    {
        $supplier = Supplier::findOrFail($id);

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone'          => 'nullable|string|max:50',
            'email'          => 'nullable|email|max:255|unique:suppliers,email,' . $supplier->id,
            'address'        => 'nullable|string|max:255',
            'tax_id'         => 'nullable|string|max:100',
            'status'         => 'required|in:active,inactive',
            'notes'          => 'nullable|string',
        ]);

        $supplier->update($validated);

        return redirect()->route('suppliers.index')
                         ->with('success', 'Supplier updated successfully.');
    }

    public function destroy(int $id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return redirect()->route('suppliers.index')
                         ->with('success', 'Supplier archived successfully.');
    }

    public function archived()
    {
        $suppliers = Supplier::onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('suppliers.archived', compact('suppliers'));
    }

    public function restore(int $id)
    {
        $supplier = Supplier::onlyTrashed()->findOrFail($id);
        $supplier->restore();

        return redirect()->route('suppliers.archived')
                         ->with('success', 'Supplier restored successfully.');
    }

    public function forceDelete(int $id)
    {
        $supplier = Supplier::onlyTrashed()->findOrFail($id);
        $supplier->forceDelete();

        return redirect()->route('suppliers.archived')
                         ->with('success', 'Supplier permanently deleted.');
    }

    /* ============================================================
    | Deliveries Sub-Module Logic
    |============================================================ */

    public function deliveriesIndex($supplierId, Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $deliveries = Delivery::where('supplier_id', $supplierId)
            ->when($search, fn($q) => $q->where('delivery_number', 'like', "%{$search}%"))
            ->when($status, fn($q) => $q->where('status', $status))
            ->latest('delivery_date')
            ->paginate(15);

        return view('suppliers.deliveries.index', compact('deliveries', 'search', 'status', 'supplierId'));
    }

    public function deliveriesCreate($supplierId)
    {
        $drugs = Drug::select('id', 'name')->orderBy('name')->get();
        return view('suppliers.deliveries.create', compact('supplierId', 'drugs'));
    }

    public function deliveriesStore($supplierId, Request $request)
    {
        $validated = $request->validate([
            'delivery_date' => ['required', 'date'],
            'status'        => ['required', 'in:pending,received,partially_received,cancelled'],
            'notes'         => ['nullable', 'string'],
            'items'         => ['required', 'array', 'min:1'],
            'items.*.drug_id' => ['nullable', 'exists:drugs,id'],
            'items.*.batch_number' => ['nullable', 'string', 'max:100'],
            'items.*.expiry_date' => ['nullable', 'date'],
            'items.*.quantity_received' => ['required', 'integer', 'min:0'],
            'items.*.unit_cost' => ['required', 'numeric', 'min:0'],
        ]);

        $delivery = Delivery::create([
            'supplier_id'     => $supplierId,
            'delivery_number' => 'DLV-' . Str::upper(Str::random(8)),
            'delivery_date'   => $validated['delivery_date'],
            'status'          => $validated['status'],
            'notes'           => $validated['notes'] ?? null,
        ]);

        foreach ($validated['items'] as $item) {
            DeliveryItem::create([
                'delivery_id'       => $delivery->id,
                'drug_id'           => $item['drug_id'] ?? null,
                'batch_number'      => $item['batch_number'] ?? null,
                'expiry_date'       => $item['expiry_date'] ?? null,
                'quantity_received' => $item['quantity_received'],
                'unit_cost'         => $item['unit_cost'],
            ]);
        }

        return redirect()
            ->route('suppliers.deliveries.index', $supplierId)
            ->with('success', 'Delivery recorded successfully.');
    }

    public function deliveriesShow($supplierId, $deliveryId)
    {
        $delivery = Delivery::find($deliveryId);
        return view('suppliers.deliveries.show', compact('delivery', 'supplierId'));
    }

    public function deliveriesEdit($supplierId, $deliveryId)
    {
        $delivery = Delivery::find($deliveryId);
        $drugs = Drug::select('id', 'name')->orderBy('name')->get();
        return view('suppliers.deliveries.edit', compact('delivery', 'supplierId', 'drugs'));
    }

    public function deliveriesUpdate($supplierId, $deliveryId, Request $request)
    {
        $delivery = Delivery::find($deliveryId);

        $validated = $request->validate([
            'delivery_date' => ['required', 'date'],
            'status'        => ['required', 'in:pending,received,partially_received,cancelled'],
            'notes'         => ['nullable', 'string'],
        ]);

        $delivery?->update($validated);

        return redirect()
            ->route('suppliers.deliveries.index', $supplierId)
            ->with('success', 'Delivery updated successfully.');
    }

    public function deliveriesDestroy($supplierId, $deliveryId)
    {
        $delivery = Delivery::find($deliveryId);
        $delivery?->delete();

        return redirect()
            ->route('suppliers.deliveries.index', $supplierId)
            ->with('success', 'Delivery deleted successfully.');
    }

    /* ============================================================
    | Invoices Sub-Module Logic
    |============================================================ */

    public function invoicesIndex($supplierId, Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $invoices = SupplierInvoice::where('supplier_id', $supplierId)
            ->when($search, fn($q) => $q->where('invoice_number', 'like', "%{$search}%"))
            ->when($status, fn($q) => $q->where('status', $status))
            ->latest('invoice_date')
            ->paginate(15);

        return view('suppliers.invoices.index', compact('invoices', 'search', 'status', 'supplierId'));
    }

    public function invoicesCreate($supplierId)
    {
        return view('suppliers.invoices.create', compact('supplierId'));
    }

    public function invoicesStore($supplierId, Request $request)
    {
        $validated = $request->validate([
            'invoice_date'   => ['required', 'date'],
            'invoice_number' => ['required', 'string', 'max:100', 'unique:supplier_invoices,invoice_number'],
            'amount'         => ['required', 'numeric', 'min:0'],
            'status'         => ['required', 'in:unpaid,paid,partially_paid,cancelled'],
            'notes'          => ['nullable', 'string'],
        ]);

        SupplierInvoice::create([
            'supplier_id'    => $supplierId,
            'invoice_date'   => $validated['invoice_date'],
            'invoice_number' => $validated['invoice_number'],
            'amount'         => $validated['amount'],
            'status'         => $validated['status'],
            'notes'          => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('suppliers.invoices.index', $supplierId)
            ->with('success', 'Invoice recorded successfully.');
    }

    public function invoicesShow($supplierId, $invoiceId)
    {
        $invoice = SupplierInvoice::find($invoiceId);
        return view('suppliers.invoices.show', compact('invoice', 'supplierId'));
    }

    public function invoicesEdit($supplierId, $invoiceId)
    {
        $invoice = SupplierInvoice::find($invoiceId);
        return view('suppliers.invoices.edit', compact('invoice', 'supplierId'));
    }

    public function invoicesUpdate($supplierId, Request $request, $invoiceId)
    {
        $invoice = SupplierInvoice::find($invoiceId);

        $validated = $request->validate([
            'invoice_date'   => ['required', 'date'],
            'invoice_number' => ['required', 'string', 'max:100', 'unique:supplier_invoices,invoice_number,' . $invoiceId],
            'amount'         => ['required', 'numeric', 'min:0'],
            'status'         => ['required', 'in:unpaid,paid,partially_paid,cancelled'],
            'notes'          => ['nullable', 'string'],
        ]);

        $invoice?->update($validated);

        return redirect()
            ->route('suppliers.invoices.index', $supplierId)
            ->with('success', 'Invoice updated successfully.');
    }

    public function invoicesDestroy($supplierId, $invoiceId)
    {
        $invoice = SupplierInvoice::find($invoiceId);
        $invoice?->delete();

        return redirect()
            ->route('suppliers.invoices.index', $supplierId)
            ->with('success', 'Invoice deleted successfully.');
    }

    /* ============================================================
    | Payments Sub-Module Logic
    |============================================================ */

    /**
     * List all payments for a given supplier invoice.
     */
    public function paymentsIndex($supplierId, $invoiceId)
    {
        $invoice = SupplierInvoice::find($invoiceId);
        $payments = $invoice ? $invoice->payments()->latest()->paginate(10) : collect();

        return view('suppliers.payments.index', compact('invoice', 'payments', 'supplierId', 'invoiceId'));
    }

    /**
     * Show form to create a new payment for a supplier invoice.
     */
    public function paymentsCreate($supplierId, $invoiceId)
    {
        $invoice = SupplierInvoice::find($invoiceId);
        return view('suppliers.payments.create', compact('invoice', 'supplierId', 'invoiceId'));
    }

    /**
     * Store a new payment in the database.
     */
    public function paymentsStore($supplierId, $invoiceId, Request $request)
    {
        $validated = $request->validate([
            'payment_date' => 'required|date',
            'amount'       => 'required|numeric|min:0',
            'method'       => 'required|in:cash,bank_transfer,mobile_money,cheque',
            'reference'    => 'nullable|string|max:150',
            'notes'        => 'nullable|string',
        ]);

        $validated['supplier_id'] = $supplierId;
        $validated['invoice_id']  = $invoiceId;

        SupplierPayment::create($validated);

        return redirect()
            ->route('suppliers.invoices.payments.index', [$supplierId, $invoiceId])
            ->with('success', 'Payment recorded successfully.');
    }

    /**
     * Show details of a specific payment.
     */
    public function paymentsShow($supplierId, $invoiceId, $paymentId)
    {
        $payment = SupplierPayment::find($paymentId);
        return view('suppliers.payments.show', compact('payment', 'supplierId', 'invoiceId'));
    }

    /**
     * Show form to edit an existing payment.
     */
    public function paymentsEdit($supplierId, $invoiceId, $paymentId)
    {
        $payment = SupplierPayment::find($paymentId);
        return view('suppliers.payments.edit', compact('payment', 'supplierId', 'invoiceId'));
    }

    /**
     * Update an existing payment.
     */
    public function paymentsUpdate($supplierId, $invoiceId, $paymentId, Request $request)
    {
        $payment = SupplierPayment::find($paymentId);

        $validated = $request->validate([
            'payment_date' => 'required|date',
            'amount'       => 'required|numeric|min:0',
            'method'       => 'required|in:cash,bank_transfer,mobile_money,cheque',
            'reference'    => 'nullable|string|max:150',
            'notes'        => 'nullable|string',
        ]);

        $payment?->update($validated);

        return redirect()
            ->route('suppliers.invoices.payments.show', [$supplierId, $invoiceId, $paymentId])
            ->with('success', 'Payment updated successfully.');
    }

    /**
     * Delete a payment.
     */
    public function paymentsDestroy($supplierId, $invoiceId, $paymentId)
    {
        $payment = SupplierPayment::find($paymentId);
        $payment?->delete();

        return redirect()
            ->route('suppliers.invoices.payments.index', [$supplierId, $invoiceId])
            ->with('success', 'Payment deleted successfully.');
    }
}