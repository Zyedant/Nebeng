<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// Superadmin Controllers
use App\Http\Controllers\Superadmin\DashboardController;
use App\Http\Controllers\Superadmin\MitraController;
use App\Http\Controllers\Superadmin\CustomerController;
use App\Http\Controllers\Superadmin\RefundController;
use App\Http\Controllers\Superadmin\LaporanController;
use App\Http\Controllers\Superadmin\NotificationController;
use App\Http\Controllers\Superadmin\PengaturanController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('welcome'))->name('home');

/*
|--------------------------------------------------------------------------
| Breeze Default Dashboard
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', fn () => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Profile Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Entry Route (OPTIONAL)
| - Admin akses /admin
| - Tetap diarahkan ke dashboard superadmin (view sama persis)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->get('/admin', fn () => redirect()->route('sa.dashboard'))
    ->name('admin.home');

/*
|--------------------------------------------------------------------------
| Superadmin Routes (dipakai bareng admin juga)
|--------------------------------------------------------------------------
*/
Route::prefix('superadmin')
    ->name('sa.')
    ->middleware(['auth', 'role:superadmin,admin'])
    ->group(function () {

        // HOME + DASHBOARD
        Route::get('/', fn () => redirect()->route('sa.dashboard'))->name('home');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | VERIFIKASI
        |--------------------------------------------------------------------------
        */
        Route::prefix('verifikasi')->name('verifikasi.')->group(function () {

            Route::get('/', fn () => redirect()->route('sa.verifikasi.mitra'))->name('index');

            // LIST
            Route::get('/mitra',    [MitraController::class, 'mitra'])->name('mitra');
            Route::get('/customer', [CustomerController::class, 'customer'])->name('customer');

            // DOWNLOAD
            Route::get('/mitra/download',    [MitraController::class, 'downloadMitraPdf'])->name('mitra.download');
            Route::get('/customer/download', [CustomerController::class, 'downloadCustomerPdf'])->name('customer.download');

            // DETAIL
            Route::get('/mitra/{id}', [MitraController::class, 'mitraDetail'])->whereNumber('id')->name('mitra.detail');
            Route::get('/customer/{id}', [CustomerController::class, 'customerDetail'])->whereNumber('id')->name('customer.detail');

            // ACTION - MITRA
            Route::post('/mitra/{id}/verify', [MitraController::class, 'mitraVerify'])->whereNumber('id')->name('mitra.verify');
            Route::post('/mitra/{id}/reject', [MitraController::class, 'mitraReject'])->whereNumber('id')->name('mitra.reject');
            Route::post('/mitra/{id}/block',  [MitraController::class, 'mitraBlock'])->whereNumber('id')->name('mitra.block');

            // ACTION - CUSTOMER
            Route::post('/customer/{id}/verify', [CustomerController::class, 'customerVerify'])->whereNumber('id')->name('customer.verify');
            Route::post('/customer/{id}/reject', [CustomerController::class, 'customerReject'])->whereNumber('id')->name('customer.reject');
            Route::post('/customer/{id}/block',  [CustomerController::class, 'customerBlock'])->whereNumber('id')->name('customer.block');
        });

        // shortcut /superadmin/verifikasi
        Route::get('/verifikasi', fn () => redirect()->route('sa.verifikasi.mitra'))->name('verifikasi');

        /*
        |--------------------------------------------------------------------------
        | MITRA (sidebar)
        |--------------------------------------------------------------------------
        */
        Route::prefix('mitra')->name('mitra.')->group(function () {
            Route::get('/',          [MitraController::class, 'daftarMitra'])->name('index');
            Route::get('/kendaraan', [MitraController::class, 'mitraKendaraan'])->name('kendaraan');
            Route::get('/blokir',    [MitraController::class, 'mitraBlokir'])->name('blokir');

            Route::get('/download', [MitraController::class, 'downloadDaftarMitraPdf'])->name('download');

            Route::get('/{id}',      [MitraController::class, 'mitraDetail'])->whereNumber('id')->name('detail');
            Route::get('/{id}/edit', [MitraController::class, 'edit'])->whereNumber('id')->name('edit');
            Route::put('/{id}',      [MitraController::class, 'update'])->whereNumber('id')->name('update');

            Route::prefix('kendaraan')->name('kendaraan.')->group(function () {
                Route::get('/{id}', [MitraController::class, 'mitraKendaraanDetail'])->whereNumber('id')->name('detail');
                Route::put('/{id}', [MitraController::class, 'mitraKendaraanUpdate'])->whereNumber('id')->name('update');
            });

            Route::get('/kendaraan/download/pdf', [MitraController::class, 'downloadDaftarKendaraanMitraPdf'])
                ->name('kendaraan.download');
        });

        /*
        |--------------------------------------------------------------------------
        | CUSTOMER (sidebar)
        |--------------------------------------------------------------------------
        */
        Route::prefix('customer')->name('customer.')->group(function () {
            Route::get('/',         [CustomerController::class, 'daftarCustomer'])->name('index');
            Route::get('/download', [CustomerController::class, 'downloadDaftarCustomerPdf'])->name('download');

            Route::get('/{id}',      [CustomerController::class, 'customerDetail'])->whereNumber('id')->name('detail');
            Route::get('/{id}/edit', [CustomerController::class, 'edit'])->whereNumber('id')->name('edit');
            Route::put('/{id}',      [CustomerController::class, 'update'])->whereNumber('id')->name('update');
        });

        /*
        |--------------------------------------------------------------------------
        | TRANSAKSI / PESANAN
        |--------------------------------------------------------------------------
        */
        Route::get('/transaksi', [CustomerController::class, 'transaksi'])->name('transaksi');
        Route::get('/transaksi/download', [CustomerController::class, 'downloadPesananPdf'])->name('transaksi.download');
        Route::get('/transaksi/{id}', [CustomerController::class, 'transaksiDetail'])
            ->whereNumber('id')
            ->name('transaksi.detail');
        /*
        |--------------------------------------------------------------------------
        | REFUND
        |--------------------------------------------------------------------------
        */
        Route::prefix('refund')->name('refund.')->group(function () {
            Route::get('/', [RefundController::class, 'index'])->name('index');
            Route::get('/download', [RefundController::class, 'downloadPdf'])->name('download');
            Route::get('/{id}', [RefundController::class, 'detail'])->whereNumber('id')->name('detail');
            Route::post('/{id}/approve', [RefundController::class, 'approve'])->whereNumber('id')->name('approve');
            Route::post('/{id}/reject',  [RefundController::class, 'reject'])->whereNumber('id')->name('reject');
             Route::post('/{id}/terima', [RefundController::class, 'terima'])
        ->whereNumber('id')
        ->name('terima');
        });

        /*
        |--------------------------------------------------------------------------
        | NOTIFICATIONS
        |--------------------------------------------------------------------------
        */
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [NotificationController::class, 'index'])->name('index');
            Route::get('/latest', [NotificationController::class, 'getLatest'])->name('latest');
            Route::post('/{id}/read', [NotificationController::class, 'markRead'])->whereNumber('id')->name('read');
            Route::post('/read-all',  [NotificationController::class, 'markAllRead'])->name('readAll');
        });

        /*
        |--------------------------------------------------------------------------
        | LAPORAN (MENU SIDEBAR "Laporan")
        |--------------------------------------------------------------------------
        */
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
        Route::get('/laporan/download', [LaporanController::class, 'download'])->name('laporan.download');

        Route::get('/laporan/{id}', [LaporanController::class, 'detail'])
            ->whereNumber('id')
            ->name('laporan.detail');

        Route::post('/laporan/{id}/block', [LaporanController::class, 'blockReported'])
            ->whereNumber('id')
            ->name('laporan.block');

        Route::put('/laporan/{id}/update-terlapor', [LaporanController::class, 'updateReported'])
            ->whereNumber('id')
            ->name('laporan.updateTerlapor');

        /*
        |--------------------------------------------------------------------------
        | MENU LAIN
        |--------------------------------------------------------------------------
        */
        Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan');
        Route::patch('/pengaturan', [PengaturanController::class, 'updateProfile'])->name('pengaturan.updateProfile');
        Route::patch('/pengaturan/password', [PengaturanController::class, 'updatePassword'])->name('pengaturan.updatePassword');
        Route::patch('/pengaturan/avatar', [PengaturanController::class, 'updateAvatar'])->name('pengaturan.updateAvatar');
    });

require __DIR__ . '/auth.php';