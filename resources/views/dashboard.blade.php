@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
  .stat-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border-radius: 16px;
  }
  .stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08) !important;
  }
</style>

<!-- Hero Welcome Banner -->
<div class="card border-0 text-white mb-4 overflow-hidden shadow-sm" style="background: linear-gradient(135deg, #064e3b 0%, #059669 60%, #10b981 100%); border-radius: 18px;">
  <div class="card-body p-4 p-md-5 d-flex flex-column flex-lg-row align-items-lg-center justify-content-between">
    <div class="mb-3 mb-lg-0">
      <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
        <span class="badge bg-white text-success fw-semibold px-3 py-2 rounded-pill">
          <i class="bi bi-shield-check me-1"></i> SIMHASA v1.0 • Kelapa Sawit
        </span>
        <span class="badge bg-dark bg-opacity-25 text-white fw-normal px-3 py-2 rounded-pill">
          <i class="bi bi-calendar3 me-1"></i> {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
        </span>
      </div>
      <h2 class="fw-bold mb-2">Selamat Datang di Dashboard SIMHASA</h2>
      <p class="mb-0 opacity-75" style="max-width: 620px;">
        Sistem Informasi Monitoring Hama dan Penyakit Tanaman Kelapa Sawit Berbasis Web. Pantau kondisi perkebunan, referensi gangguan tanaman, dan riwayat pemeriksaan dalam satu layar.
      </p>
    </div>
    <div class="d-flex gap-2 flex-shrink-0">
      <a href="{{ route('monitoring.index') }}" class="btn btn-light text-success fw-semibold px-4 py-2 rounded-3 shadow-sm">
        <i class="bi bi-clipboard2-pulse me-1"></i> Catat Monitoring
      </a>
      <a href="{{ route('kebun.index') }}" class="btn btn-outline-light fw-semibold px-4 py-2 rounded-3">
        <i class="bi bi-plus-lg me-1"></i> Data Kebun
      </a>
    </div>
  </div>
</div>

<!-- 4 Kartu Statistik Utama -->
<div class="row g-4 mb-4">
  <div class="col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm h-100 stat-card">
      <div class="card-body p-4 d-flex align-items-center justify-content-between">
        <div>
          <p class="text-muted small fw-semibold text-uppercase mb-1">Total Data Kebun</p>
          <h2 class="fw-bold mb-1 text-dark">{{ $totalKebun ?? 0 }}</h2>
          <a href="{{ route('kebun.index') }}" class="text-success small text-decoration-none fw-medium">
            Lihat detail <i class="bi bi-arrow-right"></i>
          </a>
        </div>
        <div class="rounded-4 d-flex align-items-center justify-content-center" style="width: 58px; height: 58px; background-color: #dcfce7; color: #16a34a;">
          <i class="bi bi-tree-fill fs-3"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm h-100 stat-card">
      <div class="card-body p-4 d-flex align-items-center justify-content-between">
        <div>
          <p class="text-muted small fw-semibold text-uppercase mb-1">Total Data Hama</p>
          <h2 class="fw-bold mb-1 text-dark">{{ $totalHama ?? 0 }}</h2>
          <a href="{{ route('hama.index') }}" class="text-warning small text-decoration-none fw-medium">
            Lihat detail <i class="bi bi-arrow-right"></i>
          </a>
        </div>
        <div class="rounded-4 d-flex align-items-center justify-content-center" style="width: 58px; height: 58px; background-color: #fef3c7; color: #d97706;">
          <i class="bi bi-bug-fill fs-3"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm h-100 stat-card">
      <div class="card-body p-4 d-flex align-items-center justify-content-between">
        <div>
          <p class="text-muted small fw-semibold text-uppercase mb-1">Total Data Penyakit</p>
          <h2 class="fw-bold mb-1 text-dark">{{ $totalPenyakit ?? 0 }}</h2>
          <a href="{{ route('penyakit.index') }}" class="text-danger small text-decoration-none fw-medium">
            Lihat detail <i class="bi bi-arrow-right"></i>
          </a>
        </div>
        <div class="rounded-4 d-flex align-items-center justify-content-center" style="width: 58px; height: 58px; background-color: #fee2e2; color: #dc2626;">
          <i class="bi bi-virus fs-3"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm h-100 stat-card">
      <div class="card-body p-4 d-flex align-items-center justify-content-between">
        <div>
          <p class="text-muted small fw-semibold text-uppercase mb-1">Catatan Monitoring</p>
          <h2 class="fw-bold mb-1 text-dark">{{ $totalMonitoring ?? 0 }}</h2>
          <a href="{{ route('monitoring.index') }}" class="text-primary small text-decoration-none fw-medium">
            Lihat detail <i class="bi bi-arrow-right"></i>
          </a>
        </div>
        <div class="rounded-4 d-flex align-items-center justify-content-center" style="width: 58px; height: 58px; background-color: #dbeafe; color: #2563eb;">
          <i class="bi bi-clipboard2-pulse-fill fs-3"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Bagian Tengah: Grafik Statistik Monitoring & Riwayat Terbaru -->
<div class="row g-4 mb-4">
  <div class="col-lg-4">
    <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
      <div class="card-header bg-white border-bottom py-3 px-4">
        <h6 class="fw-bold mb-0"><i class="bi bi-pie-chart-fill text-success me-2"></i>Statistik Kondisi Lapangan</h6>
      </div>
      <div class="card-body p-4 d-flex flex-column align-items-center justify-content-center">
        @if(($totalMonitoring ?? 0) > 0)
          <div style="width: 210px; height: 210px;">
            <canvas id="monitoringChart"></canvas>
          </div>
          <div class="d-flex justify-content-center gap-3 mt-3 small">
            <span><i class="bi bi-circle-fill text-warning me-1"></i>Hama ({{ $countHama }})</span>
            <span><i class="bi bi-circle-fill text-danger me-1"></i>Penyakit ({{ $countPenyakit }})</span>
            <span><i class="bi bi-circle-fill text-success me-1"></i>Sehat ({{ $countSehat }})</span>
          </div>
        @else
          <div class="text-center text-muted py-4">
            <i class="bi bi-bar-chart fs-1 opacity-50"></i>
            <p class="small mt-2 mb-0">Grafik akan otomatis muncul setelah ada data monitoring.</p>
          </div>
        @endif
      </div>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
      <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0"><i class="bi bi-clock-history text-success me-2"></i>Riwayat Monitoring Kebun Terbaru</h6>
        <a href="{{ route('monitoring.index') }}" class="btn btn-sm btn-outline-success rounded-3">Semua Riwayat</a>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th class="ps-4">Tanggal</th>
                <th>Nama Kebun</th>
                <th>Status / Gangguan</th>
                <th class="pe-4">Keterangan</th>
              </tr>
            </thead>
            <tbody>
              @forelse($recentMonitorings ?? [] as $m)
              <tr>
                <td class="ps-4 text-muted small">{{ \Carbon\Carbon::parse($m->tanggal)->format('d M Y') }}</td>
                <td class="fw-semibold">{{ $m->kebun->nama_kebun ?? '-' }}</td>
                <td>
                  @if($m->jenis_gangguan == 'Hama')
                    <span class="badge bg-warning-subtle text-warning px-2 py-1">Hama: {{ $m->nama_gangguan ?: '-' }}</span>
                  @elseif($m->jenis_gangguan == 'Penyakit')
                    <span class="badge bg-danger-subtle text-danger px-2 py-1">Penyakit: {{ $m->nama_gangguan ?: '-' }}</span>
                  @else
                    <span class="badge bg-success-subtle text-success px-2 py-1">Sehat / Normal</span>
                  @endif
                </td>
                <td class="pe-4 small text-muted">{{ \Illuminate\Support\Str::limit($m->keterangan, 50) }}</td>
              </tr>
              @empty
              <tr>
                <td colspan="4" class="text-center text-muted py-4">
                  Belum ada riwayat pemeriksaan kebun. Silakan tambahkan catatan di menu <strong>Monitoring</strong>.
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Bagian Bawah: Panduan & Ruang Lingkup -->
<div class="card border-0 shadow-sm" style="border-radius: 16px;">
  <div class="card-body p-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
    <div>
      <h6 class="fw-bold mb-1"><i class="bi bi-info-circle-fill text-success me-2"></i>Ruang Lingkup & Batasan Sistem SIMHASA</h6>
      <p class="small text-muted mb-0">
        Sistem ini berfungsi untuk pencatatan data kebun kelapa sawit, dokumentasi referensi hama dan penyakit, serta rekapitulasi riwayat monitoring lapangan secara terstruktur (bukan alat diagnosis otomatis pengganti tenaga ahli perkebunan).
      </p>
    </div>
    <div class="d-flex gap-2 flex-shrink-0">
      <a href="{{ route('hama.index') }}" class="btn btn-sm btn-light border fw-medium">Referensi Hama</a>
      <a href="{{ route('penyakit.index') }}" class="btn btn-sm btn-light border fw-medium">Referensi Penyakit</a>
    </div>
  </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('monitoringChart');
    if (ctx) {
      new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: ['Serangan Hama', 'Serangan Penyakit', 'Sehat / Normal'],
          datasets: [{
            data: [{{ $countHama ?? 0 }}, {{ $countPenyakit ?? 0 }}, {{ $countSehat ?? 0 }}],
            backgroundColor: ['#f59e0b', '#ef4444', '#10b981'],
            borderWidth: 0,
            hoverOffset: 6
          }]
        },
        options: {
          cutout: '68%',
          plugins: {
            legend: { display: false }
          }
        }
      });
    }
  });
</script>
@endsection