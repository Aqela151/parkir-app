<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegistrasiUserController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\TarifParkirController;
use App\Http\Controllers\AreaParkirController;
use App\Models\Kendaraan;
use App\Models\AreaParkir;
use App\Models\TarifParkir;
use App\Models\User;

// ROOT → langsung ke login
Route::get('/', fn() => redirect()->route('login'));

// AUTH
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ADMIN
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard', [
            'kendaraanCount' => Kendaraan::count(),
            'areaCount' => AreaParkir::where('status', 'aktif')->count(),
            'tarifCount' => TarifParkir::count(),
            'userCount' => User::count(),
            'areaCards' => AreaParkir::take(4)->get(),
        ]);
    })->name('dashboard');

    // Registrasi User
    Route::get('/registrasi-user',          [RegistrasiUserController::class, 'index'])->name('registrasi-user');
    Route::post('/registrasi-user',         [RegistrasiUserController::class, 'store'])->name('registrasi-user.store');
    Route::put('/registrasi-user/{id}',     [RegistrasiUserController::class, 'update'])->name('registrasi-user.update');
    Route::delete('/registrasi-user/{id}',  [RegistrasiUserController::class, 'destroy'])->name('registrasi-user.destroy');

    // Kendaraan
    Route::get('/kendaraan',                [KendaraanController::class, 'index'])->name('kendaraan');
    Route::post('/kendaraan',               [KendaraanController::class, 'store'])->name('kendaraan.store');
    Route::put('/kendaraan/{id}',           [KendaraanController::class, 'update'])->name('kendaraan.update');
    Route::delete('/kendaraan/{id}',        [KendaraanController::class, 'destroy'])->name('kendaraan.destroy');

    // Tarif Parkir
    Route::get('/tarif-parkir',             [TarifParkirController::class, 'index'])->name('tarif-parkir');
    Route::post('/tarif-parkir',            [TarifParkirController::class, 'store'])->name('tarif-parkir.store');
    Route::put('/tarif-parkir/{id}',        [TarifParkirController::class, 'update'])->name('tarif-parkir.update');
    Route::delete('/tarif-parkir/{id}',     [TarifParkirController::class, 'destroy'])->name('tarif-parkir.destroy');

    // Area Parkir
    Route::get('/area-parkir',              [AreaParkirController::class, 'index'])->name('area-parkir');
    Route::post('/area-parkir',             [AreaParkirController::class, 'store'])->name('area-parkir.store');
    Route::put('/area-parkir/{id}',         [AreaParkirController::class, 'update'])->name('area-parkir.update');
    Route::delete('/area-parkir/{id}',      [AreaParkirController::class, 'destroy'])->name('area-parkir.destroy');

    Route::get('/log-aktivitas', fn() => view('admin.log-aktivitas'))->name('log-aktivitas');
});

// PETUGAS
Route::prefix('petugas')->name('petugas.')->middleware(['auth', 'role:petugas'])->group(function () {
    Route::get('/dashboard', fn() => view('petugas.dashboard'))->name('dashboard');
    Route::get('/transaksi', fn() => view('petugas.transaksi'))->name('transaksi');
    Route::get('/riwayat-transaksi', fn() => view('petugas.riwayat-transaksi'))->name('riwayat-transaksi');
    Route::get('/status-area', fn() => view('petugas.status-area'))->name('status-area');
});

// OWNER
Route::prefix('owner')->name('owner.')->middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/dashboard', fn() => view('owner.dashboard'))->name('dashboard');
    Route::get('/rekap-transaksi', fn() => view('owner.rekap-transaksi'))->name('rekap-transaksi');
    Route::get('/grafik-pendapatan', fn() => view('owner.grafik-pendapatan'))->name('grafik-pendapatan');
    Route::get('/performa-area', fn() => view('owner.performa-area'))->name('performa-area');
});