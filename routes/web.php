<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\PartnerPostController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\RefundController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {

    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/latest', [NotificationController::class, 'getLatest'])->name('latest');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
    });

    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('index');
        Route::get('/{id}', [TransactionController::class, 'show'])->name('show');
        Route::put('/{id}/accept', [TransactionController::class, 'accept'])->name('accept');
        Route::put('/{id}/reject', [TransactionController::class, 'reject'])->name('reject');
    });

    Route::prefix('partner')->name('partner.')->group(function () {
        Route::get('/', [PartnerController::class, 'index'])->name('index');
        Route::get('/{id}', [PartnerController::class, 'show'])->name('show');
    });

    Route::prefix('partner-post')->name('partner-post.')->group(function () {
        Route::get('/', [PartnerPostController::class, 'index'])->name('index');
        Route::get('/{id}', [PartnerPostController::class, 'show'])->name('show');
    });

    Route::prefix('withdrawals/partner')->name('withdrawals.partner.')->group(function () {
        Route::get('/', [WithdrawalController::class, 'partnerIndex'])->name('index');
        Route::get('/{id}', [WithdrawalController::class, 'partnerShow'])->name('show');
        Route::put('/{id}', [WithdrawalController::class, 'partnerUpdate'])->name('update');
    });
    
    Route::prefix('withdrawals/partner-post')->name('withdrawals.partner-post.')->group(function () {
        Route::get('/', [WithdrawalController::class, 'partnerPostIndex'])->name('index');
        Route::get('/{id}', [WithdrawalController::class, 'partnerPostShow'])->name('show');
        Route::put('/{id}', [WithdrawalController::class, 'partnerPostUpdate'])->name('update');
    });

    Route::prefix('refund')->name('refund.')->group(function () {
        Route::get('/', [RefundController::class, 'index'])->name('index');
        Route::get('/{id}', [RefundController::class, 'show'])->name('show');
    Route::put('/{id}', [RefundController::class, 'update'])->name('update');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';