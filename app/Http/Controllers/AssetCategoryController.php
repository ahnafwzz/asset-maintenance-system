<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssetCategory;

class AssetCategoryController extends Controller
{
    public function index()
    {
        $categories = AssetCategory::latest()->get();
        return view('asset_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('asset_categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        AssetCategory::create($validated);
        return redirect()->route('asset-categories.index')->with('success', 'Kategori aset berhasil ditambahkan!');
    }

    public function show(string $id) {}

    public function edit(string $id)
    {
        $category = AssetCategory::findOrFail($id);
        return view('asset_categories.edit', compact('category'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $category = AssetCategory::findOrFail($id);
        $category->update($validated);
        return redirect()->route('asset-categories.index')->with('success', 'Kategori aset berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $category = AssetCategory::findOrFail($id);
        $category->delete();
        return redirect()->route('asset-categories.index')->with('success', 'Kategori aset berhasil dihapus!');
    }
}