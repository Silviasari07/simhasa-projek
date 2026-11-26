@extends('layouts.app')

@section('content')
<!-- Muat Tailwind CSS & Font Modern -->
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    .simhasa-dashboard {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .simhasa-dashboard a {
        text-decoration: none !important;
    }
</style>

<div class="simhasa-dashboard space-y-6 pb-8">

    <!-- 1. HERO BANNER AGRI-TECH -->
    <section class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#064e3b] via-[#047857] to-[#10b981] text-white p-7 lg:p-8 shadow-lg shadow-emerald-900/10">
        <div class="absolute -right-12 -top-12 w-56 h-56 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="space-y-2.5 max-w-2xl">
                <div class="flex flex-wrap items-center gap-2 text-xs font-semibold">
                    <span class="px-3 py-1 rounded-full bg-white text-emerald-900 shadow-sm">🌿 SIMHASA v1.0 • Kelapa Sawit</span>
                    <span class="px-3 py-1 rounded-full bg-black/20 text-emerald-50">
                        📅 {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    </span>
                </div>
                <h2 class="text-2xl lg:text-3xl font-extrabold tracking-tight text-white">
                    Selamat Datang di Dashboard SIMHASA 👋
                </h2>
                <p class="text-emerald-50/95 text-sm leading-relaxed">
                    Pantau tren serangan hama & penyakit kelapa sawit, evaluasi blok kebun terdampak, serta kelola catatan inspeksi lapangan dalam satu layar terpadu.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <!-- Skor Kesehatan Kebun -->
                <div class="px-4 py-2.5 rounded-2xl bg-black/20 border border-white/20 backdrop-blur-sm">
                    <p class="text-[10px] uppercase tracking-wider text-emerald-200 font-bold">Skor Kesehatan Kebun</p>
                    <div class="flex items-baseline gap-1.5 mt-0.5">
                        <span class="text-2xl font-extrabold text-white">{{ $persenSehat }}%</span>
                        <span class="text-xs text-emerald-200 font-semibold">
                            {{ $persenSehat >= 80 ? 'Optimal' : ($persenSehat >= 50 ? 'Waspada' : 'Perlu Aksi') }}
                        </span>
                    </div>
                </div>

                <a href="{{ route('monitoring.index') }}" class="px-5 py-3 rounded-xl bg-white text-emerald-900 font-bold text-sm shadow-md hover:bg-emerald-50 transition flex items-center gap-2">
                    <span>📝</span> + Catat Monitoring
                </a>
                <a href="{{ route('kebun.index') }}" class="px-5 py-3 rounded-xl bg-emerald-950/35 hover:bg-emerald-950/50 text-white border border-white/25 font-semibold text-sm transition">
                    + Data Kebun
                </a>
            </div>
        </div>
    </section>

    <!-- 2. FILTER PERIODE TAHUN & BULAN -->
    <section class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-base">🗓️</span>
            <div>
                <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Filter Analitik Periode</h3>
                <p class="text-[11px] text-slate-500">Pilih tahun dan bulan untuk menyaring grafik & riwayat monitoring</p>
            </div>
        </div>

        <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap items-center gap-2.5">
            <select name="tahun" onchange="this.form.submit()" class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 bg-slate-50 focus:outline-none focus:border-emerald-500">
                @for($y = date('Y'); $y >= date('Y') - 3; $y--)
                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                @endfor
            </select>

            <select name="bulan" onchange="this.form.submit()" class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 bg-slate-50 focus:outline-none focus:border-emerald-500">
                <option value="all" {{ $bulan == 'all' ? 'selected' : '' }}>Semua Bulan (Jan – Des)</option>
                @php
                    $namaBulan = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
                @endphp
                @foreach($namaBulan as $num => $label)
                    <option value="{{ $num }}" {{ $bulan == $num ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>

            @if($bulan !== 'all' || $tahun != date('Y'))
                <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-xl text-xs font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 transition">Reset</a>
            @endif
        </form>
    </section>

    <!-- 3. EMPAT KARTU STATISTIK (KPI) -->
    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
        <a href="{{ route('kebun.index') }}" class="bg-white rounded-2xl p-5 border border-slate-200/80 border-t-4 border-t-emerald-500 shadow-sm hover:-translate-y-0.5 transition block">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Data Kebun</p>
                    <p class="text-3xl font-extrabold text-slate-800 mt-2">{{ $totalKebun }}</p>
                    <p class="text-xs text-emerald-600 font-semibold mt-1.5">Lihat detail blok →</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">🌴</div>
            </div>
        </a>

        <a href="{{ route('hama.index') }}" class="bg-white rounded-2xl p-5 border border-slate-200/80 border-t-4 border-t-amber-500 shadow-sm hover:-translate-y-0.5 transition block">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Data Hama</p>
                    <p class="text-3xl font-extrabold text-slate-800 mt-2">{{ $totalHama }}</p>
                    <p class="text-xs text-amber-600 font-semibold mt-1.5">Referensi hama sawit →</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">🪲</div>
            </div>
        </a>

        <a href="{{ route('penyakit.index') }}" class="bg-white rounded-2xl p-5 border border-slate-200/80 border-t-4 border-t-rose-500 shadow-sm hover:-translate-y-0.5 transition block">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Data Penyakit</p>
                    <p class="text-3xl font-extrabold text-slate-800 mt-2">{{ $totalPenyakit }}</p>
                    <p class="text-xs text-rose-600 font-semibold mt-1.5">Referensi penyakit →</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl">🦠</div>
            </div>
        </a>

        <a href="{{ route('monitoring.index') }}" class="bg-white rounded-2xl p-5 border border-slate-200/80 border-t-4 border-t-blue-500 shadow-sm hover:-translate-y-0.5 transition block">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Catatan Monitoring</p>
                    <p class="text-3xl font-extrabold text-slate-800 mt-2">{{ $totalMonitoring }}</p>
                    <p class="text-xs text-blue-600 font-semibold mt-1.5">Semua riwayat inspeksi →</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">📋</div>
            </div>
        </a>
    </section>

    <!-- 4. GRID 2x2 GRAFIK ANALITIK UTAMA -->
    <section class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Grafik 1: Tren Serangan Bulanan (Jan - Des) -->
        <div class="lg:col-span-8 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                <div>
                    <h3 class="text-base font-extrabold text-slate-800">📈 Tren Serangan Bulanan ({{ $tahun }})</h3>
                    <p class="text-xs text-slate-500">Perbandingan jumlah temuan Hama vs Penyakit dari Januari hingga Desember</p>
                </div>
                <div class="flex items-center gap-3 text-xs font-bold">
                    <span class="flex items-center gap-1.5 text-amber-600"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Hama</span>
                    <span class="flex items-center gap-1.5 text-rose-600"><span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span> Penyakit</span>
                </div>
            </div>
            <div class="h-64">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <!-- Grafik 2: Komposisi Gangguan (Donat) -->
        <div class="lg:col-span-4 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-base font-extrabold text-slate-800">🍩 Komposisi Kondisi Kebun</h3>
                <p class="text-xs text-slate-500">Proporsi hasil pemeriksaan lapangan</p>
            </div>
            <div class="h-48 my-2 flex items-center justify-center">
                <canvas id="donutChart"></canvas>
            </div>
            <div class="grid grid-cols-3 gap-2 text-center pt-2 border-t border-slate-100 text-xs">
                <div class="p-2 rounded-xl bg-amber-50">
                    <p class="font-bold text-amber-700">{{ $countHama }}</p>
                    <p class="text-[11px] text-amber-600">Hama</p>
                </div>
                <div class="p-2 rounded-xl bg-rose-50">
                    <p class="font-bold text-rose-700">{{ $countPenyakit }}</p>
                    <p class="text-[11px] text-rose-600">Penyakit</p>
                </div>
                <div class="p-2 rounded-xl bg-emerald-50">
                    <p class="font-bold text-emerald-700">{{ $countSehat }}</p>
                    <p class="text-[11px] text-emerald-600">Sehat</p>
                </div>
            </div>
        </div>

        <!-- Grafik 3: Top 5 Jenis Hama & Penyakit Terbanyak -->
        <div class="lg:col-span-6 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <div class="mb-4">
                <h3 class="text-base font-extrabold text-slate-800">📊 Top 5 Gangguan Terbanyak</h3>
                <p class="text-xs text-slate-500">Jenis hama atau penyakit yang paling sering ditemukan di lapangan</p>
            </div>
            <div class="h-60">
                <canvas id="topGangguanChart"></canvas>
            </div>
        </div>

        <!-- Grafik 4: Intensitas Gangguan per Kebun -->
        <div class="lg:col-span-6 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <div class="mb-4">
                <h3 class="text-base font-extrabold text-slate-800">🌴 Kebun Paling Terdampak</h3>
                <p class="text-xs text-slate-500">Jumlah temuan gangguan (Hama & Penyakit) berdasarkan lokasi kebun</p>
            </div>
            <div class="h-60">
                <canvas id="kebunChart"></canvas>
            </div>
        </div>
    </section>

    <!-- 5. RIWAYAT MONITORING TERBARU: INTERACTIVE TIMELINE + SMART FIELD INSPECTOR CARD -->
    <section class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-extrabold text-slate-800 flex items-center gap-2">
                    <span>🕒</span> Riwayat Monitoring Kebun Terbaru
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Klik salah satu catatan di sebelah kiri untuk melihat detail gejala dan rekomendasi penanganan dari Master Data.</p>
            </div>
            <a href="{{ route('monitoring.index') }}" class="px-4 py-2 rounded-xl border border-slate-200 hover:bg-slate-50 text-xs font-bold text-slate-700 transition">
                Lihat Semua Riwayat →
            </a>
        </div>

        @if($recentMonitorings->isEmpty())
            <div class="p-12 text-center">
                <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-3xl mx-auto mb-3">🌱</div>
                <h4 class="font-bold text-slate-800">Belum Ada Riwayat Pemeriksaan Kebun</h4>
                <p class="text-xs text-slate-500 max-w-md mx-auto mt-1 mb-4">Silakan tambahkan catatan pemeriksaan lapangan agar grafik dan linimasa otomatis terisi.</p>
                <a href="{{ route('monitoring.index') }}" class="inline-block px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm transition">
                    + Catat Monitoring Sekarang
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-12">
                <!-- Kiri: Interactive Timeline List -->
                <div class="lg:col-span-6 p-6 border-b lg:border-b-0 lg:border-r border-slate-100 max-h-[540px] overflow-y-auto">
                    <div class="relative pl-6 border-l-2 border-slate-200 space-y-3.5" id="timelineContainer">
                        @foreach($recentMonitorings as $index => $item)
                            @php
                                $isPenyakit = $item->jenis_gangguan === 'Penyakit';
                                $isHama     = $item->jenis_gangguan === 'Hama';
                                $dotColor   = $isPenyakit ? 'bg-rose-500 ring-rose-100' : ($isHama ? 'bg-amber-500 ring-amber-100' : 'bg-emerald-500 ring-emerald-100');
                                $badgeClass = $isPenyakit ? 'bg-rose-50 text-rose-700 border-rose-200' : ($isHama ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200');
                            @endphp
                            <div onclick="showInspectorDetail({{ $index }})"
                                 id="timeline-card-{{ $index }}"
                                 class="timeline-item relative cursor-pointer rounded-2xl p-4 border transition {{ $index === 0 ? 'bg-emerald-50/50 border-emerald-400 shadow-sm' : 'bg-white border-slate-200/80 hover:border-emerald-300' }}">
                                <span class="absolute -left-[31px] top-5 w-3.5 h-3.5 rounded-full ring-4 {{ $dotColor }}"></span>

                                <div class="flex items-center justify-between gap-2 mb-1">
                                    <span class="text-xs font-extrabold text-slate-800">{{ $item->kebun->nama_kebun ?? 'Kebun Tidak Diketahui' }}</span>
                                    <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full border {{ $badgeClass }}">
                                        {{ $item->jenis_gangguan ?? 'Sehat' }}
                                    </span>
                                </div>

                                <p class="text-sm font-bold text-slate-900">{{ $item->nama_gangguan ?: 'Kondisi Tanaman Sehat' }}</p>
                                <p class="text-xs text-slate-500 line-clamp-1 mt-0.5">{{ $item->keterangan_kondisi }}</p>

                                <div class="mt-2.5 pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                                    <span>📅 {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}</span>
                                    <span class="text-emerald-700 font-semibold">Klik untuk detail →</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Kanan: Smart Field Inspector Card -->
                <div class="lg:col-span-6 p-6 bg-slate-50/60 flex flex-col justify-between" id="inspectorDetailContainer">
                    <!-- Diisi otomatis oleh JavaScript -->
                </div>
            </div>
        @endif
    </section>

</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@php
    $inspectorItems = $recentMonitorings->map(function($item) {
        return [
            'id'         => $item->id,
            'kode'       => '#MON-' . str_pad($item->id, 3, '0', STR_PAD_LEFT),
            'tanggal'    => \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y'),
            'kebun'      => $item->kebun->nama_kebun ?? 'Kebun Tidak Diketahui',
            'jenis'      => $item->jenis_gangguan ?? 'Sehat',
            'gangguan'   => $item->nama_gangguan ?: 'Kondisi Tanaman Optimal',
            'keterangan' => $item->keterangan_kondisi ?: 'Tidak ada catatan tambahan.',
            'rekomendasi'=> $item->rekomendasi_sop,
            'edit_url'   => route('monitoring.index'),
            'delete_url' => route('monitoring.destroy', $item->id),
        ];
    })->values();
@endphp

<script>
    // === 1. INISIALISASI 4 GRAFIK CHART.JS ===
    const bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

    new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: {
            labels: bulanLabels,
            datasets: [
                {
                    label: 'Hama',
                    data: @json($trenHama),
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245, 158, 11, 0.12)',
                    borderWidth: 2.5,
                    tension: 0.35,
                    fill: true,
                    pointRadius: 3
                },
                {
                    label: 'Penyakit',
                    data: @json($trenPenyakit),
                    borderColor: '#f43f5e',
                    backgroundColor: 'rgba(244, 63, 94, 0.10)',
                    borderWidth: 2.5,
                    tension: 0.35,
                    fill: true,
                    pointRadius: 3
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } },
                x: { grid: { display: false } }
            }
        }
    });

    const totalDonut = {{ $countHama + $countPenyakit + $countSehat }};
    new Chart(document.getElementById('donutChart'), {
        type: 'doughnut',
        data: {
            labels: totalDonut > 0 ? ['Hama', 'Penyakit', 'Sehat'] : ['Belum Ada Data'],
            datasets: [{
                data: totalDonut > 0 ? [{{ $countHama }}, {{ $countPenyakit }}, {{ $countSehat }}] : [1],
                backgroundColor: totalDonut > 0 ? ['#f59e0b', '#f43f5e', '#10b981'] : ['#e2e8f0'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '68%',
            plugins: { legend: { display: false } }
        }
    });

    new Chart(document.getElementById('topGangguanChart'), {
        type: 'bar',
        data: {
            labels: @json($topGangguan->pluck('nama_gangguan')),
            datasets: [{
                label: 'Jumlah Kasus',
                data: @json($topGangguan->pluck('total')),
                backgroundColor: '#059669',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } },
                x: { grid: { display: false } }
            }
        }
    });

    new Chart(document.getElementById('kebunChart'), {
        type: 'bar',
        data: {
            labels: @json($kebunTerdampak->pluck('nama_kebun')),
            datasets: [{
                label: 'Total Gangguan',
                data: @json($kebunTerdampak->pluck('gangguan_count')),
                backgroundColor: '#0284c7',
                borderRadius: 8
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true, ticks: { precision: 0 } },
                y: { grid: { display: false } }
            }
        }
    });

    // === 2. SMART FIELD INSPECTOR CARD ===
    const inspectorData = @json($inspectorItems);
    const csrfToken = '{{ csrf_token() }}';
    const userName = '{{ auth()->user()->name ?? "Via" }}';

    function showInspectorDetail(index) {
        if (!inspectorData.length) return;
        const item = inspectorData[index];

        document.querySelectorAll('.timeline-item').forEach((el, idx) => {
            if (idx === index) {
                el.classList.add('bg-emerald-50/50', 'border-emerald-400', 'shadow-sm');
                el.classList.remove('bg-white', 'border-slate-200/80');
            } else {
                el.classList.remove('bg-emerald-50/50', 'border-emerald-400', 'shadow-sm');
                el.classList.add('bg-white', 'border-slate-200/80');
            }
        });

        const isPenyakit = item.jenis === 'Penyakit';
        const isHama     = item.jenis === 'Hama';

        const theme = isPenyakit ? {
            grad: 'from-rose-50 via-rose-50/60 to-white border-rose-100',
            iconBg: 'bg-rose-100 text-rose-600 border-rose-200/70',
            icon: '🦠',
            labelColor: 'text-rose-600',
            pill: 'text-rose-700 border-rose-200',
            dot: 'bg-rose-500',
            statusText: 'Perlu Penanganan'
        } : isHama ? {
            grad: 'from-amber-50 via-amber-50/60 to-white border-amber-100',
            iconBg: 'bg-amber-100 text-amber-600 border-amber-200/70',
            icon: '🪲',
            labelColor: 'text-amber-600',
            pill: 'text-amber-700 border-amber-200',
            dot: 'bg-amber-500',
            statusText: 'Pantauan Hama'
        } : {
            grad: 'from-emerald-50 via-emerald-50/60 to-white border-emerald-100',
            iconBg: 'bg-emerald-100 text-emerald-600 border-emerald-200/70',
            icon: '🌿',
            labelColor: 'text-emerald-600',
            pill: 'text-emerald-700 border-emerald-200',
            dot: 'bg-emerald-500',
            statusText: 'Kondisi Sehat'
        };

        document.getElementById('inspectorDetailContainer').innerHTML = `
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden flex flex-col justify-between h-full">
                <div>
                    <div class="bg-gradient-to-r ${theme.grad} px-6 py-5 border-b flex items-start justify-between gap-4">
                        <div class="flex items-start gap-3.5">
                            <div class="w-12 h-12 rounded-2xl ${theme.iconBg} flex items-center justify-center text-2xl shrink-0 border">
                                ${theme.icon}
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[11px] font-extrabold uppercase tracking-wider ${theme.labelColor}">${item.jenis}</span>
                                    <span class="text-slate-300">•</span>
                                    <span class="text-xs font-medium text-slate-500">${item.tanggal}</span>
                                </div>
                                <h4 class="text-xl font-extrabold text-slate-900 mt-0.5 leading-snug">${item.gangguan}</h4>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-white ${theme.pill} border shrink-0">
                            <span class="w-2 h-2 rounded-full ${theme.dot}"></span>
                            ${theme.statusText}
                        </span>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70 flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-emerald-100/80 text-emerald-700 flex items-center justify-center text-base shrink-0">🌴</div>
                                <div class="min-w-0">
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Lokasi Blok Kebun</p>
                                    <p class="text-xs font-extrabold text-slate-800 truncate mt-0.5">${item.kebun}</p>
                                </div>
                            </div>
                            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70 flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-blue-100/80 text-blue-700 flex items-center justify-center text-base shrink-0">📋</div>
                                <div class="min-w-0">
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Dicatat Oleh</p>
                                    <p class="text-xs font-extrabold text-slate-800 truncate mt-0.5">${userName} • Pengelola Kebun</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-50/70 border border-slate-200/80 border-l-4 border-l-slate-700">
                            <p class="text-xs font-extrabold text-slate-800 flex items-center gap-1.5 mb-1.5">
                                <span>📝</span> Keterangan Kondisi Lapangan
                            </p>
                            <p class="text-xs text-slate-600 leading-relaxed">${item.keterangan}</p>
                        </div>

                        <div class="p-4 rounded-2xl bg-gradient-to-br from-emerald-50 to-teal-50/60 border border-emerald-200/90">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-xs font-extrabold text-emerald-900 flex items-center gap-1.5">
                                    <span>🛡️</span> Referensi Penanganan (Master Data)
                                </p>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800">Rekomendasi SOP</span>
                            </div>
                            <p class="text-xs text-emerald-900/90 leading-relaxed">${item.rekomendasi}</p>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-[11px] text-slate-400">ID Catatan ${item.kode}</span>
                    <div class="flex items-center gap-2">
                        <a href="${item.edit_url}" class="px-3.5 py-1.5 rounded-xl bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 text-xs font-bold transition">
                            ✏️ Kelola
                        </a>
                        <form action="${item.delete_url}" method="POST" onsubmit="return confirm('Hapus catatan monitoring ini?')">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 text-xs font-bold transition">
                                🗑️ Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        `;
    }

    if (inspectorData.length > 0) {
        showInspectorDetail(0);
    }
</script>
@endsection