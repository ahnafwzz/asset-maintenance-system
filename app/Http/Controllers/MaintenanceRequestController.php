<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceRequest;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceRequestController extends Controller
{
    // 1. Menampilkan Halaman Pusat Laporan
    public function index(Request $request)
    {
        $search = $request->input('search');
        $tab = $request->input('tab', 'diproses'); 

        // Tambahkan 'technician' pada with() agar relasi teknisi ikut dimuat
        $query = MaintenanceRequest::with(['asset', 'user', 'technician']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('asset', function($subQ) use ($search) {
                      $subQ->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('user', function($subQ) use ($search) {
                      $subQ->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($tab === 'selesai') {
            $query->whereIn('status', ['Resolved', 'Closed', 'Cancelled', 'Rejected']);
        } else {
            $query->whereIn('status', ['Reported', 'Reviewed', 'Assigned', 'In Progress']);
        }

        $requests = $query->latest()->paginate(10)->withQueryString();

        return view('maintenance.index', compact('requests', 'tab', 'search'));
    }

    // 2. Menampilkan Form Buat Laporan Baru
    public function create()
    {
        $assets = Asset::where('status', '!=', 'retired')->get();
        return view('maintenance.create', compact('assets'));
    }

    // 3. Menyimpan Data Laporan ke Database
    public function store(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:Low,Medium,High,Critical',
        ]);

        MaintenanceRequest::create([
            'asset_id' => $request->asset_id,
            'user_id' => Auth::id(), 
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => 'Reported',
        ]);

        $asset = Asset::find($request->asset_id);
        $asset->update(['status' => 'maintenance']);

        return redirect()->route('dashboard')->with('success', 'Laporan kerusakan berhasil dikirim!');
    }

    // 4. Menampilkan Halaman Detail & Proses Tindak Lanjut
    public function edit($id)
    {
        $maintenance = MaintenanceRequest::with(['asset', 'user', 'technician'])->findOrFail($id);
        $user = auth()->user();

        // PROTEKSI: Cegah teknisi lain mengambil alih tugas yang sudah ada penanggung jawabnya
        if ($user->hasRole('Maintenance Staff') && !$user->hasAnyRole('Super Admin|Asset Manager')) {
            if ($maintenance->technician_id !== null && $maintenance->technician_id !== $user->id) {
                return redirect()->route('maintenance.index')->with('error', 'Akses ditolak: Laporan ini sudah dikerjakan oleh teknisi lain.');
            }
        }

        $technicians = \App\Models\User::role('Maintenance Staff')->get();
        
        // Kita passing data dengan nama 'request' agar sesuai 
        return view('maintenance.edit', ['request' => $maintenance, 'technicians' => $technicians]);
    }

    // 5. Memperbarui Status Laporan & Penugasan Teknisi
    public function update(Request $request, $id)
    {
        $maintenance = MaintenanceRequest::findOrFail($id);
        $user = auth()->user();
        
        // PROTEKSI JALUR BELAKANG: Cegah teknisi lain memaksa ubah tugas via POST
        if ($user->hasRole('Maintenance Staff') && !$user->hasAnyRole('Super Admin|Asset Manager')) {
            if ($maintenance->technician_id !== null && $maintenance->technician_id !== $user->id) {
                return redirect()->route('maintenance.index')->with('error', 'Akses ditolak: Laporan ini sudah dikerjakan oleh teknisi lain.');
            }
        }

        $request->validate([
            'status' => 'required|in:Reported,Reviewed,Assigned,In Progress,Resolved,Closed,Cancelled,Rejected',
            'technician_id' => 'nullable|exists:users,id'
        ]);

        $newStatus = $request->status;
        $technicianId = $request->technician_id ?? $maintenance->technician_id;

        // LOGIKA OTOMATIS: Jika Teknisi klik "In Progress", dia langsung jadi penanggung jawab
        if ($newStatus === 'In Progress' && $user->hasRole('Maintenance Staff') && !$technicianId) {
            $technicianId = $user->id;
        }

        // VALIDASI WAJIB: Status tertentu tidak boleh diproses tanpa teknisi!
        if (in_array($newStatus, ['Assigned', 'In Progress', 'Resolved', 'Closed'])) {
            if (!$technicianId) {
                return back()->with('error', "Gagal: Anda harus memilih Teknisi terlebih dahulu untuk mengubah status menjadi {$newStatus}.");
            }
        }
        
        $maintenance->update([
            'status' => $newStatus,
            'technician_id' => $technicianId
        ]);

        if (in_array($newStatus, ['Resolved', 'Closed', 'Cancelled', 'Rejected'])) {
            $asset = Asset::find($maintenance->asset_id);
            if ($asset) {
                $asset->update(['status' => 'active']);
            }
        }

        return redirect()->route('maintenance.index')->with('success', "Status laporan berhasil diperbarui.");
    }

    // 6. Menghapus Laporan Secara Permanen
    public function destroy($id)
    {
        $maintenance = MaintenanceRequest::findOrFail($id);
        $maintenance->delete();

        return back()->with('success', 'Data laporan berhasil dihapus permanen.');
    }
}