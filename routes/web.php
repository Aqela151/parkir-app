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
            'kendaraanCount' => \App\Models\Kendaraan::count(),
            'areaCount' => \App\Models\AreaParkir::where('status', 'aktif')->count(),
            'tarifCount' => \App\Models\TarifParkir::count(),
            'userCount' => \App\Models\User::count(),
            'areaCards' => \App\Models\AreaParkir::where('status', 'aktif')->take(4)->get()->map(function ($area) {
                $terisi = \App\Models\Transaksi::where('area_id', $area->id)->where('status', 'parkir')->count();
                $area->terisi = $terisi;
                return $area;
            }),
            'logAktivitas' => \App\Models\LogAktivitas::with('user')
                ->where('created_at', '>=', now()->subHour())
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(),
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
    Route::get('/api/area-parkir',          [AreaParkirController::class, 'apiGetAreas'])->name('api.area-parkir');

    Route::get('/log-aktivitas', [\App\Http\Controllers\AdminController::class, 'logAktivitas'])->name('log-aktivitas');
    Route::get('/api/status-area', [\App\Http\Controllers\AdminController::class, 'getStatusArea'])->name('api.status-area');
});

// PETUGAS
Route::prefix('petugas')->name('petugas.')->middleware(['auth', 'role:petugas'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\PetugasDashboardController::class, 'index'])->name('dashboard');
    
    // Transaksi routes
    Route::prefix('transaksi')->name('transaksi.')->group(function () {
        Route::get('/', [\App\Http\Controllers\PetugasTransaksiController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\PetugasTransaksiController::class, 'store'])->name('store');
        Route::get('/{id}/keluar', [\App\Http\Controllers\PetugasTransaksiController::class, 'keluar'])->name('keluar');
        Route::get('/{id}/struk', [\App\Http\Controllers\PetugasTransaksiController::class, 'struk'])->name('struk');
    });
    
    Route::get('/riwayat-transaksi', [\App\Http\Controllers\PetugasTransaksiController::class, 'riwayat'])->name('riwayat-transaksi');
    Route::get('/riwayat-transaksi/{id}/struk', [\App\Http\Controllers\PetugasTransaksiController::class, 'struk'])->name('riwayat-transaksi.struk');
    Route::get('/status-area', [\App\Http\Controllers\PetugasDashboardController::class, 'statusArea'])->name('status-area');
    Route::get('/api/status-area', [\App\Http\Controllers\PetugasDashboardController::class, 'getStatusArea'])->name('api.status-area');
});

// OWNER
Route::prefix('owner')->name('owner.')->middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\OwnerController::class, 'dashboard'])->name('dashboard');
    Route::get('/rekap-transaksi', [\App\Http\Controllers\OwnerController::class, 'rekapTransaksi'])->name('rekap-transaksi');
    Route::get('/grafik-pendapatan', [\App\Http\Controllers\OwnerController::class, 'grafikPendapatan'])->name('grafik-pendapatan');
    Route::get('/performa-area', [\App\Http\Controllers\OwnerController::class, 'performaArea'])->name('performa-area');
});