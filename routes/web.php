<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BankingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    // Breeze Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 1.1 OTP Verification
    Route::get('/otp-verify', [BankingController::class, 'showOtpForm'])->name('otp.verify');
    Route::post('/otp-verify', [BankingController::class, 'verifyOtp']);

    // Protected by MFA
    Route::middleware('mfa')->group(function () {
        // 1.2 Dashboard
        Route::get('/dashboard', [BankingController::class, 'dashboard'])->name('dashboard');
        
        // 1.3 Transfers
        Route::get('/transfer', [BankingController::class, 'transferForm'])->name('transfer');
        Route::post('/transfer', [BankingController::class, 'processTransfer']);
        Route::post('/beneficiary', [BankingController::class, 'addBeneficiary'])->name('beneficiary.add');
        
        // 1.4 Export
        Route::get('/export-history', [BankingController::class, 'exportHistory'])->name('history.export');
    });
});

// This line is strictly required for Login/Register pages to work!
require __DIR__.'/auth.php';