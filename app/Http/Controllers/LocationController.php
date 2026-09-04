<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;

class LocationController extends Controller
{
    public function index()
    {
        // Mengambil semua lokasi beserta nama induknya (jika ada)
        $locations = Location::with('parent')->latest()->get();
        return view('locations.index', compact('locations'));
    }

    public function create()
    {
        $parents = Location::all(); // Untuk pilihan dropdown parent
        return view('locations.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:locations,id'
        ]);

        Location::create($validated);
        return redirect()->route('locations.index')->with('success', 'Lokasi berhasil ditambahkan!');
    }

    public function show(string $id) { }

    public function edit(string $id)
    {
        $location = Location::findOrFail($id);
        // Cegah lokasi menjadi parent bagi dirinya sendiri
        $parents = Location::where('id', '!=', $id)->get(); 
        return view('locations.edit', compact('location', 'parents'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:locations,id'
        ]);

        $location = Location::findOrFail($id);
        $location->update($validated);

        return redirect()->route('locations.index')->with('success', 'Lokasi berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $location = Location::findOrFail($id);
        $location->delete();
        return redirect()->route('locations.index')->with('success', 'Lokasi berhasil dihapus!');
    }
}