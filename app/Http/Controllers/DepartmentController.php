<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department; 

class DepartmentController extends Controller
{
    /**
     * Menampilkan halaman daftar data (Read)
     */
    public function index()
    {
        // Mengambil data dari yang paling baru ditambahkan
        $departments = Department::latest()->get(); 
        return view('departments.index', compact('departments'));
    }

    //Menampilkan form tambah data (Create)
    public function create()
    {
        return view('departments.create');
    }

    //Menyimpan data baru ke database (Store)
    public function store(Request $request)
    {
        // 1. Validasi inputan
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        // 2. Simpan ke database
        Department::create($validated);

        // 3. Kembali ke halaman index dengan pesan sukses
        return redirect()->route('departments.index')->with('success', 'Departemen berhasil ditambahkan!');
    }

    //Menampilkan detail satu data spesifik
    public function show(string $id)
    {
        $department = Department::findOrFail($id);
        return view('departments.show', compact('department'));
    }

    //Menampilkan form edit data (Edit)
    public function edit(string $id)
    {
        $department = Department::findOrFail($id);
        return view('departments.edit', compact('department'));
    }

    //Menyimpan perubahan data ke database (Update)
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $department = Department::findOrFail($id);
        $department->update($validated);

        return redirect()->route('departments.index')->with('success', 'Departemen berhasil diperbarui!');
    }

    // Menghapus data dari database (Delete)
    public function destroy(string $id)
    {
        $department = Department::findOrFail($id);
        $department->delete();

        return redirect()->route('departments.index')->with('success', 'Departemen berhasil dihapus!');
    }
}