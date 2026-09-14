<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\AssetCategoryController; 
use App\Http\Controllers\AssetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Routing Master Data
    
    // 1. JALUR KHUSUS (Satpam Jalur Belakang ditaruh di ATAS)
    // Hanya Super Admin, Asset Manager, dan Maintenance Staff yang boleh Create, Edit, dan Delete
    Route::middleware(['role:Super Admin|Asset Manager|Maintenance Staff'])->group(function () {
        Route::resource('assets', AssetController::class)->except(['index', 'show']);
        Route::resource('asset-categories', AssetCategoryController::class)->except(['index', 'show']);
        Route::resource('departments', DepartmentController::class)->except(['index', 'show']);
        Route::resource('locations', LocationController::class)->except(['index', 'show']);
    });

    // 2. JALUR UMUM (Semua yang login bisa akses)
    // Hanya membuka akses 'index' (melihat daftar) dan 'show' (melihat detail)
    Route::resource('assets', AssetController::class)->only(['index', 'show']);
    Route::resource('asset-categories', AssetCategoryController::class)->only(['index', 'show']);
    Route::resource('departments', DepartmentController::class)->only(['index', 'show']);
    Route::resource('locations', LocationController::class)->only(['index', 'show']);

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/{id}/approve', [UserController::class, 'approve'])->name('users.approve');

    Route::post('/clear-approval-status', function () {
        auth()->user()->update(['approval_status' => 'none']);
        return back();
    })->name('clear.approval');
});

require __DIR__.'/auth.php';