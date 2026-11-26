<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KebunController;
use App\Http\Controllers\HamaController;
use App\Http\Controllers\PenyakitController;
use App\Http\Controllers\MonitoringController;
use App\Models\Kebun;
use App\Models\Hama;
use App\Models\Penyakit;
use App\Models\Monitoring;

// === 1. ROUTE AUTH (LOGIN / REGISTER / LOGOUT) ===
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// === 2. ROUTE UTAMA APLIKASI (WAJIB LOGIN) ===
Route::middleware('auth')->group(function () {
    Route::get('/', function (Request $request) {
        $tahun = $request->input('tahun', date('Y'));
        $bulan = $request->input('bulan', 'all');

        // Deteksi otomatis nama kolom di tabel monitorings
        $kolomTanggal = collect(['tanggal', 'tanggal_monitoring', 'tgl_monitoring', 'created_at'])
            ->first(fn($col) => Schema::hasColumn('monitorings', $col)) ?? 'created_at';
        $kolomKeterangan = collect(['keterangan_kondisi', 'keterangan', 'catatan', 'deskripsi'])
            ->first(fn($col) => Schema::hasColumn('monitorings', $col));

        // 1. Kartu KPI Utama
        $totalKebun      = Kebun::count();
        $totalHama       = Hama::count();
        $totalPenyakit   = Penyakit::count();
        $totalMonitoring = Monitoring::count();

        // Query dasar dengan filter Tahun & Bulan
        $baseQuery = Monitoring::query()
            ->whereYear($kolomTanggal, $tahun)
            ->when($bulan !== 'all', fn($q) => $q->whereMonth($kolomTanggal, $bulan));

        // 2. Data Grafik Donat (Komposisi Hama vs Penyakit vs Sehat)
        $countHama     = (clone $baseQuery)->where('jenis_gangguan', 'Hama')->count();
        $countPenyakit = (clone $baseQuery)->where('jenis_gangguan', 'Penyakit')->count();
        $countSehat    = (clone $baseQuery)->whereNotIn('jenis_gangguan', ['Hama', 'Penyakit'])->count();

        // 3. Data Grafik Garis Bulanan (Jan - Des pada tahun terpilih)
        $trenHama = [];
        $trenPenyakit = [];
        for ($m = 1; $m <= 12; $m++) {
            $trenHama[] = Monitoring::whereYear($kolomTanggal, $tahun)
                ->whereMonth($kolomTanggal, $m)
                ->where('jenis_gangguan', 'Hama')
                ->count();
            $trenPenyakit[] = Monitoring::whereYear($kolomTanggal, $tahun)
                ->whereMonth($kolomTanggal, $m)
                ->where('jenis_gangguan', 'Penyakit')
                ->count();
        }

        // 4. Data Grafik Batang Vertikal (Top 5 Hama & Penyakit Terbanyak)
        $topGangguan = (clone $baseQuery)
            ->select('nama_gangguan', DB::raw('count(*) as total'))
            ->whereNotNull('nama_gangguan')
            ->where('nama_gangguan', '!=', '')
            ->groupBy('nama_gangguan')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        // 5. Data Grafik Batang Horizontal (Intensitas Gangguan per Kebun)
        $kebunTerdampak = Kebun::withCount(['monitorings as gangguan_count' => function ($q) use ($tahun, $bulan, $kolomTanggal) {
                $q->whereYear($kolomTanggal, $tahun)
                  ->when($bulan !== 'all', fn($sub) => $sub->whereMonth($kolomTanggal, $bulan))
                  ->whereIn('jenis_gangguan', ['Hama', 'Penyakit']);
            }])
            ->orderByDesc('gangguan_count')
            ->take(5)
            ->get();

        // 6. Riwayat Monitoring Terbaru + Referensi Penanganan Otomatis dari Master Data
        $kolomSopHama = collect(['cara_penanganan', 'penanganan', 'informasi_penanganan', 'solusi', 'deskripsi'])
            ->first(fn($col) => Schema::hasColumn('hamas', $col));
        $hamaMap = $kolomSopHama ? Hama::pluck($kolomSopHama, 'nama_hama') : collect();

        $kolomSopPenyakit = collect(['informasi_penanganan', 'cara_penanganan', 'penanganan', 'solusi', 'deskripsi'])
            ->first(fn($col) => Schema::hasColumn('penyakits', $col));
        $penyakitMap = $kolomSopPenyakit ? Penyakit::pluck($kolomSopPenyakit, 'nama_penyakit') : collect();

        $recentMonitorings = (clone $baseQuery)
            ->with('kebun')
            ->latest($kolomTanggal)
            ->take(8)
            ->get()
            ->map(function ($item) use ($hamaMap, $penyakitMap, $kolomTanggal, $kolomKeterangan) {
                $rekomendasi = 'Lanjutkan pemantauan rutin dan jaga sanitasi piringan tanaman kelapa sawit.';
                if ($item->jenis_gangguan === 'Hama' && isset($hamaMap[$item->nama_gangguan])) {
                    $rekomendasi = $hamaMap[$item->nama_gangguan];
                } elseif ($item->jenis_gangguan === 'Penyakit' && isset($penyakitMap[$item->nama_gangguan])) {
                    $rekomendasi = $penyakitMap[$item->nama_gangguan];
                }
                $item->rekomendasi_sop = $rekomendasi;
                $item->tanggal = $item->{$kolomTanggal};
                $item->keterangan_kondisi = $kolomKeterangan ? ($item->{$kolomKeterangan} ?? '') : '';
                return $item;
            });

        // Skor Kesehatan Perkebunan
        $persenSehat = $totalMonitoring > 0
            ? max(15, min(100, round(100 - ((($countHama + $countPenyakit) / $totalMonitoring) * 55))))
            : 100;

        return view('dashboard', compact(
            'tahun', 'bulan',
            'totalKebun', 'totalHama', 'totalPenyakit', 'totalMonitoring',
            'countHama', 'countPenyakit', 'countSehat',
            'trenHama', 'trenPenyakit',
            'topGangguan', 'kebunTerdampak',
            'recentMonitorings', 'persenSehat'
        ));
    })->name('dashboard');

    // Redirect otomatis jika ada akses ke /create
    Route::get('/kebun/create', fn() => redirect()->route('kebun.index'));
    Route::get('/hama/create', fn() => redirect()->route('hama.index'));
    Route::get('/penyakit/create', fn() => redirect()->route('penyakit.index'));
    Route::get('/monitoring/create', fn() => redirect()->route('monitoring.index'));

    // Resource Route CRUD Utama
    Route::resource('kebun', KebunController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('hama', HamaController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('penyakit', PenyakitController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('monitoring', MonitoringController::class)->only(['index', 'store', 'update', 'destroy']);
});