@extends('layouts.app')

@section('title', 'Data Hama Kelapa Sawit')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
  <div>
    <h1 class="page-title">Kelola Data Hama</h1>
    <p class="page-subtitle">Daftar inventarisasi informasi hama tanaman kelapa sawit SIMHASA.</p>
  </div>
  <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahHama">
    <i class="bi bi-plus-circle me-1"></i> Tambah Hama
  </button>
</div>

<div class="card p-4">
  <div class="table-responsive">
    <table class="table table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th width="5%">No</th>
          <th width="20%">Nama Hama</th>
          <th width="15%">Jenis Tanaman</th>
          <th width="25%">Gejala Serangan</th>
          <th width="25%">Cara Penanganan</th>
          <th width="10%" class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($hama as $index => $item)
        <tr>
          <td>{{ $index + 1 }}</td>
          <td class="fw-semibold">{{ $item->nama_hama }}</td>
          <td><span class="badge bg-success-subtle text-success">{{ $item->jenis_tanaman }}</span></td>
          <td>{{ $item->gejala }}</td>
          <td>{{ $item->penanganan }}</td>
          <td class="text-center">
            <button type="button" class="btn btn-sm btn-warning text-white" data-bs-toggle="modal" data-bs-target="#modalEditHama{{ $item->id }}">
              <i class="bi bi-pencil-square"></i>
            </button>
            <form action="{{ route('hama.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data hama ini?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-danger">
                <i class="bi bi-trash"></i>
              </button>
            </form>
          </td>
        </tr>

        <!-- Modal Edit Hama -->
        <div class="modal fade" id="modalEditHama{{ $item->id }}" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <form action="{{ route('hama.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                  <h5 class="modal-title fw-bold">Edit Data Hama</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
                  <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        @empty
        <tr>
          <td colspan="6" class="text-center text-muted py-4">
            Belum ada data hama. Klik tombol <strong>Tambah Hama</strong> di atas untuk menambahkan data baru.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<!-- Modal Tambah Hama -->
<div class="modal fade" id="modalTambahHama" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('hama.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title fw-bold">Tambah Data Hama Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nama Hama</label>
            <input type="text" name="nama_hama" class="form-control" placeholder="Contoh: Ulat Api (Setothosea asigna)" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Jenis Tanaman</label>
            <input type="text" name="jenis_tanaman" class="form-control" value="Kelapa Sawit" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Gejala Serangan</label>
            <textarea name="gejala" class="form-control" rows="3" placeholder="Deskripsi gejala pada daun/batang kelapa sawit..." required></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Cara Penanganan</label>
            <textarea name="penanganan" class="form-control" rows="3" placeholder="Langkah pengendalian sesuai referensi pertanian..." required></textarea>
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
@endsection