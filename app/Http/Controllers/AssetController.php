<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\Department;
use App\Models\Location;

class AssetController extends Controller
{
    public function index()
    {
        // Mengambil data aset beserta relasinya (Eager Loading) agar query lebih ringan
        $assets = Asset::with(['category', 'department', 'location'])->latest()->get();
        return view('assets.index', compact('assets'));
    }

    public function create()
    {
        // Mengambil semua master data untuk dijadikan dropdown di form tambah
        $categories = AssetCategory::all();
        $departments = Department::all();
        $locations = Location::all();
        
        // Membuat rekomendasi kode aset otomatis (Misal: INV-20260904-123)
        $generateCode = 'INV-' . date('Ymd') . '-' . rand(100, 999);

        return view('assets.create', compact('categories', 'departments', 'locations', 'generateCode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_code' => 'required|string|max:255|unique:assets,asset_code',
            'name' => 'required|string|max:255',
            'asset_category_id' => 'required|exists:asset_categories,id',
            'department_id' => 'required|exists:departments,id',
            'location_id' => 'required|exists:locations,id',
            'purchase_date' => 'nullable|date',
            'status' => 'required|in:active,maintenance,broken,retired',
            'notes' => 'nullable|string'
        ]);

        Asset::create($validated);
        return redirect()->route('assets.index')->with('success', 'Aset baru berhasil ditambahkan!');
    }

    public function show(string $id) { }

    public function edit(string $id)
    {
        $asset = Asset::findOrFail($id);
        
        // Mengambil master data untuk pilihan dropdown
        $categories = AssetCategory::all();
        $departments = Department::all();
        $locations = Location::all();

        return view('assets.edit', compact('asset', 'categories', 'departments', 'locations'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            // Validasi unik kecuali untuk ID aset ini sendiri
            'asset_code' => 'required|string|max:255|unique:assets,asset_code,' . $id,
            'name' => 'required|string|max:255',
            'asset_category_id' => 'required|exists:asset_categories,id',
            'department_id' => 'required|exists:departments,id',
            'location_id' => 'required|exists:locations,id',
            'purchase_date' => 'nullable|date',
            'status' => 'required|in:active,maintenance,broken,retired',
            'notes' => 'nullable|string'
        ]);

        $asset = Asset::findOrFail($id);
        $asset->update($validated);
        return redirect()->route('assets.index')->with('success', 'Data aset berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $asset = Asset::findOrFail($id);
        $asset->delete();
        return redirect()->route('assets.index')->with('success', 'Aset berhasil dihapus!');
    }
}
