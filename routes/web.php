<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

//welcome

Route::middleware(['auth', 'role:admin,kasir'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    //transaksi route
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi');
    Route::get("/laporan", [TransaksiController::class, 'laporan'])->name('laporan');
    Route::get("/laporan/detail/{id}", [TransaksiController::class, 'detailLaporan'])->name('laporan.detail');

    //loogout routes
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});


Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'postLogin'])->name('postLogin');
    Route::get('/logout', function () {
        Auth::logout();
        return redirect('/login');
    })->name('logout');
});
