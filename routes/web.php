<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController; 
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;

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

        // Input Order
        Route::get('/input-order', [OrderController::class, 'create'])->name('admin.input_order');
        Route::post('/input-order', [OrderController::class, 'store'])->name('admin.input_order.store');

        // REKAP ORDER
        Route::get('/rekap-order', [OrderController::class, 'index'])->name('admin.rekap_order');
        Route::get('/rekap-order/{id}/edit', [OrderController::class, 'edit'])->name('admin.rekap_order.edit');
        Route::put('/rekap-order/{id}', [OrderController::class, 'update'])->name('admin.rekap_order.update');
        Route::delete('/rekap-order/{id}', [OrderController::class, 'destroy'])->name('admin.rekap_order.destroy');

        // CREATE INVOICE
        Route::get('/create-invoice', [OrderController::class, 'invoiceIndex'])->name('admin.invoice_index');
        Route::get('/order-detail/{id}', [OrderController::class, 'getDetail'])->name('admin.order.detail');
        Route::get('/invoice/print/{id}', [OrderController::class, 'printInvoice'])->name('admin.invoice.print');
        
        // PAYMENT
        Route::get('/payment', [OrderController::class, 'paymentIndex'])->name('admin.payment_index');
        Route::put('/payment/{id}', [OrderController::class, 'paymentUpdate'])->name('admin.payment_update');
    });
});