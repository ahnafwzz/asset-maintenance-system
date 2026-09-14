<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        // PENGAMAN LAPIS BAJA: Tendang keluar jika bukan Super Admin
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403, 'Akses Ditolak. Halaman ini khusus Super Admin.');
        }

        // Ambil semua user (kecuali Super Admin itu sendiri)
        $users = User::where('id', '!=', auth()->id())->latest()->get();
        
        return view('users.index', compact('users'));
    }

    public function approve(Request $request, string $id)
    {
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403);
        }

        $user = User::findOrFail($id);

        if ($user->requested_role && $user->approval_status === 'pending') {
            // Tukar role lama dengan role baru yang diminta
            $user->syncRoles([$user->requested_role]);
            
            // Ubah status untuk memicu pop-up notifikasi dan bersihkan memo
            $user->update([
                'approval_status' => 'approved',
                'requested_role' => null
            ]);

            return redirect()->route('users.index')->with('success', 'Akses ' . $user->name . ' disetujui. Role sekarang: ' . $user->roles->first()->name);
        }

        return redirect()->route('users.index')->with('error', 'Tidak ada request valid untuk akun ini.');
    }
}