<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the suppliers.
     */
    public function index(Request $request)
    {
        // Optional filters for search and status
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

    /**
     * Show the form for creating a new supplier.
     */
    public function create()
    {
        return view('suppliers.create');
    }

    /**
     * Store a newly created supplier in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone'          => 'nullable|string|max:50',
            'email'          => 'nullable|email|max:255|unique:suppliers,email',
            'address'        => 'nullable|string|max:255',
            'tax_id'         => 'nullable|string|max:100',
            'status'         => 'required|in:active,inactive',
            'notes'          => 'nullable|string',
        ]);

        Supplier::create($validated);

        return redirect()->route('suppliers.index')
                         ->with('success', 'Supplier added successfully.');
    }

    /**
     * Display the specified supplier.
     */
    public function show(int $id)
    {
        $supplier = Supplier::withTrashed()->findOrFail($id);
        return view('suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified supplier.
     */
    public function edit(int $id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified supplier in storage.
     */
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

    /**
     * Soft delete the specified supplier.
     */
    public function destroy(int $id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete(); // Soft delete

        return redirect()->route('suppliers.index')
                         ->with('success', 'Supplier archived successfully.');
    }

    /**
     * Show a list of archived suppliers.
     */
    public function archived()
    {
        $suppliers = Supplier::onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('suppliers.archived', compact('suppliers'));
    }

    /**
     * Restore a soft-deleted supplier.
     */
    public function restore(int $id)
    {
        $supplier = Supplier::onlyTrashed()->findOrFail($id);
        $supplier->restore();

        return redirect()->route('suppliers.archived')
                         ->with('success', 'Supplier restored successfully.');
    }

    /**
     * Permanently delete a supplier.
     */
    public function forceDelete(int $id)
    {
        $supplier = Supplier::onlyTrashed()->findOrFail($id);
        $supplier->forceDelete();

        return redirect()->route('suppliers.archived')
                         ->with('success', 'Supplier permanently deleted.');
    }
}
