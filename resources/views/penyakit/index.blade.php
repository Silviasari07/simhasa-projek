@extends('layouts.app')

@section('title', 'Data Penyakit Kelapa Sawit')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
  <div>
    <h1 class="page-title">Kelola Data Penyakit</h1>
    <p class="page-subtitle">Daftar referensi informasi penyakit tanaman kelapa sawit SIMHASA.</p>
  </div>
  <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahPenyakit">
    <i class="bi bi-plus-circle me-1"></i> Tambah Penyakit
  </button>
</div>

<div class="card p-4">
  <div class="table-responsive">
    <table class="table table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th width="5%">No</th>
          <th width="25%">Nama Penyakit</th>
          <th width="30%">Gejala Umum</th>
          <th width="30%">Informasi Penanganan</th>
          <th width="10%" class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($penyakits as $index => $item)
        <tr>
          <td>{{ $index + 1 }}</td>
          <td class="fw-semibold">{{ $item->nama_penyakit }}</td>
          <td>{{ $item->gejala }}</td>
          <td>{{ $item->penanganan }}</td>
          <td class="text-center">
            <button type="button" class="btn btn-sm btn-warning text-white" data-bs-toggle="modal" data-bs-target="#modalEditPenyakit{{ $item->id }}">
              <i class="bi bi-pencil-square"></i>
            </button>
            <form action="{{ route('penyakit.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data penyakit ini?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-danger">
                <i class="bi bi-trash"></i>
              </button>
            </form>
          </td>
        </tr>

        <!-- Modal Edit Penyakit -->
        <div class="modal fade" id="modalEditPenyakit{{ $item->id }}" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <form action="{{ route('penyakit.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                  <h5 class="modal-title fw-bold">Edit Data Penyakit</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <div class="mb-3">
                    <label class="form-label">Nama Penyakit</label>
                    <input type="text" name="nama_penyakit" class="form-control" value="{{ $item->nama_penyakit }}" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Gejala Umum</label>
                    <textarea name="gejala" class="form-control" rows="3" required>{{ $item->gejala }}</textarea>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Informasi Penanganan</label>
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
          <td colspan="5" class="text-center text-muted py-4">
            Belum ada data penyakit. Klik tombol <strong>Tambah Penyakit</strong> di atas untuk menambahkan data baru.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<!-- Modal Tambah Penyakit -->
<div class="modal fade" id="modalTambahPenyakit" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('penyakit.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title fw-bold">Tambah Data Penyakit Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nama Penyakit</label>
            <input type="text" name="nama_penyakit" class="form-control" placeholder="Contoh: Busuk Pangkal Batang (Ganoderma)" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Gejala Umum</label>
            <textarea name="gejala" class="form-control" rows="3" placeholder="Deskripsi gejala pada pelepah/batang kelapa sawit..." required></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Informasi Penanganan</label>
            <textarea name="penanganan" class="form-control" rows="3" placeholder="Langkah penanganan sesuai referensi pertanian..." required></textarea>
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