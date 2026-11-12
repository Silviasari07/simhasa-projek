   <!DOCTYPE html>
   <html lang="en">

   <head>
       <meta charset="UTF-8">
       <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <title>SIMHASA - Dashboard Utama</title>


       <link rel="icon" type="image/png" href="{{ asset('images/favicon.ico') }}">


       <link rel="stylesheet" href="{{ asset('css/main.css') }}">
       <link rel="stylesheet" href="{{ asset('css/style.css') }}">
       <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
   </head>

   <body>

       <!-- Sidebar Component -->
       <div class="sidebar-wrapper" id="sidebar">
           <a href="{{ url('/') }}" class="sidebar-brand">
               <i class="bi bi-asterisk"></i>
               <span>SIMHASA</span>
           </a>

           <div class="flex-grow-1 overflow-y-auto">
               <div class="sidebar-menu-section">
                   <div class="sidebar-menu-title">Menu Utama</div>
                   <ul class="sidebar-menu-list">
                       <li class="sidebar-menu-item">
                           <a href="{{ url('/') }}" class="sidebar-menu-link active">
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
                           <a href="{{ url('/hama') }}" class="sidebar-menu-link">
                               <i class="bi bi-bug-fill"></i>
                               <span>Data Hama</span>
                           </a>
                       </li>
                   </ul>
               </div>
           </div>

           <div class="sidebar-profile">
           <img src="{{ asset('images/avatar.png') }}" alt="Administrator" class="sidebar-profile-img" style="width: 40px; height: 40px; border-radius: 50%;">
                   onerror="this.src='https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=256&auto=format&fit=crop'">
               <div class="sidebar-profile-info">
                   <div class="sidebar-profile-name">Administrator</div>
                   <div class="sidebar-profile-email">admin@simhasa.com</div>
               </div>
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
                   <span class="fw-semibold">Sistem Informasi Hama & Penyakit Tanaman</span>
               </div>
           </header>

           <!-- Header Banner Dashboard SIMHASA -->
           <div class="page-header">
               <div>
                   <h1 class="page-title">Dashboard SIMHASA</h1>
                   <p class="page-subtitle">Selamat datang di sistem pemantauan dan pengelolaan data hama tanaman.</p>
               </div>
           </div>

           <!-- KONTEN UTAMA DASHBOARD SIMHASA -->
           <div class="row g-4">

               <!-- Kartu Ringkasan 1: Total Hama -->
               <div class="col-md-4">
                   <div class="card p-3 d-flex flex-row align-items-center justify-content-between">
                       <div>
                           <span class="text-muted small">Total Data Hama</span>
                           <h2 class="mb-0 fw-bold mt-1">12</h2>
                           <small class="text-success"><i class="bi bi-arrow-up-short"></i> Terdata dalam sistem</small>
                       </div>
                       <div class="bg-light-success p-3 rounded-circle text-success fs-3">
                           <i class="bi bi-bug-fill"></i>
                       </div>
                   </div>
               </div>

               <!-- Kartu Ringkasan 2: Tanaman Teridentifikasi -->
               <div class="col-md-4">
                   <div class="card p-3 d-flex flex-row align-items-center justify-content-between">
                       <div>
                           <span class="text-muted small">Jenis Tanaman</span>
                           <h2 class="mb-0 fw-bold mt-1">5</h2>
                           <small class="text-info"><i class="bi bi-check-circle"></i> Komoditas pertanian</small>
                       </div>
                       <div class="bg-light-info p-3 rounded-circle text-info fs-3">
                           <i class="bi bi-tree-fill"></i>
                       </div>
                   </div>
               </div>

               <!-- Kartu Ringkasan 3: Panduan Penanganan -->
               <div class="col-md-4">
                   <div class="card p-3 d-flex flex-row align-items-center justify-content-between">
                       <div>
                           <span class="text-muted small">Metode Penanganan</span>
                           <h2 class="mb-0 fw-bold mt-1">8</h2>
                           <small class="text-warning"><i class="bi bi-shield-check"></i> Solusi terverifikasi</small>
                       </div>
                       <div class="bg-light-warning p-3 rounded-circle text-warning fs-3">
                           <i class="bi bi-journal-medical"></i>
                       </div>
                   </div>
               </div>

               <!-- Informasi/Pengumuman Sistem -->
               <div class="col-12">
                   <div class="card p-4">
                       <h4 class="card-title fw-bold text-success mb-2">
                           <i class="bi bi-info-circle-fill me-2"></i>Tentang Sistem SIMHASA
                       </h4>
                       <p class="mb-0 text-muted">
                           SIMHASA (Sistem Informasi Hama dan Penyakit Tanaman) dirancang untuk membantu pendataan, identifikasi gejala, serta penyediaan panduan penanganan hama pada tanaman pertanian secara cepat dan efisien. Gunakan menu <strong>Data Hama</strong> di navigasi sebelah kiri untuk mulai mengelola database.
                       </p>
                   </div>
               </div>

           </div>
           <!-- END KONTEN UTAMA -->

           <!-- Footer -->
           <footer class="footer-custom mt-5">
               <div class="footer-left">
                   <span class="footer-logo"><i class="bi bi-asterisk"></i> SIMHASA</span>
                   <span class="footer-separator">|</span>
                   <span class="footer-copy">&copy; 2026 SIMHASA Project</span>
               </div>
           </footer>

       </div>

     <script src="{{ asset('js/dashboard.js') }}"></script>

   </body>

   </html>