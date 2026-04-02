<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController; 
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\PengeluaranController;
use App\Http\Controllers\Admin\ServiceController; // Pastikan ini terpanggil

Route::get('/', function () {
    return view('landing_page');
});

Route::prefix('admin')->group(function () {
    
    // ==========================================
    // ROUTE PUBLIC (Bisa diakses tanpa login)
    // ==========================================
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

    // ==========================================
    // ROUTE PRIVATE (WAJIB LOGIN)
    // ==========================================
    Route::middleware(['auth'])->group(function () {
        
        // Logout
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
        
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/dashboard/realtime', [OrderController::class, 'getRealtimeDashboard'])->name('admin.dashboard.realtime');

        // Input Order
        Route::get('/input-order', [OrderController::class, 'create'])->name('admin.input_order');
        Route::post('/input-order', [OrderController::class, 'store'])->name('admin.input_order.store');

        // REKAP ORDER
        Route::get('/rekap-order', [OrderController::class, 'index'])->name('admin.rekap_order');
        Route::get('/rekap-order/{id}/edit', [OrderController::class, 'edit'])->name('admin.rekap_order.edit');
        Route::put('/rekap-order/{id}', [OrderController::class, 'update'])->name('admin.rekap_order.update');
        Route::delete('/rekap-order/{id}', [OrderController::class, 'destroy'])->name('admin.rekap_order.destroy');
        Route::get('/rekap-order/export', [OrderController::class, 'exportExcel'])->name('admin.rekap_order.export');

        // CREATE INVOICE
        Route::get('/create-invoice', [OrderController::class, 'invoiceIndex'])->name('admin.invoice_index');
        Route::get('/create-invoice/export', [OrderController::class, 'exportInvoice'])->name('admin.invoice.export_excel'); // Tambahkan baris ini
        Route::get('/order-detail/{id}', [OrderController::class, 'getDetail'])->name('admin.order.detail');
        Route::get('/invoice/print/{id}', [OrderController::class, 'invoicePrint'])->name('admin.invoice.print');

        // PAYMENT
        Route::get('/payment', [OrderController::class, 'paymentIndex'])->name('admin.payment_index');
        Route::put('/payment/{id}', [OrderController::class, 'paymentUpdate'])->name('admin.payment_update');

        // PENGELUARAN
        Route::get('/pengeluaran', [PengeluaranController::class, 'index'])->name('admin.pengeluaran');
        Route::get('/pengeluaran/tambah', [PengeluaranController::class, 'create'])->name('admin.pengeluaran.create');
        Route::post('/pengeluaran/simpan', [PengeluaranController::class, 'store'])->name('admin.pengeluaran.store');
        Route::get('/pengeluaran/{id}/edit', [PengeluaranController::class, 'edit'])->name('admin.pengeluaran.edit');
        Route::put('/pengeluaran/{id}', [PengeluaranController::class, 'update'])->name('admin.pengeluaran.update');
        Route::delete('/pengeluaran/{id}', [PengeluaranController::class, 'destroy'])->name('admin.pengeluaran.destroy');
        Route::get('/pengeluaran/export', [PengeluaranController::class, 'exportExcel'])->name('admin.pengeluaran.export_excel'); // Tambahkan baris ini
        Route::get('/pengeluaran/tambah', [PengeluaranController::class, 'create'])->name('admin.pengeluaran.create');

        // Monitoring Cancel
        Route::get('/rekap-cancel', [OrderController::class, 'indexCancel'])->name('admin.rekap_cancel');
        Route::get('/rekap-cancel/export', [OrderController::class, 'exportCancel'])->name('admin.rekap_cancel.export');
        Route::put('/order/{id}/cancel', [OrderController::class, 'cancel'])->name('admin.order.cancel');
        Route::put('/order/{id}/restore', [OrderController::class, 'restore'])->name('admin.order.restore');

        Route::put('/order-item/{id}/cancel', [OrderController::class, 'cancelItem'])->name('admin.item.cancel');
        Route::put('/order-item/{id}/restore', [OrderController::class, 'restoreItem'])->name('admin.item.restore');
        Route::delete('/order-item/{id}/destroy', [OrderController::class, 'destroyItem'])->name('admin.item.destroy');

        Route::get('/laporan', [OrderController::class, 'laporan'])->name('admin.laporan');

        // Master Sopir
        Route::get('/master-sopir', [DriverController::class, 'index'])->name('admin.master_sopir.index');
        Route::post('/master-sopir', [DriverController::class, 'store'])->name('admin.master_sopir.store');
        Route::put('/master-sopir/{id}', [DriverController::class, 'update'])->name('admin.master_sopir.update');
        Route::delete('/master-sopir/{id}', [DriverController::class, 'destroy'])->name('admin.master_sopir.destroy');
        Route::get('/master-sopir/export', [DriverController::class, 'exportExcel'])->name('admin.master_sopir.export');
        
        Route::get('/api/search-drivers', [DriverController::class, 'search'])->name('admin.api.drivers.search');
        Route::get('/master-layanan/export', [ServiceController::class, 'exportExcel'])->name('admin.master_layanan.export');
        Route::get('/invoice/export', [OrderController::class, 'exportExcel'])->name('admin.invoice.export');
        Route::get('/payment/export', [OrderController::class, 'exportPaymentExcel'])->name('admin.payment.export');
        Route::get('/laporan/export', [OrderController::class, 'exportLaporan'])->name('admin.laporan.export');

        // REVISI MASTER LAYANAN (Disamakan menggunakan {id})
        Route::prefix('master-layanan')->name('admin.master_layanan.')->group(function () {
            Route::get('/', [ServiceController::class, 'index'])->name('index');
            Route::post('/simpan', [ServiceController::class, 'store'])->name('store');
            Route::put('/{id}', [ServiceController::class, 'update'])->name('update');
            Route::delete('/{id}', [ServiceController::class, 'destroy'])->name('destroy');
        });
    });
});