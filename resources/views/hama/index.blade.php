<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SIMHASA - Data Hama</title>

  <!-- Favicon & Stylesheet -->
  <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.ico') }}">
  <link rel="stylesheet" href="{{ asset('assets/libs/bootstrap/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/libs/bootstrap-icons/bootstrap-icons.css') }}">
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
            <a href="{{ url('/') }}" class="sidebar-menu-link">
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
            <a href="{{ route('hama.index') }}" class="sidebar-menu-link active">
              <i class="bi bi-bug-fill"></i>
              <span>Data Hama</span>
            </a>
          </li>
        </ul>
      </div>
    </div>

    <div class="sidebar-profile">
      <img src="{{ asset('assets/images/avatar.png') }}" alt="Administrator" class="sidebar-profile-img" style="width:36px; height:36px; border-radius:50%;">
      <div class="sidebar-profile-info ms-2">
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

    <!-- Header Banner -->
    <div class="page-header d-flex justify-content-between align-items-center">
      <div>
        <h1 class="page-title">Kelola Data Hama</h1>
        <p class="page-subtitle">Daftar inventarisasi hama tanaman pertanian SIMHASA.</p>
      </div>
      <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-lg me-1"></i> Tambah Hama
      </button>
    </div>

    <!-- Alert Sukses -->
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <!-- Tabel Data Hama -->
    <div class="card p-3">
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead>
            <tr>
              <th width="50">No</th>
              <th>Nama Hama</th>
              <th>Jenis Tanaman</th>
              <th>Gejala Serangan</th>
              <th>Cara Penanganan</th>
              <th width="120">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($hama as $index => $item)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $item->nama_hama }}</strong></td>
                <td><span class="badge bg-success">{{ $item->jenis_tanaman }}</span></td>
                <td>{{ $item->gejala }}</td>
                <td>{{ $item->penanganan }}</td>
                <td>
                  <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $item->id }}">
                    <i class="bi bi-pencil"></i>
                  </button>
                  <form action="{{ route('hama.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>

              <!-- Modal Edit -->
              <div class="modal fade" id="modalEdit{{ $item->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <form action="{{ route('hama.update', $item->id) }}" method="POST">
                      @csrf
                      @method('PUT')
                      <div class="modal-header">
                        <h5 class="modal-title">Edit Data Hama</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <div class="mb-3">
                          <label class="form-label">Nama Hama</label>
                          <input type="text" name="nama_hama" class="form-control" value="{{ $item->nama_hama }}" required>
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Jenis Tanaman</label>
                          <input type="text" name="jenis_tanaman" class="form-control" value="{{ $item->jenis_tanaman }}" required>
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Gejala Serangan</label>
                          <textarea name="gejala" class="form-control" rows="3" required>{{ $item->gejala }}</textarea>
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Cara Penanganan</label>
                          <textarea name="penanganan" class="form-control" rows="3" required>{{ $item->penanganan }}</textarea>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

            @empty
              <tr>
                <td colspan="6" class="text-center py-4 text-muted">
                  Belum ada data hama. Klik tombol <strong>Tambah Hama</strong> di atas untuk menambahkan data baru.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Footer -->
    <footer class="footer-custom mt-5">
      <div class="footer-left">
        <span class="footer-logo"><i class="bi bi-asterisk"></i> SIMHASA</span>
        <span class="footer-separator">|</span>
        <span class="footer-copy">&copy; 2026 SIMHASA Project</span>
      </div>
    </footer>

  </div>

  <!-- Modal Tambah -->
  <div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="{{ route('hama.store') }}" method="POST">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title">Tambah Data Hama Baru</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Nama Hama</label>
              <input type="text" name="nama_hama" class="form-control" placeholder="Contoh: Wereng Coklat" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Jenis Tanaman</label>
              <input type="text" name="jenis_tanaman" class="form-control" placeholder="Contoh: Padi" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Gejala Serangan</label>
              <textarea name="gejala" class="form-control" rows="3" placeholder="Deskripsi gejala..." required></textarea>
            </div>
            <div class="mb-3">
              <label class="form-label">Cara Penanganan</label>
              <textarea name="penanganan" class="form-control" rows="3" placeholder="Langkah pengendalian..." required></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-success">Simpan Data</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>