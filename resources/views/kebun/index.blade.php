@extends('layouts.app')

@section('title', 'Data Kebun Kelapa Sawit')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
  <div>
    <h1 class="page-title">Kelola Data Kebun</h1>
    <p class="page-subtitle">Daftar pencatatan data perkebunan kelapa sawit SIMHASA.</p>
  </div>
  <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahKebun">
    <i class="bi bi-plus-circle me-1"></i> Tambah Kebun
  </button>
</div>

<div class="card p-4">
  <div class="table-responsive">
    <table class="table table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th width="5%">No</th>
          <th width="25%">Nama Kebun</th>
          <th width="20%">Nama Pemilik</th>
          <th width="25%">Lokasi</th>
          <th width="15%">Luas Lahan</th>
          <th width="10%" class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($kebuns as $index =>$item)
        <tr>
          <td>{{ $index + 1 }}</td>
          <td class="fw-semibold">{{ $item->nama_kebun }}</td>
          <td>{{ $item->pemilik }}</td>
          <td>{{ $item->lokasi }}</td>
          <td><span class="badge bg-success-subtle text-success">{{ $item->luas_lahan }}</span></td>
          <td class="text-center">
            <button type="button" class="btn btn-sm btn-warning text-white" data-bs-toggle="modal" data-bs-target="#modalEditKebun{{ $item->id }}">
              <i class="bi bi-pencil-square"></i>
            </button>
            <form action="{{ route('kebun.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data kebun ini?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-danger">
                <i class="bi bi-trash"></i>
              </button>
            </form>
          </td>
        </tr>

        <!-- Modal Edit Kebun -->
        <div class="modal fade" id="modalEditKebun{{ $item->id }}" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <form action="{{ route('kebun.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                  <h5 class="modal-title fw-bold">Edit Data Kebun</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <div class="mb-3">
                    <label class="form-label">Nama Kebun</label>
                    <input type="text" name="nama_kebun" class="form-control" value="{{ $item->nama_kebun }}" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Nama Pemilik</label>
                    <input type="text" name="pemilik" class="form-control" value="{{ $item->pemilik }}" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Lokasi Kebun</label>
                    <input type="text" name="lokasi" class="form-control" value="{{ $item->lokasi }}" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Luas Lahan</label>
                    <input type="text" name="luas_lahan" class="form-control" value="{{ $item->luas_lahan }}" required>
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
            Belum ada data kebun. Klik tombol <strong>Tambah Kebun</strong> di atas untuk menambahkan data baru.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<!-- Modal Tambah Kebun -->
<div class="modal fade" id="modalTambahKebun" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('kebun.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title fw-bold">Tambah Data Kebun Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nama Kebun</label>
            <input type="text" name="nama_kebun" class="form-control" placeholder="Contoh: Kebun Sawit Blok A" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Nama Pemilik</label>
            <input type="text" name="pemilik" class="form-control" placeholder="Contoh: Bpk. Hendra" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Lokasi Kebun</label>
            <input type="text" name="lokasi" class="form-control" placeholder="Contoh: Kampar, Riau" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Luas Lahan</label>
            <input type="text" name="luas_lahan" class="form-control" placeholder="Contoh: 5 Hektar" required>
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