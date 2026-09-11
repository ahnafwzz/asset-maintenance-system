<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;

class DashboardController extends Controller
{
    public function index()
    {
        // Menghitung statistik aset
        $totalAssets = Asset::count();
        $activeAssets = Asset::where('status', 'active')->count();
        $maintenanceAssets = Asset::where('status', 'maintenance')->count();
        $brokenAssets = Asset::where('status', 'broken')->count();

        return view('dashboard', compact('totalAssets', 'activeAssets', 'maintenanceAssets', 'brokenAssets'));
    }
}