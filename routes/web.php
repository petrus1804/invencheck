<?php

use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemRequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (! auth()->check()) {
        return redirect()->route('login');
    }

    return redirect()->route(auth()->user()->defaultRouteName());
});

Route::middleware('auth')->group(function () {

    Route::middleware('permission:view_dashboard')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });

    Route::middleware('permission:view_stock,manage_stock')->group(function () {
        Route::get('/stock', [ItemController::class, 'index'])->name('stock');
    });
    Route::middleware('permission:manage_stock')->group(function () {
        Route::post('/stock', [ItemController::class, 'store'])->name('stock.store');
        Route::put('/stock/{item}', [ItemController::class, 'update'])->name('stock.update');
        Route::delete('/stock/{item}', [ItemController::class, 'destroy'])->name('stock.destroy');
    });
    Route::middleware('permission:restock_items')->group(function () {
        Route::post('/stock/restock', [ItemController::class, 'restock'])->name('stock.restock');
    });

    Route::middleware('permission:view_categories,manage_categories')->group(function () {
        Route::get('/kategori', [CategoryController::class, 'index'])->name('kategori');
    });
    Route::middleware('permission:manage_categories')->group(function () {
        Route::post('/kategori', [CategoryController::class, 'store'])->name('kategori.store');
        Route::put('/kategori/{category}', [CategoryController::class, 'update'])->name('kategori.update');
        Route::delete('/kategori/{category}', [CategoryController::class, 'destroy'])->name('kategori.destroy');
    });

    Route::middleware('permission:view_warehouses,manage_warehouses')->group(function () {
        Route::get('/gudang', [WarehouseController::class, 'index'])->name('gudang');
    });
    Route::middleware('permission:manage_warehouses')->group(function () {
        Route::post('/gudang', [WarehouseController::class, 'store'])->name('gudang.store');
        Route::put('/gudang/{warehouse}', [WarehouseController::class, 'update'])->name('gudang.update');
        Route::delete('/gudang/{warehouse}', [WarehouseController::class, 'destroy'])->name('gudang.destroy');
    });

    Route::middleware('permission:view_reports')->group(function () {
        Route::get('/laporan', [ReportController::class, 'index'])->name('laporan');
        Route::get('/laporan/export/excel', [ReportController::class, 'exportExcel'])->name('laporan.export.excel');
        Route::get('/laporan/export/pdf', [ReportController::class, 'exportPdf'])->name('laporan.export.pdf');
    });

    Route::middleware('permission:approve_requests')->group(function () {
        Route::get('/persetujuan', [ApprovalController::class, 'index'])->name('persetujuan');
        Route::post('/persetujuan/{transaction}/approve', [ApprovalController::class, 'approve'])->name('persetujuan.approve');
        Route::post('/persetujuan/{transaction}/reject', [ApprovalController::class, 'reject'])->name('persetujuan.reject');
    });

    Route::middleware('permission:manage_users')->group(function () {
        Route::get('/pengaturan', [UserController::class, 'index'])->name('pengaturan');
        Route::post('/pengaturan/users', [UserController::class, 'store'])->name('pengaturan.users.store');
        Route::put('/pengaturan/users/{user}', [UserController::class, 'update'])->name('pengaturan.users.update');
        Route::delete('/pengaturan/users/{user}', [UserController::class, 'destroy'])->name('pengaturan.users.destroy');

        Route::get('/roles', [RoleController::class, 'index'])->name('roles');
        Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
        Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
    });

    Route::middleware('permission:request_items')->group(function () {
        Route::get('/ambil-barang', [ItemRequestController::class, 'index'])->name('ambil-barang');
        Route::post('/ambil-barang', [ItemRequestController::class, 'store'])->name('ambil-barang.store');
        Route::get('/riwayat-saya', [ItemRequestController::class, 'history'])->name('riwayat-saya');
        Route::get('/riwayat-saya/{transaction}/bukti', [ItemRequestController::class, 'printProof'])->name('riwayat-saya.bukti');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__.'/auth.php';