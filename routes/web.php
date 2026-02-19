<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LedgerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReceivePaymentController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;

// Redirect root URL to /home
Route::get('/', function () {
    return redirect()->route('home');
});


// Home route pointing to HomeController's index method
Route::get('/', [HomeController::class, 'index'])->name('home');

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'verified', 'role:1'])->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Payment Rputes
    Route::get('/receive-payment', [ReceivePaymentController::class, 'index'])->name('receive-payment.index');
    Route::get('/receive-payment/add', [ReceivePaymentController::class, 'add'])->name('receive-payment.add');
    Route::get('receive-payment/getSupplierAccounts', [ReceivePaymentController::class, 'getClientContext'])->name('receive-payment.getClientContext');
    Route::post('/receive-payment/save', [ReceivePaymentController::class, 'save'])->name('receive-payment.save');
    Route::get('/receive-payment/getall', [ReceivePaymentController::class, 'getall'])->name('receive-payment.getall');
    Route::get('/receive-payment/view/{id}', [ReceivePaymentController::class, 'view'])->name('receive-payment.view');
    Route::post('/receive-payment/update_status', [ReceivePaymentController::class, 'update_status'])->name('receive-payment.update_status');
    Route::get('/receive-payment/edit/{id}', [ReceivePaymentController::class, 'edit'])->name('receive-payment.edit');
    Route::post('/receive-payment/update', [ReceivePaymentController::class, 'update'])->name('receive-payment.update');
    Route::delete('/receive-payment/{id}', [ReceivePaymentController::class, 'destroy'])->name('receive-payment.destroy');
    Route::get('/receive-payment/getall_filtered', [ReceivePaymentController::class, 'filter'])->name('receive-payment.getall_filtered');
    Route::get('/receive-payment/receipt/{payment}', [ReceivePaymentController::class, 'viewReceipt'])->name('receive-payment.receipt');
    Route::get('/receive-payment/pdf/{id}', [ReceivePaymentController::class, 'pdf'])->name('receive-payment.pdf');


    // Ledgers Routes
    Route::get('/ledger', [LedgerController::class, 'index'])->name('ledger.index');
    Route::get('/ledger/add', [ledgerController::class, 'add'])->name('ledger.add');
    Route::post('/ledger/save', [ledgerController::class, 'save'])->name('ledger.save');
    Route::get('/ledger/getall', [ledgerController::class, 'getall'])->name('ledger.getall');
    Route::get('/ledger/view/{id}', [ledgerController::class, 'view'])->name('ledger.view');
    Route::post('/ledger/update_status', [ledgerController::class, 'update_status'])->name('ledger.update_status');
    Route::get('/ledger/edit/{id}', [ledgerController::class, 'edit'])->name('ledger.edit');
    Route::post('/ledger/update', [ledgerController::class, 'update'])->name('ledger.update');
    Route::delete('/receive-payment/{id}', [ledgerController::class, 'destroy'])->name('ledger.destroy');
    Route::get('/ledger/getall_filtered', [ledgerController::class, 'filter'])->name('ledger.getall_filtered');
    Route::get('/ledger/receipt/{payment}', [ledgerController::class, 'viewReceipt'])->name('ledger.receipt');
    Route::get('/ledger/pdf/{id}', [ledgerController::class, 'pdf'])->name('ledger.pdf');

});

// Agent Routes


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/custom-logout', [AuthenticatedSessionController::class, 'logout'])->name('custom.logout');

require __DIR__.'/auth.php';
