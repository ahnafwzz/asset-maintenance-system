<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Location;
use App\Models\AssetCategory;
use App\Models\Asset;
use App\Models\MaintenanceRequest;
use App\Models\User;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil user 'Siti Viewer' sebagai pelapor dummy
        $viewer = User::where('username', 'sitiviewer')->first();

        // 2. Buat Data Master (Kategori, Departemen, Lokasi)
        $kategori = AssetCategory::create(['name' => 'Elektronik & Jaringan', 'description' => 'Aset IT']);
        $departemen = Department::create(['name' => 'IT Support', 'description' => 'Divisi Teknologi']);
        $lokasi = Location::create(['name' => 'Ruang Server Lantai 2']);

        // 3. Buat Data Aset
        $asset1 = Asset::create([
            'asset_code' => 'AST-001',
            'name' => 'Server Dell PowerEdge',
            'asset_category_id' => $kategori->id,
            'department_id' => $departemen->id,
            'location_id' => $lokasi->id,
            'purchase_date' => now()->subYears(2),
            'status' => 'maintenance',
        ]);

        $asset2 = Asset::create([
            'asset_code' => 'AST-002',
            'name' => 'Router Cisco Catalyst',
            'asset_category_id' => $kategori->id,
            'department_id' => $departemen->id,
            'location_id' => $lokasi->id,
            'purchase_date' => now()->subYear(),
            'status' => 'maintenance',
        ]);

        // 4. Buat Data Laporan Perbaikan (Maintenance Requests)
        
        // Laporan 1: Status "Reported" (Untuk tes tombol "Batalkan" oleh pelapor/Super Admin)
        MaintenanceRequest::create([
            'asset_id' => $asset1->id,
            'user_id' => $viewer->id ?? 1,
            'title' => 'Server sering mati mendadak (Dummy)',
            'description' => 'Power supply sepertinya bermasalah, tolong segera dicek.',
            'priority' => 'High',
            'status' => 'Reported',
        ]);

        // Laporan 2: Status "In Progress" (Untuk tes Dropdown "Tindak Lanjuti" ke Selesai)
        MaintenanceRequest::create([
            'asset_id' => $asset2->id,
            'user_id' => $viewer->id ?? 1,
            'title' => 'Port LAN nomor 4 mati (Dummy)',
            'description' => 'Kabel sudah diganti tapi indikator lampu tetap mati.',
            'priority' => 'Medium',
            'status' => 'In Progress',
        ]);
    }
}