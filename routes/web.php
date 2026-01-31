<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Breeze Dashboard (pakai auth)
|--------------------------------------------------------------------------
| Ini milik auth temanmu, biarkan aktif.
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Superadmin Routes (sementara tanpa middleware)
|--------------------------------------------------------------------------
| Setelah auth & role sudah siap, tinggal tambah middleware:
| ->middleware(['auth','role:superadmin'])
*/
Route::prefix('superadmin')->name('sa.')->group(function () {

    // HALAMAN UTAMA setelah login (kosong / watermark)
    Route::get('/', fn() => view('superadmin.pages.home'))->name('home');

    // halaman dashboard superadmin (nanti isi data)
    Route::get('/dashboard', fn() => view('superadmin.pages.dashboard'))->name('dashboard');

    Route::get('/verifikasi', fn() => view('superadmin.pages.verifikasi'))->name('verifikasi');

    Route::get('/mitra', fn() => view('superadmin.pages.mitra'))->name('mitra');
    Route::get('/mitra/kendaraan', fn() => view('superadmin.pages.mitra_kendaraan'))->name('mitra.kendaraan');

    Route::get('/customer', fn() => view('superadmin.pages.customer'))->name('customer');
    Route::get('/transaksi', fn() => view('superadmin.pages.transaksi'))->name('transaksi');
    Route::get('/refund', fn() => view('superadmin.pages.refund'))->name('refund');
    Route::get('/laporan', fn() => view('superadmin.pages.laporan'))->name('laporan');
    Route::get('/pengaturan', fn() => view('superadmin.pages.pengaturan'))->name('pengaturan');
});

/*
|--------------------------------------------------------------------------
| Profile Routes (Breeze)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
