<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Department;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAssets = Asset::count();
        $activeAssets = Asset::where('status', 'active')->count();
        $maintenanceAssets = Asset::where('status', 'maintenance')->count();
        $brokenAssets = Asset::where('status', 'broken')->count();

        $chartStatusData = [
            $activeAssets,
            $maintenanceAssets,
            $brokenAssets,
            Asset::where('status', 'retired')->count()
        ];

        $departments = Department::withCount('assets')->get();
        $chartDeptLabels = $departments->pluck('name')->toArray();
        $chartDeptData = $departments->pluck('assets_count')->toArray();

        return view('dashboard', compact(
            'totalAssets', 'activeAssets', 'maintenanceAssets', 'brokenAssets',
            'chartStatusData', 'chartDeptLabels', 'chartDeptData'
        ));
    }
}