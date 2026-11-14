<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SIMHASA - @yield('title', 'Sistem Informasi Monitoring Kelapa Sawit')</title>

  <link rel="icon" type="image/png" href="{{ asset('images/favicon.ico') }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>

<body>

  <!-- Sidebar Component -->
  <div class="sidebar-wrapper" id="sidebar">
    <a href="{{ route('dashboard') }}" class="sidebar-brand">
      <i class="bi bi-asterisk"></i>
      <span>SIMHASA</span>
    </a>

    <div class="flex-grow-1 overflow-y-auto">
      <div class="sidebar-menu-section">
        <div class="sidebar-menu-title">Menu Utama</div>
        <ul class="sidebar-menu-list">
          <li class="sidebar-menu-item">
            <a href="{{ route('dashboard') }}" class="sidebar-menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
              <i class="bi bi-grid-fill"></i>
              <span>Dashboard</span>
            </a>
          </li>
        </ul>
      </div>

      <div class="sidebar-menu-section">
        <div class="sidebar-menu-title">Master Data</div>
        <ul class="sidebar-menu-list">
          <li class="sidebar-menu-item">
            <a href="{{ route('kebun.index') }}" class="sidebar-menu-link {{ request()->routeIs('kebun.*') ? 'active' : '' }}">
              <i class="bi bi-tree-fill"></i>
              <span>Data Kebun</span>
            </a>
          </li>
          <li class="sidebar-menu-item">
            <a href="{{ route('hama.index') }}" class="sidebar-menu-link {{ request()->routeIs('hama.*') ? 'active' : '' }}">
              <i class="bi bi-bug-fill"></i>
              <span>Data Hama</span>
            </a>
          </li>
          <li class="sidebar-menu-item">
            <a href="{{ route('penyakit.index') }}" class="sidebar-menu-link {{ request()->routeIs('penyakit.*') ? 'active' : '' }}">
              <i class="bi bi-virus"></i>
              <span>Data Penyakit</span>
            </a>
          </li>
        </ul>
      </div>

      <div class="sidebar-menu-section">
        <div class="sidebar-menu-title">Aktivitas Kebun</div>
        <ul class="sidebar-menu-list">
          <li class="sidebar-menu-item">
            <a href="{{ route('monitoring.index') }}" class="sidebar-menu-link {{ request()->routeIs('monitoring.*') ? 'active' : '' }}">
              <i class="bi bi-clipboard2-pulse-fill"></i>
              <span>Monitoring</span>
            </a>
          </li>
        </ul>
      </div>
    </div>

    <!-- Profil User Login Dinamis + Tombol Logout -->
    <div class="sidebar-profile d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center overflow-hidden">
        <div class="rounded-circle bg-success text-white fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width:36px; height:36px;">
          {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
        </div>
        <div class="sidebar-profile-info ms-2 text-truncate">
          <div class="sidebar-profile-name text-truncate">{{ Auth::user()->name ?? 'Administrator' }}</div>
          <div class="sidebar-profile-email text-truncate">{{ Auth::user()->email ?? 'admin@simhasa.com' }}</div>
        </div>
      </div>
      <form action="{{ route('logout') }}" method="POST" class="ms-2 flex-shrink-0" title="Keluar / Logout">
        @csrf
        <button type="submit" class="btn btn-sm btn-outline-danger border-0 px-2 py-1" onclick="return confirm('Keluar dari SIMHASA?')">
          <i class="bi bi-box-arrow-right"></i>
        </button>
      </form>
    </div>
  </div>

  <!-- Main Content Area -->
  <div class="main-wrapper">

    <!-- Top Navbar -->
    <header class="navbar-custom">
      <div class="navbar-left">
        <button class="sidebar-toggle-btn me-2" id="sidebar-toggle">
          <i class="bi bi-list"></i>
        </button>
      </div>
      <div class="navbar-actions ms-auto">
        <span class="fw-semibold">Sistem Informasi Monitoring Hama & Penyakit Tanaman Kelapa Sawit</span>
      </div>
    </header>

    <!-- Notifikasi Sukses -->
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <!-- Konten Halaman -->
    @yield('content')

    <!-- Footer -->
    <footer class="footer-custom mt-5">
      <div class="footer-left">
        <span class="footer-logo"><i class="bi bi-asterisk"></i> SIMHASA</span>
        <span class="footer-separator">|</span>
        <span class="footer-copy">&copy;  SIMHASA </span>
      </div>
    </footer>

  </div>

 <!-- Fitur Pencarian Real-Time Otomatis untuk Semua Tabel CRUD -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Jangan tampilkan search bar di halaman Dashboard (hanya di halaman kelola data)
      if (window.location.pathname === '/' || window.location.pathname === '') return;

      const tables = document.querySelectorAll('table');
      tables.forEach((table, index) => {
        const tbody = table.querySelector('tbody');
        if (!tbody) return;

        // Buat container kotak pencarian di atas tabel
        const wrapper = document.createElement('div');
        wrapper.className = 'd-flex justify-content-between align-items-center mb-3 px-3 pt-3';
        wrapper.innerHTML = `
          <div class="text-muted small">
            <i class="bi bi-funnel me-1"></i> Menampilkan daftar data tersimpan
          </div>
          <div class="input-group input-group-sm" style="max-width: 280px;">
            <span class="input-group-text bg-white border-end-0 text-muted">
              <i class="bi bi-search"></i>
            </span>
            <input type="text" class="form-control border-start-0 ps-0" id="tableSearch_${index}" placeholder="Cari data di tabel...">
          </div>
        `;

        const tableResponsive = table.closest('.table-responsive') || table;
        tableResponsive.parentNode.insertBefore(wrapper, tableResponsive);

        // Logika penyaringan real-time saat mengetik
        const searchInput = document.getElementById(`tableSearch_${index}`);
        searchInput.addEventListener('keyup', function () {
          const keyword = this.value.toLowerCase().trim();
          const rows = tbody.querySelectorAll('tr');
          let visibleCount = 0;

          rows.forEach(row => {
            // Abaikan baris pesan kosong bawaan
            if (row.querySelector('td[colspan]')) return;

            const text = row.textContent.toLowerCase();
            if (text.includes(keyword)) {
              row.style.display = '';
              visibleCount++;
            } else {
              row.style.display = 'none';
            }
          });
        });
      });
    });
  </script>
</body>

</html>