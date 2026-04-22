<?php

namespace App\Http\Controllers;

use App\Models\DrugCategory;
use Illuminate\Http\Request;

class DrugCategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index()
    {
        $categories = DrugCategory::latest()->paginate(10);
        return view('drugs.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('drugs.categories.create');
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:drug_categories,name',
            'description' => 'nullable|string',
        ]);

        DrugCategory::create($validated);

        return redirect()->route('drugs.categories.index')
                         ->with('success', 'Category added successfully.');
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(DrugCategory $category)
    {
        return view('drugs.categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, DrugCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:drug_categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return redirect()->route('drugs.categories.index')
                         ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(DrugCategory $category)
    {
        $category->delete();

        return redirect()->route('drugs.categories.index')
                         ->with('success', 'Category deleted successfully.');
    }
}
