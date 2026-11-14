@extends('layouts.app')

@section('title', 'Monitoring Kondisi Kebun')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
  <div>
    <h1 class="page-title">Monitoring Kondisi Kebun</h1>
    <p class="page-subtitle">Pencatatan berkala hasil pemeriksaan hama dan penyakit pada kebun kelapa sawit.</p>
  </div>
  <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahMonitoring">
    <i class="bi bi-plus-circle me-1"></i> Catat Monitoring
  </button>
</div>

<div class="card p-4">
  <div class="table-responsive">
    <table class="table table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th width="5%">No</th>
          <th width="13%">Tanggal</th>
          <th width="20%">Nama Kebun</th>
          <th width="15%">Jenis Gangguan</th>
          <th width="17%">Nama Gangguan</th>
          <th width="20%">Keterangan Kondisi</th>
          <th width="10%" class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($monitorings as $index => $item)
        <tr>
          <td>{{ $index + 1 }}</td>
          <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
          <td class="fw-semibold">{{ $item->kebun->nama_kebun ?? '-' }}</td>
          <td>
            @if($item->jenis_gangguan == 'Hama')
              <span class="badge bg-warning-subtle text-warning">Hama</span>
            @elseif($item->jenis_gangguan == 'Penyakit')
              <span class="badge bg-danger-subtle text-danger">Penyakit</span>
            @else
              <span class="badge bg-success-subtle text-success">{{ $item->jenis_gangguan }}</span>
            @endif
          </td>
          <td>{{ $item->nama_gangguan ?: '-' }}</td>
          <td>{{ $item->keterangan }}</td>
          <td class="text-center">
            <button type="button" class="btn btn-sm btn-warning text-white" data-bs-toggle="modal" data-bs-target="#modalEditMonitoring{{ $item->id }}">
              <i class="bi bi-pencil-square"></i>
            </button>
            <form action="{{ route('monitoring.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus catatan monitoring ini?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-danger">
                <i class="bi bi-trash"></i>
              </button>
            </form>
          </td>
        </tr>

        <!-- Modal Edit Monitoring -->
        <div class="modal fade" id="modalEditMonitoring{{ $item->id }}" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <form action="{{ route('monitoring.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                  <h5 class="modal-title fw-bold">Edit Catatan Monitoring</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <div class="mb-3">
                    <label class="form-label">Tanggal Pemeriksaan</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ $item->tanggal }}" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Kebun yang Diperiksa</label>
                    <select name="kebun_id" class="form-select" required>
                      @foreach($kebuns as $k)
                        <option value="{{ $k->id }}" {{ $item->kebun_id == $k->id ? 'selected' : '' }}>{{ $k->nama_kebun }} ({{ $k->lokasi }})</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Jenis Gangguan</label>
                    <select name="jenis_gangguan" class="form-select" required>
                      <option value="Hama" {{ $item->jenis_gangguan == 'Hama' ? 'selected' : '' }}>Hama</option>
                      <option value="Penyakit" {{ $item->jenis_gangguan == 'Penyakit' ? 'selected' : '' }}>Penyakit</option>
                      <option value="Sehat / Normal" {{ $item->jenis_gangguan == 'Sehat / Normal' ? 'selected' : '' }}>Sehat / Normal</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Nama Hama / Penyakit (Jika Diketahui)</label>
                    <input type="text" name="nama_gangguan" class="form-control" value="{{ $item->nama_gangguan }}" placeholder="Contoh: Ulat Api / Ganoderma">
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Keterangan Kondisi Lapangan</label>
                    <textarea name="keterangan" class="form-control" rows="3" required>{{ $item->keterangan }}</textarea>
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
          <td colspan="7" class="text-center text-muted py-4">
            Belum ada catatan monitoring. Pastikan Anda sudah mengisi <strong>Data Kebun</strong> terlebih dahulu, lalu klik <strong>Catat Monitoring</strong>.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<!-- Modal Tambah Monitoring -->
<div class="modal fade" id="modalTambahMonitoring" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('monitoring.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title fw-bold">Catat Hasil Monitoring Kebun</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Tanggal Pemeriksaan</label>
            <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Pilih Kebun</label>
            <select name="kebun_id" class="form-select" required>
              <option value="">-- Pilih Kebun Kelapa Sawit --</option>
              @foreach($kebuns as $k)
                <option value="{{ $k->id }}">{{ $k->nama_kebun }} - Pemilik: {{ $k->pemilik }}</option>
              @endforeach
            </select>
            @if($kebuns->isEmpty())
              <small class="text-danger">Data kebun masih kosong. Silakan isi menu <strong>Data Kebun</strong> terlebih dahulu.</small>
            @endif
          </div>
          <div class="mb-3">
            <label class="form-label">Jenis Gangguan</label>
            <select name="jenis_gangguan" class="form-select" required>
              <option value="Hama">Hama</option>
              <option value="Penyakit">Penyakit</option>
              <option value="Sehat / Normal">Sehat / Normal</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Nama Hama / Penyakit (Jika Diketahui)</label>
            <input type="text" name="nama_gangguan" class="form-control" list="daftarGangguan" placeholder="Ketik atau pilih nama hama/penyakit...">
            <datalist id="daftarGangguan">
              @foreach($hamas as $h)
                <option value="{{ $h->nama_hama }} (Hama)">
              @endforeach
              @foreach($penyakits as $p)
                <option value="{{ $p->nama_penyakit }} (Penyakit)">
              @endforeach
            </datalist>
          </div>
          <div class="mb-3">
            <label class="form-label">Keterangan Kondisi Lapangan</label>
            <textarea name="keterangan" class="form-control" rows="3" placeholder="Catat kondisi tanaman sawit di lapangan..." required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success">Simpan Monitoring</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection