@extends('layouts.app')

@section('title', 'Data Penyakit Kelapa Sawit')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    .simhasa-page { font-family: 'Plus Jakarta Sans', sans-serif; }
    .simhasa-page a { text-decoration: none !important; }
</style>

@php
    $listPenyakit = $penyakits ?? ($penyakit ?? collect());
    $totalPenyakit = $listPenyakit->count();
@endphp

<div class="simhasa-page space-y-6 pb-8">

    <!-- 1. HERO HEADER HIJAU ZAMRUD SERAGAM -->
    <section class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#064e3b] via-[#047857] to-[#10b981] text-white p-7 lg:p-8 shadow-lg shadow-emerald-900/10">
        <div class="absolute -right-12 -top-12 w-56 h-56 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-5">
            <div class="space-y-1.5">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 border border-white/20 text-xs font-semibold text-emerald-50">
                    <span>🦠 Master Referensi Patogen & Penyakit</span>
                </div>
                <h1 class="text-2xl lg:text-3xl font-extrabold tracking-tight text-white">Kelola Data Penyakit</h1>
                <p class="text-sm text-emerald-50/90 max-w-xl">
                    Katalog diagnosa penyakit tanaman kelapa sawit (jamur, bakteri, dan patogen), gejala klinis pada batang atau daun, serta SOP karantina lapangan.
                </p>
            </div>

            <button type="button" onclick="openPenyakitModal('create')"
                class="px-5 py-3 rounded-xl bg-white text-emerald-900 font-bold text-sm shadow-md hover:bg-emerald-50 transition flex items-center gap-2 shrink-0 cursor-pointer">
                <span class="text-base">＋</span> Tambah Penyakit
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

    <!-- 2. MINI KPI STATISTIK PENYAKIT -->
    <section class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 border-l-4 border-l-rose-500 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Jenis Penyakit</p>
                <p class="text-2xl font-extrabold text-slate-800 mt-1">{{ $totalPenyakit }} <span class="text-xs font-semibold text-slate-400">Patogen</span></p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl">🦠</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 border-l-4 border-l-emerald-500 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Protokol SOP Aktif</p>
                <p class="text-2xl font-extrabold text-slate-800 mt-1">{{ $totalPenyakit }} <span class="text-xs font-semibold text-slate-400">Prosedur</span></p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">🛡️</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 border-l-4 border-l-teal-500 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Status Integrasi</p>
                <p class="text-base font-extrabold text-emerald-700 mt-1.5">Terhubung Monitoring</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl">🩺</div>
        </div>
    </section>

    <!-- 3. TOOLBAR PENCARIAN & SWITCHER TAMPILAN -->
    <section class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex flex-wrap items-center justify-between gap-3">
        <div class="relative flex-1 min-w-[240px] max-w-md">
            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm">🔍</span>
            <input type="text" id="searchPenyakitInput" onkeyup="filterPenyakit()"
                placeholder="Cari nama penyakit, gejala umum, atau penanganan..."
                class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-xs font-medium text-slate-700 bg-slate-50 focus:bg-white focus:outline-none focus:border-emerald-500 transition">
        </div>

        <div class="flex items-center gap-2 bg-slate-100 p-1 rounded-xl">
            <button type="button" id="btnPenyakitCard" onclick="switchPenyakitView('card')"
                class="px-3.5 py-1.5 rounded-lg text-xs font-bold bg-white text-emerald-800 shadow-sm transition cursor-pointer">
                🗂️ Katalog Kartu
            </button>
            <button type="button" id="btnPenyakitTable" onclick="switchPenyakitView('table')"
                class="px-3.5 py-1.5 rounded-lg text-xs font-bold text-slate-500 hover:text-slate-800 transition cursor-pointer">
                📋 Tabel Ringkas
            </button>
        </div>
    </section>

    <!-- 4. KONTEN UTAMA (EMPTY STATE / KATALOG KARTU / TABEL) -->
    @if($listPenyakit->isEmpty())
        <section class="bg-white rounded-3xl p-12 border border-slate-200/80 shadow-sm text-center">
            <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-3xl mx-auto mb-3">🦠</div>
            <h3 class="text-base font-extrabold text-slate-800">Belum Ada Data Penyakit</h3>
            <p class="text-xs text-slate-500 max-w-md mx-auto mt-1 mb-5">
                Tambahkan referensi penyakit kelapa sawit (seperti Busuk Pangkal Batang Ganoderma, Busuk Pucuk, atau Tajuk) beserta gejala dan penanganannya.
            </p>
            <button type="button" onclick="openPenyakitModal('create')"
                class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm transition cursor-pointer">
                ＋ Tambah Penyakit Pertama
            </button>
        </section>
    @else
        <!-- MODE A: KATALOG KARTU REFERENSI PENYAKIT -->
        <section id="penyakitCardView" class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @foreach($listPenyakit as $index => $item)
                @php
                    $searchText = strtolower(($item->nama_penyakit ?? '') . ' ' . ($item->gejala ?? '') . ' ' . ($item->penanganan ?? ''));
                @endphp
                <div class="penyakit-item bg-white rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-md hover:border-emerald-300 transition overflow-hidden flex flex-col justify-between"
                     data-search="{{ $searchText }}">
                    <div>
                        <!-- Header Kartu -->
                        <div class="px-5 py-4 bg-gradient-to-r from-emerald-50/70 to-white border-b border-slate-100 flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-11 h-11 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl shrink-0 border border-rose-200/60">
                                    🦠
                                </div>
                                <div class="min-w-0">
                                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-700">Penyakit #{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    <h3 class="text-base font-extrabold text-slate-900 truncate">{{ $item->nama_penyakit }}</h3>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-200 text-[11px] font-bold shrink-0">
                                🌴 Kelapa Sawit
                            </span>
                        </div>

                        <!-- Dua Kotak Visual: Gejala Umum & Informasi Penanganan -->
                        <div class="p-5 space-y-3">
                            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70">
                                <p class="text-[10px] font-extrabold uppercase tracking-wider text-rose-700 mb-1 flex items-center gap-1.5">
                                    <span>🔬</span> Gejala Umum
                                </p>
                                <p class="text-xs text-slate-700 leading-relaxed">{{ $item->gejala }}</p>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-emerald-50/60 border border-emerald-200/70">
                                <p class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-800 mb-1 flex items-center gap-1.5">
                                    <span>🛡️</span> Informasi Penanganan (SOP)
                                </p>
                                <p class="text-xs text-slate-700 leading-relaxed">{{ $item->penanganan }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Aksi -->
                    <div class="px-5 py-3 bg-slate-50/80 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[11px] text-slate-400 font-medium">ID: #PNY-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</span>
                        <div class="flex items-center gap-2">
                            <button type="button"
                                onclick='openPenyakitModal("edit", @json($item))'
                                class="px-3 py-1.5 rounded-xl bg-white hover:bg-amber-50 text-amber-700 border border-slate-200 hover:border-amber-200 text-xs font-bold transition cursor-pointer">
                                ✏️ Edit
                            </button>
                            <form action="{{ route('penyakit.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data penyakit {{ $item->nama_penyakit }}?')">
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
        <section id="penyakitTableView" class="hidden bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                            <th class="py-3.5 px-5">No</th>
                            <th class="py-3.5 px-5">Nama Penyakit</th>
                            <th class="py-3.5 px-5">Gejala Umum</th>
                            <th class="py-3.5 px-5">Informasi Penanganan</th>
                            <th class="py-3.5 px-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @foreach($listPenyakit as $index => $item)
                            @php
                                $searchText = strtolower(($item->nama_penyakit ?? '') . ' ' . ($item->gejala ?? '') . ' ' . ($item->penanganan ?? ''));
                            @endphp
                            <tr class="penyakit-row hover:bg-emerald-50/40 transition" data-search="{{ $searchText }}">
                                <td class="py-3.5 px-5 font-bold text-slate-400">{{ $index + 1 }}</td>
                                <td class="py-3.5 px-5 font-extrabold text-slate-800">{{ $item->nama_penyakit }}</td>
                                <td class="py-3.5 px-5 text-slate-600 max-w-sm">{{ $item->gejala }}</td>
                                <td class="py-3.5 px-5 text-slate-600 max-w-sm">{{ $item->penanganan }}</td>
                                <td class="py-3.5 px-5 text-right">
                                    <div class="inline-flex items-center gap-1.5">
                                        <button type="button" onclick='openPenyakitModal("edit", @json($item))'
                                            class="px-3 py-1.5 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-700 font-bold transition cursor-pointer">
                                            Edit
                                        </button>
                                        <form action="{{ route('penyakit.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data penyakit ini?')">
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

<!-- MODAL TAMBAH / EDIT DATA PENYAKIT -->
<div id="penyakitModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4">
    <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-[#064e3b] to-[#047857] text-white flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <span class="text-xl">🦠</span>
                <h3 id="penyakitModalTitle" style="color: #ffffff !important;" class="text-base font-extrabold">Tambah Data Penyakit Baru</h3>
            </div>
            <button type="button" onclick="closePenyakitModal()" class="w-8 h-8 rounded-full bg-white/15 hover:bg-white/25 text-white flex items-center justify-center text-sm cursor-pointer">✕</button>
        </div>

        <form id="penyakitForm" method="POST" action="{{ route('penyakit.store') }}" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="_method" id="penyakitFormMethod" value="POST">

            <div>
                <label class="block text-xs font-extrabold text-slate-700 mb-1.5">Nama Penyakit <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_penyakit" id="inputNamaPenyakit" required placeholder="Contoh: Busuk Pangkal Batang (Ganoderma boninense)"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-extrabold text-slate-700 mb-1.5">Gejala Umum <span class="text-rose-500">*</span></label>
                <textarea name="gejala" id="inputGejalaPenyakit" rows="3" required placeholder="Deskripsi gejala pada pelepah/batang kelapa sawit..."
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-emerald-500"></textarea>
            </div>

            <div>
                <label class="block text-xs font-extrabold text-slate-700 mb-1.5">Informasi Penanganan <span class="text-rose-500">*</span></label>
                <textarea name="penanganan" id="inputPenangananPenyakit" rows="3" required placeholder="Langkah penanganan sesuai referensi pertanian..."
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-emerald-500"></textarea>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closePenyakitModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm transition cursor-pointer">
                    💾 Simpan Data Penyakit
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openPenyakitModal(mode, data = null) {
        const modal = document.getElementById('penyakitModal');
        const form = document.getElementById('penyakitForm');
        const title = document.getElementById('penyakitModalTitle');
        const method = document.getElementById('penyakitFormMethod');

        if (mode === 'edit' && data) {
            title.textContent = 'Edit Data Penyakit';
            form.action = `/penyakit/${data.id}`;
            method.value = 'PUT';
            document.getElementById('inputNamaPenyakit').value = data.nama_penyakit || '';
            document.getElementById('inputGejalaPenyakit').value = data.gejala || '';
            document.getElementById('inputPenangananPenyakit').value = data.penanganan || '';
        } else {
            title.textContent = 'Tambah Data Penyakit Baru';
            form.action = "{{ route('penyakit.store') }}";
            method.value = 'POST';
            form.reset();
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closePenyakitModal() {
        const modal = document.getElementById('penyakitModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function switchPenyakitView(view) {
        const cardView = document.getElementById('penyakitCardView');
        const tableView = document.getElementById('penyakitTableView');
        const btnCard = document.getElementById('btnPenyakitCard');
        const btnTable = document.getElementById('btnPenyakitTable');
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

    function filterPenyakit() {
        const keyword = document.getElementById('searchPenyakitInput').value.toLowerCase();
        document.querySelectorAll('.penyakit-item, .penyakit-row').forEach(el => {
            const text = el.getAttribute('data-search') || '';
            el.style.display = text.includes(keyword) ? '' : 'none';
        });
    }
</script>
@endsection