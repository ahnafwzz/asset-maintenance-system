<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceRequest;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceRequestController extends Controller
{
    // 1. Menampilkan Halaman Pusat Laporan (Report Center)
    public function index(Request $request)
    {
        // Menangkap kata kunci pencarian & tab aktif
        $search = $request->input('search');
        $tab = $request->input('tab', 'diproses'); // Default tab: diproses

        // Mulai query mengambil data laporan beserta nama aset dan pelapornya
        $query = MaintenanceRequest::with(['asset', 'user']);

        // Logika Fitur Search
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

        // Logika Filter Berdasarkan Tab Aktif
        if ($tab === 'selesai') {
            // Tampilkan yang sudah beres atau batal
            $query->whereIn('status', ['Resolved', 'Closed', 'Cancelled', 'Rejected']);
        } else {
            // Tampilkan yang masih antre atau dikerjakan
            $query->whereIn('status', ['Reported', 'Reviewed', 'Assigned', 'In Progress']);
        }

        // Urutkan dari yang terbaru, lalu potong per 10 data (Pagination)
        $requests = $query->latest()->paginate(10)->withQueryString();

        return view('maintenance.index', compact('requests', 'tab', 'search'));
    }

    // 2. Menampilkan Form Buat Laporan Baru
    public function create()
    {
        // Mengambil semua aset KECUALI yang statusnya retired
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

        // Simpan data laporan
        MaintenanceRequest::create([
            'asset_id' => $request->asset_id,
            'user_id' => Auth::id(), // Mengambil ID pegawai yang sedang login
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => 'Reported',
        ]);

        // Mengubah status aset menjadi maintenance
        $asset = Asset::find($request->asset_id);
        $asset->update(['status' => 'maintenance']);

        return redirect()->route('dashboard')->with('success', 'Laporan kerusakan berhasil dikirim!');
    }
}