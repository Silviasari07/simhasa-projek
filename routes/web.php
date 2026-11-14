<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KebunController;
use App\Http\Controllers\HamaController;
use App\Http\Controllers\PenyakitController;
use App\Http\Controllers\MonitoringController;
use App\Models\Kebun;
use App\Models\Hama;
use App\Models\Penyakit;
use App\Models\Monitoring;

// Halaman Login & Register (Untuk Tamu / Belum Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Semua Halaman Utama SIMHASA (Wajib Login)
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        $totalKebun = Kebun::count();
        $totalHama = Hama::count();
        $totalPenyakit = Penyakit::count();
        $totalMonitoring = Monitoring::count();
        $recentMonitorings = Monitoring::with('kebun')->latest()->take(5)->get();

        $countHama = Monitoring::where('jenis_gangguan', 'Hama')->count();
        $countPenyakit = Monitoring::where('jenis_gangguan', 'Penyakit')->count();
        $countSehat = Monitoring::where('jenis_gangguan', 'Sehat / Normal')->count();

        return view('dashboard', compact(
            'totalKebun',
            'totalHama',
            'totalPenyakit',
            'totalMonitoring',
            'recentMonitorings',
            'countHama',
            'countPenyakit',
            'countSehat'
        ));
    })->name('dashboard');

    Route::resource('kebun', KebunController::class);
    Route::resource('hama', HamaController::class);
    Route::resource('penyakit', PenyakitController::class);
    Route::resource('monitoring', MonitoringController::class);
});