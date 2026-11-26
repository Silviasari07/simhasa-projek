@extends('layouts.app')

@section('title', 'Monitoring Kondisi Kebun')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    .simhasa-page { font-family: 'Plus Jakarta Sans', sans-serif; }
    .simhasa-page a { text-decoration: none !important; }
</style>

@php
    $listMonitoring = $monitorings ?? collect();
    $listKebun      = $kebuns ?? collect();
    $listHama       = $hamas ?? collect();
    $listPenyakit   = $penyakits ?? collect();

    $totalInspeksi  = $listMonitoring->count();
    $totalHama      = $listMonitoring->where('jenis_gangguan', 'Hama')->count();
    $totalPenyakit  = $listMonitoring->where('jenis_gangguan', 'Penyakit')->count();
    $totalSehat     = $listMonitoring->where('jenis_gangguan', 'Sehat / Normal')->count();
@endphp

<div class="simhasa-page space-y-6 pb-8">

    <!-- 1. HERO HEADER HIJAU ZAMRUD SERAGAM -->
    <section class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#064e3b] via-[#047857] to-[#10b981] text-white p-7 lg:p-8 shadow-lg shadow-emerald-900/10">
        <div class="absolute -right-12 -top-12 w-56 h-56 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-5">
            <div class="space-y-1.5">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 border border-white/20 text-xs font-semibold text-emerald-50">
                    <span>📋 Log Inspeksi & Kesehatan Blok</span>
                </div>
                <h1 class="text-2xl lg:text-3xl font-extrabold tracking-tight text-white">Monitoring Kondisi Kebun</h1>
                <p class="text-sm text-emerald-50/90 max-w-xl">
                    Pencatatan berkala hasil pemeriksaan lapangan terkait kondisi tanaman, serangan hama, maupun gejala penyakit pada setiap blok kebun kelapa sawit.
                </p>
            </div>

            <button type="button" onclick="openMonitoringModal('create')"
                class="px-5 py-3 rounded-xl bg-white text-emerald-900 font-bold text-sm shadow-md hover:bg-emerald-50 transition flex items-center gap-2 shrink-0 cursor-pointer">
                <span class="text-base">＋</span> Catat Monitoring
            </button>
        </div>
    </section>

    <!-- NOTIFIKASI SUKSES / ERROR -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-base">✅</span>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900">✕</button>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs space-y-1">
            <p class="font-extrabold">⚠️ Periksa kembali inputan kamu:</p>
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- 2. MINI KPI STATISTIK MONITORING (4 KARTU) -->
    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 border-l-4 border-l-teal-500 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Inspeksi</p>
                <p class="text-2xl font-extrabold text-slate-800 mt-1">{{ $totalInspeksi }} <span class="text-xs font-semibold text-slate-400">Laporan</span></p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl">📋</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 border-l-4 border-l-emerald-500 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Sehat / Normal</p>
                <p class="text-2xl font-extrabold text-slate-800 mt-1">{{ $totalSehat }} <span class="text-xs font-semibold text-slate-400">Catatan</span></p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">🌿</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 border-l-4 border-l-amber-500 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Temuan Hama</p>
                <p class="text-2xl font-extrabold text-slate-800 mt-1">{{ $totalHama }} <span class="text-xs font-semibold text-slate-400">Kasus</span></p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">🪲</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 border-l-4 border-l-rose-500 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Temuan Penyakit</p>
                <p class="text-2xl font-extrabold text-slate-800 mt-1">{{ $totalPenyakit }} <span class="text-xs font-semibold text-slate-400">Kasus</span></p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl">🦠</div>
        </div>
    </section>

    <!-- 3. TOOLBAR PENCARIAN, FILTER STATUS & SWITCHER TAMPILAN -->
    <section class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex flex-wrap items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-2.5 flex-1 min-w-[260px]">
            <div class="relative flex-1 min-w-[210px] max-w-xs">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm">🔍</span>
                <input type="text" id="searchMonitoringInput" onkeyup="applyMonitoringFilter()"
                    placeholder="Cari kebun, gangguan, keterangan..."
                    class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-xs font-medium text-slate-700 bg-slate-50 focus:bg-white focus:outline-none focus:border-emerald-500 transition">
            </div>

            <!-- Filter Cepat Kategori -->
            <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl text-xs font-bold">
                <button type="button" onclick="setStatusFilter('ALL', this)" class="status-filter-btn px-3 py-1.5 rounded-lg bg-white text-emerald-800 shadow-sm cursor-pointer">Semua</button>
                <button type="button" onclick="setStatusFilter('Sehat / Normal', this)" class="status-filter-btn px-3 py-1.5 rounded-lg text-slate-500 hover:text-slate-800 cursor-pointer">Sehat</button>
                <button type="button" onclick="setStatusFilter('Hama', this)" class="status-filter-btn px-3 py-1.5 rounded-lg text-slate-500 hover:text-slate-800 cursor-pointer">Hama</button>
                <button type="button" onclick="setStatusFilter('Penyakit', this)" class="status-filter-btn px-3 py-1.5 rounded-lg text-slate-500 hover:text-slate-800 cursor-pointer">Penyakit</button>
            </div>
        </div>

        <div class="flex items-center gap-2 bg-slate-100 p-1 rounded-xl">
            <button type="button" id="btnMonitoringCard" onclick="switchMonitoringView('card')"
                class="px-3.5 py-1.5 rounded-lg text-xs font-bold bg-white text-emerald-800 shadow-sm transition cursor-pointer">
                🗂️ Linimasa Kartu
            </button>
            <button type="button" id="btnMonitoringTable" onclick="switchMonitoringView('table')"
                class="px-3.5 py-1.5 rounded-lg text-xs font-bold text-slate-500 hover:text-slate-800 transition cursor-pointer">
                📋 Tabel Ringkas
            </button>
        </div>
    </section>

    <!-- 4. KONTEN UTAMA (EMPTY STATE / LINIMASA KARTU / TABEL) -->
    @if($listMonitoring->isEmpty())
        <section class="bg-white rounded-3xl p-12 border border-slate-200/80 shadow-sm text-center">
            <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-3xl mx-auto mb-3">📋</div>
            <h3 class="text-base font-extrabold text-slate-800">Belum Ada Catatan Monitoring</h3>
            <p class="text-xs text-slate-500 max-w-md mx-auto mt-1 mb-5">
                Pastikan kamu sudah mengisi <strong>Data Kebun</strong> terlebih dahulu, lalu mulai catat hasil pemeriksaan lapangan secara berkala.
            </p>
            <button type="button" onclick="openMonitoringModal('create')"
                class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm transition cursor-pointer">
                ＋ Catat Monitoring Pertama
            </button>
        </section>
    @else
        <!-- MODE A: LINIMASA KARTU INSPEKSI -->
        <section id="monitoringCardView" class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @foreach($listMonitoring as $index => $item)
                @php
                    $namaKebun = $item->kebun->nama_kebun ?? 'Kebun Tidak Diketahui';
                    $lokasiKebun = $item->kebun->lokasi ?? '';
                    $jenis = $item->jenis_gangguan ?? 'Sehat / Normal';
                    $searchText = strtolower($namaKebun . ' ' . $lokasiKebun . ' ' . $jenis . ' ' . ($item->nama_gangguan ?? '') . ' ' . ($item->keterangan ?? ''));

                    if ($jenis === 'Hama') {
                        $badgeClass = 'bg-amber-50 text-amber-700 border-amber-200';
                        $iconBg     = 'bg-amber-50 text-amber-600 border-amber-200/60';
                        $iconEmoji  = '🪲';
                    } elseif ($jenis === 'Penyakit') {
                        $badgeClass = 'bg-rose-50 text-rose-700 border-rose-200';
                        $iconBg     = 'bg-rose-50 text-rose-600 border-rose-200/60';
                        $iconEmoji  = '🦠';
                    } else {
                        $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                        $iconBg     = 'bg-emerald-50 text-emerald-600 border-emerald-200/60';
                        $iconEmoji  = '🌿';
                    }
                @endphp
                <div class="monitoring-item bg-white rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-md hover:border-emerald-300 transition overflow-hidden flex flex-col justify-between"
                     data-search="{{ $searchText }}" data-status="{{ $jenis }}">
                    <div>
                        <!-- Header Kartu -->
                        <div class="px-5 py-4 bg-gradient-to-r from-emerald-50/70 to-white border-b border-slate-100 flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-11 h-11 rounded-2xl {{ $iconBg }} flex items-center justify-center text-xl shrink-0 border">
                                    {{ $iconEmoji }}
                                </div>
                                <div class="min-w-0">
                                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">
                                        📅 {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                    </span>
                                    <h3 class="text-base font-extrabold text-slate-900 truncate">{{ $namaKebun }}</h3>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded-full border {{ $badgeClass }} text-[11px] font-bold shrink-0">
                                {{ $jenis }}
                            </span>
                        </div>

                        <!-- Isi Detail Inspeksi -->
                        <div class="p-5 space-y-3">
                            <div class="flex items-center justify-between text-xs bg-slate-50 px-3.5 py-2.5 rounded-xl border border-slate-200/70">
                                <span class="text-slate-500 font-semibold">Identifikasi Gangguan:</span>
                                <span class="font-extrabold text-slate-800">{{ $item->nama_gangguan ?: 'Tidak Ada Gangguan' }}</span>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-emerald-50/50 border border-emerald-200/60">
                                <p class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-800 mb-1 flex items-center gap-1.5">
                                    <span>📝</span> Keterangan Kondisi Lapangan
                                </p>
                                <p class="text-xs text-slate-700 leading-relaxed">{{ $item->keterangan }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Aksi -->
                    <div class="px-5 py-3 bg-slate-50/80 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[11px] text-slate-400 font-medium">ID: #MON-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</span>
                        <div class="flex items-center gap-2">
                            <button type="button"
                                onclick='openMonitoringModal("edit", @json($item))'
                                class="px-3 py-1.5 rounded-xl bg-white hover:bg-amber-50 text-amber-700 border border-slate-200 hover:border-amber-200 text-xs font-bold transition cursor-pointer">
                                ✏️ Edit
                            </button>
                            <form action="{{ route('monitoring.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus catatan monitoring ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 rounded-xl bg-white hover:bg-rose-50 text-rose-600 border border-slate-200 hover:border-rose-200 text-xs font-bold transition cursor-pointer">
                                    🗑️ Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </section>

        <!-- MODE B: TABEL MODERN -->
        <section id="monitoringTableView" class="hidden bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                            <th class="py-3.5 px-5">No</th>
                            <th class="py-3.5 px-5">Tanggal</th>
                            <th class="py-3.5 px-5">Nama Kebun</th>
                            <th class="py-3.5 px-5">Jenis Gangguan</th>
                            <th class="py-3.5 px-5">Nama Gangguan</th>
                            <th class="py-3.5 px-5">Keterangan Kondisi</th>
                            <th class="py-3.5 px-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @foreach($listMonitoring as $index => $item)
                            @php
                                $namaKebun = $item->kebun->nama_kebun ?? '-';
                                $jenis = $item->jenis_gangguan ?? 'Sehat / Normal';
                                $searchText = strtolower($namaKebun . ' ' . $jenis . ' ' . ($item->nama_gangguan ?? '') . ' ' . ($item->keterangan ?? ''));

                                if ($jenis === 'Hama') {
                                    $badgeClass = 'bg-amber-50 text-amber-700 border-amber-200';
                                } elseif ($jenis === 'Penyakit') {
                                    $badgeClass = 'bg-rose-50 text-rose-700 border-rose-200';
                                } else {
                                    $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                }
                            @endphp
                            <tr class="monitoring-row hover:bg-emerald-50/40 transition" data-search="{{ $searchText }}" data-status="{{ $jenis }}">
                                <td class="py-3.5 px-5 font-bold text-slate-400">{{ $index + 1 }}</td>
                                <td class="py-3.5 px-5 font-semibold text-slate-600 whitespace-nowrap">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                <td class="py-3.5 px-5 font-extrabold text-slate-800">{{ $namaKebun }}</td>
                                <td class="py-3.5 px-5">
                                    <span class="px-2.5 py-1 rounded-full border {{ $badgeClass }} font-bold whitespace-nowrap">
                                        {{ $jenis }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 font-semibold text-slate-700">{{ $item->nama_gangguan ?: '-' }}</td>
                                <td class="py-3.5 px-5 text-slate-600 max-w-xs">{{ $item->keterangan }}</td>
                                <td class="py-3.5 px-5 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5">
                                        <button type="button" onclick='openMonitoringModal("edit", @json($item))'
                                            class="px-3 py-1.5 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-700 font-bold transition cursor-pointer">
                                            Edit
                                        </button>
                                        <form action="{{ route('monitoring.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus catatan monitoring ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold transition cursor-pointer">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    @endif

</div>

<!-- MODAL CATAT / EDIT MONITORING KEBUN -->
<div id="monitoringModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4">
    <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-[#064e3b] to-[#047857] text-white flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <span class="text-xl">📋</span>
                <h3 id="monitoringModalTitle" style="color: #ffffff !important;" class="text-base font-extrabold">Catat Hasil Monitoring Kebun</h3>
            </div>
            <button type="button" onclick="closeMonitoringModal()" class="w-8 h-8 rounded-full bg-white/15 hover:bg-white/25 text-white flex items-center justify-center text-sm cursor-pointer">✕</button>
        </div>

        <form id="monitoringForm" method="POST" action="{{ route('monitoring.store') }}" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="_method" id="monitoringFormMethod" value="POST">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-extrabold text-slate-700 mb-1.5">Tanggal Pemeriksaan <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal" id="inputTanggalMonitoring" value="{{ date('Y-m-d') }}" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-extrabold text-slate-700 mb-1.5">Jenis Gangguan <span class="text-rose-500">*</span></label>
                    <select name="jenis_gangguan" id="inputJenisGangguan" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium bg-white focus:outline-none focus:border-emerald-500">
                        <option value="Hama">🪲 Hama</option>
                        <option value="Penyakit">🦠 Penyakit</option>
                        <option value="Sehat / Normal">🌿 Sehat / Normal</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-extrabold text-slate-700 mb-1.5">Pilih Kebun <span class="text-rose-500">*</span></label>
                <select name="kebun_id" id="inputKebunId" required
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium bg-white focus:outline-none focus:border-emerald-500">
                    <option value="">-- Pilih Kebun Kelapa Sawit --</option>
                    @foreach($listKebun as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_kebun }} - Pemilik: {{ $k->pemilik }}</option>
                    @endforeach
                </select>
                @if($listKebun->isEmpty())
                    <p class="text-[11px] text-rose-600 font-semibold mt-1">⚠️ Data kebun masih kosong. Silakan isi menu <strong>Data Kebun</strong> terlebih dahulu.</p>
                @endif
            </div>

            <div>
                <label class="block text-xs font-extrabold text-slate-700 mb-1.5">Nama Hama / Penyakit (Jika Diketahui)</label>
                <input type="text" name="nama_gangguan" id="inputNamaGangguan" list="daftarGangguan"
                    placeholder="Ketik atau pilih dari daftar Hama / Penyakit..."
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-emerald-500">
                <datalist id="daftarGangguan">
                    @foreach($listHama as $h)
                        <option value="{{ $h->nama_hama }} (Hama)">
                    @endforeach
                    @foreach($listPenyakit as $p)
                        <option value="{{ $p->nama_penyakit }} (Penyakit)">
                    @endforeach
                </datalist>
            </div>

            <div>
                <label class="block text-xs font-extrabold text-slate-700 mb-1.5">Keterangan Kondisi Lapangan <span class="text-rose-500">*</span></label>
                <textarea name="keterangan" id="inputKeteranganMonitoring" rows="3" required
                    placeholder="Catat detail kondisi pokok sawit, tingkat serangan, atau tindakan awal di lapangan..."
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-emerald-500"></textarea>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeMonitoringModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm transition cursor-pointer">
                    💾 Simpan Monitoring
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let activeStatusFilter = 'ALL';

    function openMonitoringModal(mode, data = null) {
        const modal = document.getElementById('monitoringModal');
        const form = document.getElementById('monitoringForm');
        const title = document.getElementById('monitoringModalTitle');
        const method = document.getElementById('monitoringFormMethod');

        if (mode === 'edit' && data) {
            title.textContent = 'Edit Catatan Monitoring';
            form.action = `/monitoring/${data.id}`;
            method.value = 'PUT';
            document.getElementById('inputTanggalMonitoring').value = data.tanggal || '';
            document.getElementById('inputKebunId').value = data.kebun_id || '';
            document.getElementById('inputJenisGangguan').value = data.jenis_gangguan || 'Hama';
            document.getElementById('inputNamaGangguan').value = data.nama_gangguan || '';
            document.getElementById('inputKeteranganMonitoring').value = data.keterangan || '';
        } else {
            title.textContent = 'Catat Hasil Monitoring Kebun';
            form.action = "{{ route('monitoring.store') }}";
            method.value = 'POST';
            form.reset();
            document.getElementById('inputTanggalMonitoring').value = "{{ date('Y-m-d') }}";
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeMonitoringModal() {
        const modal = document.getElementById('monitoringModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function switchMonitoringView(view) {
        const cardView = document.getElementById('monitoringCardView');
        const tableView = document.getElementById('monitoringTableView');
        const btnCard = document.getElementById('btnMonitoringCard');
        const btnTable = document.getElementById('btnMonitoringTable');
        if (!cardView || !tableView) return;

        if (view === 'card') {
            cardView.classList.remove('hidden');
            tableView.classList.add('hidden');
            btnCard.className = 'px-3.5 py-1.5 rounded-lg text-xs font-bold bg-white text-emerald-800 shadow-sm transition cursor-pointer';
            btnTable.className = 'px-3.5 py-1.5 rounded-lg text-xs font-bold text-slate-500 hover:text-slate-800 transition cursor-pointer';
        } else {
            cardView.classList.add('hidden');
            tableView.classList.remove('hidden');
            btnTable.className = 'px-3.5 py-1.5 rounded-lg text-xs font-bold bg-white text-emerald-800 shadow-sm transition cursor-pointer';
            btnCard.className = 'px-3.5 py-1.5 rounded-lg text-xs font-bold text-slate-500 hover:text-slate-800 transition cursor-pointer';
        }
    }

    function setStatusFilter(status, btn) {
        activeStatusFilter = status;
        document.querySelectorAll('.status-filter-btn').forEach(el => {
            el.className = 'status-filter-btn px-3 py-1.5 rounded-lg text-slate-500 hover:text-slate-800 cursor-pointer';
        });
        btn.className = 'status-filter-btn px-3 py-1.5 rounded-lg bg-white text-emerald-800 shadow-sm cursor-pointer';
        applyMonitoringFilter();
    }

    function applyMonitoringFilter() {
        const keyword = document.getElementById('searchMonitoringInput').value.toLowerCase();
        document.querySelectorAll('.monitoring-item, .monitoring-row').forEach(el => {
            const text = el.getAttribute('data-search') || '';
            const status = el.getAttribute('data-status') || '';
            const matchText = text.includes(keyword);
            const matchStatus = activeStatusFilter === 'ALL' || status === activeStatusFilter;
            el.style.display = (matchText && matchStatus) ? '' : 'none';
        });
    }
</script>
@endsection