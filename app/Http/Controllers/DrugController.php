<?php

namespace App\Http\Controllers;

use App\Models\Drug;
use App\Models\DrugCategory;
use Illuminate\Http\Request;

class DrugController extends Controller
{
    /**
     * Display a listing of drugs.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $drugs = Drug::with('category', 'stockLots')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('drugs.index', compact('drugs', 'search'));
    }

    /**
     * Show the form for creating a new drug.
     */
    public function create()
    {
        $categories = DrugCategory::all();
        return view('drugs.create', compact('categories'));
    }

    /**
     * Store a newly created drug in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'category_id'   => 'nullable|exists:drug_categories,id',
            'unit'          => 'required|string|max:50',
            'reserved'      => 'boolean',
            'expiry_date'   => 'nullable|date',
            'reorder_level' => 'required|integer|min:0',
            'description'   => 'nullable|string',
            // Lot-level fields
            'quantity'      => 'required|integer|min:0',
        ]);

        // Create drug record (without quantity at drug level)
        $drug = Drug::create($validated);

        // ✅ Create initial stock lot as new
        $drug->stockLots()->create([
            'name'          => $drug->name,
            'category_id'   => $drug->category_id,
            'description'   => $drug->description,
            'unit'          => $drug->unit,
            'quantity'      => $validated['quantity'],
            'reserved'      => $drug->reserved,
            'expiry_date'   => $validated['expiry_date'] ?? null,
            'reorder_level' => $drug->reorder_level,
            'status'        => 'new',
        ]);

        return redirect()->route('drugs.index')->with('success', 'Drug added successfully.');
    }

    /**
     * Display the specified drug.
     */
    public function show(Drug $drug)
    {
        return view('drugs.show', compact('drug'));
    }

    /**
     * Show the form for editing the specified drug.
     */
    public function edit(Drug $drug)
    {
        $categories = DrugCategory::all();
        return view('drugs.edit', compact('drug', 'categories'));
    }

    /**
     * Update the specified drug in storage.
     */
    public function update(Request $request, Drug $drug)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'category_id'   => 'nullable|exists:drug_categories,id',
            'unit'          => 'required|string|max:50',
            'reserved'      => 'boolean',
            'reorder_level' => 'required|integer|min:0',
            'description'   => 'nullable|string',
        ]);

        $drug->update($validated);

        return redirect()->route('drugs.index')->with('success', 'Drug updated successfully.');
    }

    /**
     * Soft delete the specified drug.
     */
    public function destroy(Drug $drug)
    {
        $drug->delete();
        return redirect()->route('drugs.index')->with('success', 'Drug moved to trash.');
    }

    /**
     * Show trashed drugs.
     */
    public function trashed()
    {
        $drugs = Drug::onlyTrashed()->with('category')->paginate(10);
        return view('drugs.trashed', compact('drugs'));
    }

    /**
     * Restore a trashed drug.
     */
    public function restore($id)
    {
        $drug = Drug::onlyTrashed()->findOrFail($id);
        $drug->restore();

        return redirect()->route('drugs.trashed')->with('success', 'Drug restored successfully.');
    }

    /**
     * Permanently delete a trashed drug.
     */
    public function forceDelete($id)
    {
        $drug = Drug::onlyTrashed()->findOrFail($id);
        $drug->forceDelete();

        return redirect()->route('drugs.trashed')->with('success', 'Drug permanently deleted.');
    }

    /**
     * ✅ Show the restock form for a specific drug.
     */
    public function showRestockForm(Drug $drug)
    {
        return view('drugs.restock', compact('drug'));
    }

    /**
     * ✅ Handle the restock submission.
     */
    public function restock(Request $request, Drug $drug)
    {
        $validated = $request->validate([
            'quantity'    => 'required|integer|min:1',
            'expiry_date' => 'nullable|date',
        ]);

        // Mark existing stock lots as old
        $drug->stockLots()->update(['status' => 'old']);

        // Insert new stock lot as new
        $drug->stockLots()->create([
            'name'          => $drug->name,
            'category_id'   => $drug->category_id,
            'description'   => $drug->description,
            'unit'          => $drug->unit,
            'quantity'      => $validated['quantity'],
            'reserved'      => $drug->reserved,
            'expiry_date'   => $validated['expiry_date'] ?? null,
            'reorder_level' => $drug->reorder_level,
            'status'        => 'new',
        ]);

        return redirect()->route('drugs.index')->with('success', 'Drug restocked successfully.');
    }
}
