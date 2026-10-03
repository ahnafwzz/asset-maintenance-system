<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Menampilkan daftar pengguna dengan fitur Search, Filter, Sort, dan Pagination.
     * Menggunakan Query Builder untuk menghitung statistik summary.
     */
    public function index(Request $request)
    {
        // 1. Summary Statistics (Tetap pakai Query Builder)
        $totalUsers = User::count();
        $pendingUsers = User::where('approval_status', 'pending')->count();
        $approvedUsers = User::where('approval_status', 'approved')->count();

        // Ambil daftar role untuk Dropdown Filter (kecuali Super Admin agar tidak bocor)
        $roles = \Spatie\Permission\Models\Role::where('name', '!=', 'Super Admin')->get();

        // 2. Main Query Builder
        $query = User::with('roles')->where('id', '!=', auth()->id());

        // FITUR PENCARIAN
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        // FITUR FILTER STATUS
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('approval_status', $request->status);
        }

        // FITUR FILTER ROLE (Mencari di tabel relasi Spatie)
        if ($request->filled('role') && $request->role !== 'all') {
            $roleFilter = $request->role;
            $query->whereHas('roles', function($q) use ($roleFilter) {
                $q->where('name', $roleFilter);
            });
        }

        $query->latest();
        $users = $query->paginate(10)->withQueryString();
        
        return view('users.index', compact('users', 'totalUsers', 'pendingUsers', 'approvedUsers', 'roles'));
    }

    /**
     * Menyetujui kenaikan role yang diminta user.
     */
    public function approve(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        if ($user->requested_role && $user->approval_status === 'pending') {
            // Tukar role lama dengan role baru yang diminta (syncRoles menghapus yang lama)
            $user->syncRoles([$user->requested_role]);
            
            // Ubah status Approved untuk notifikasi di Dashboard user, bersihkan memo request
            $user->update([
                'approval_status' => 'approved',
                'requested_role' => null
            ]);

            return redirect()->route('users.index')->with('success', 'Akses ' . $user->name . ' disetujui. Role sekarang: ' . $user->roles->first()->name);
        }

        return redirect()->route('users.index')->with('error', 'Tidak ada request valid untuk akun ini.');
    }

    /**
     * Menolak kenaikan role yang diminta user.
     */
    public function reject(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        if ($user->approval_status === 'pending') {
            // Set status ke none untuk membersihkan constraint, dan hapus memo request
            $user->update([
                'approval_status' => 'none',
                'requested_role' => null
            ]);

            return redirect()->route('users.index')->with('success', 'Permintaan kenaikan akses ' . $user->name . ' ditolak.');
        }

        return redirect()->route('users.index')->with('error', 'Tidak ada request pending untuk akun ini.');
    }

    /**
     * Menampilkan formulir Edit Pengguna (untuk ubah role manual oleh Admin).
     */
    public function edit(string $id)
    {
        // Jangan biarkan mengedit diri sendiri di sini (gunakan rute Profile)
        if (auth()->id() == $id) {
            return redirect()->route('users.index')->with('error', 'Gunakan menu Profil untuk mengedit akun Anda sendiri.');
        }

        $user = User::with('roles')->findOrFail($id);
        
        // Ambil semua role yang tersedia untuk dropdown (kecuali Super Admin, optional)
        $roles = Role::where('name', '!=', 'Super Admin')->get();

        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Menyimpan perubahan Role Pengguna secara manual oleh Super Admin.
     */
    public function update(Request $request, string $id)
    {
        // Validasi input
        $request->validate([
            'role' => 'required|exists:roles,name', // Pastikan role ada di DB
        ]);

        $user = User::findOrFail($id);
        
        // Pengaman ekstra: Jangan biarkan edit diri sendiri atau sesama Super Admin
        if (auth()->id() == $id || $user->hasRole('Super Admin')) {
             return redirect()->route('users.index')->with('error', 'Akses ditolak.');
        }

        // Sinkronisasi Role manual (bypass sistem request/approval)
        $user->syncRoles([$request->role]);
        
        // Reset status approval ke standar jika admin mengubah role secara manual
        $user->update([
            'approval_status' => 'none', 
            'requested_role' => null
        ]);

        return redirect()->route('users.index')->with('success', 'Role ' . $user->name . ' berhasil diubah menjadi: ' . $request->role);
    }

    /**
     * Menghapus akun pengguna secara permanen.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        // Pengaman: Jangan biarkan Super Admin hapus dirinya sendiri
        if (auth()->id() == $id) {
            return redirect()->route('users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Pengaman: Jangan biarkan hapus sesama Super Admin (Optional, tergantung kebijakan)
        if ($user->hasRole('Super Admin')) {
            return redirect()->route('users.index')->with('error', 'Tidak dapat menghapus sesama Super Admin.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Akun ' . $user->name . ' (' . $user->username . ') berhasil dihapus permanen.');
    }
}