@extends('layouts.app')

@section('title', 'Data Hama Kelapa Sawit')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    .simhasa-page { font-family: 'Plus Jakarta Sans', sans-serif; }
    .simhasa-page a { text-decoration: none !important; }
</style>

@php
    $listHama = $hama ?? ($hamas ?? collect());
    $totalHama = $listHama->count();
    $totalKomoditas = $listHama->pluck('jenis_tanaman')->filter()->unique()->count();
@endphp

<div class="simhasa-page space-y-6 pb-8">

    <!-- 1. HERO HEADER HIJAU ZAMRUD SERAGAM -->
    <section class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#064e3b] via-[#047857] to-[#10b981] text-white p-7 lg:p-8 shadow-lg shadow-emerald-900/10">
        <div class="absolute -right-12 -top-12 w-56 h-56 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-5">
            <div class="space-y-1.5">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 border border-white/20 text-xs font-semibold text-emerald-50">
                    <span>🪲 Master Referensi Hama</span>
                </div>
                <h1 class="text-2xl lg:text-3xl font-extrabold tracking-tight text-white">Kelola Data Hama</h1>
                <p class="text-sm text-emerald-50/90 max-w-xl">
                    Katalog identifikasi hama utama kelapa sawit, gejala serangan pada pelepah atau tajuk, serta rekomendasi SOP pengendalian lapangan.
                </p>
            </div>

            <button type="button" onclick="openHamaModal('create')"
                class="px-5 py-3 rounded-xl bg-white text-emerald-900 font-bold text-sm shadow-md hover:bg-emerald-50 transition flex items-center gap-2 shrink-0 cursor-pointer">
                <span class="text-base">＋</span> Tambah Hama
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

    <!-- 2. MINI KPI STATISTIK HAMA -->
    <section class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 border-l-4 border-l-amber-500 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Spesies Hama</p>
                <p class="text-2xl font-extrabold text-slate-800 mt-1">{{ $totalHama }} <span class="text-xs font-semibold text-slate-400">Jenis</span></p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">🪲</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 border-l-4 border-l-emerald-500 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Panduan SOP Aktif</p>
                <p class="text-2xl font-extrabold text-slate-800 mt-1">{{ $totalHama }} <span class="text-xs font-semibold text-slate-400">Prosedur</span></p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">🛡️</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 border-l-4 border-l-teal-500 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Komoditas Terdaftar</p>
                <p class="text-2xl font-extrabold text-slate-800 mt-1">{{ $totalKomoditas ?: 1 }} <span class="text-xs font-semibold text-slate-400">Tanaman</span></p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl">🌴</div>
        </div>
    </section>

    <!-- 3. TOOLBAR PENCARIAN & SWITCHER TAMPILAN -->
    <section class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex flex-wrap items-center justify-between gap-3">
        <div class="relative flex-1 min-w-[240px] max-w-md">
            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm">🔍</span>
            <input type="text" id="searchHamaInput" onkeyup="filterHama()"
                placeholder="Cari nama hama, gejala serangan, atau penanganan..."
                class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-xs font-medium text-slate-700 bg-slate-50 focus:bg-white focus:outline-none focus:border-emerald-500 transition">
        </div>

        <div class="flex items-center gap-2 bg-slate-100 p-1 rounded-xl">
            <button type="button" id="btnHamaCard" onclick="switchHamaView('card')"
                class="px-3.5 py-1.5 rounded-lg text-xs font-bold bg-white text-emerald-800 shadow-sm transition cursor-pointer">
                🗂️ Katalog Kartu
            </button>
            <button type="button" id="btnHamaTable" onclick="switchHamaView('table')"
                class="px-3.5 py-1.5 rounded-lg text-xs font-bold text-slate-500 hover:text-slate-800 transition cursor-pointer">
                📋 Tabel Ringkas
            </button>
        </div>
    </section>

    <!-- 4. KONTEN UTAMA (EMPTY STATE / KATALOG KARTU / TABEL) -->
    @if($listHama->isEmpty())
        <section class="bg-white rounded-3xl p-12 border border-slate-200/80 shadow-sm text-center">
            <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-3xl mx-auto mb-3">🪲</div>
            <h3 class="text-base font-extrabold text-slate-800">Belum Ada Data Hama</h3>
            <p class="text-xs text-slate-500 max-w-md mx-auto mt-1 mb-5">
                Tambahkan referensi hama kelapa sawit (seperti Ulat Api, Kumbang Tanduk, atau Tikus Semak) beserta gejala dan cara penanganannya.
            </p>
            <button type="button" onclick="openHamaModal('create')"
                class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm transition cursor-pointer">
                ＋ Tambah Hama Pertama
            </button>
        </section>
    @else
        <!-- MODE A: KATALOG KARTU REFERENSI HAMA -->
        <section id="hamaCardView" class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @foreach($listHama as $index => $item)
                @php
                    $searchText = strtolower(($item->nama_hama ?? '') . ' ' . ($item->jenis_tanaman ?? '') . ' ' . ($item->gejala ?? '') . ' ' . ($item->penanganan ?? ''));
                @endphp
                <div class="hama-item bg-white rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-md hover:border-emerald-300 transition overflow-hidden flex flex-col justify-between"
                     data-search="{{ $searchText }}">
                    <div>
                        <!-- Header Kartu -->
                        <div class="px-5 py-4 bg-gradient-to-r from-emerald-50/70 to-white border-b border-slate-100 flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0 border border-amber-200/60">
                                    🪲
                                </div>
                                <div class="min-w-0">
                                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-700">Hama #{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    <h3 class="text-base font-extrabold text-slate-900 truncate">{{ $item->nama_hama }}</h3>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-bold shrink-0">
                                🌴 {{ $item->jenis_tanaman }}
                            </span>
                        </div>

                        <!-- Dua Kotak Visual: Gejala & Cara Penanganan -->
                        <div class="p-5 space-y-3">
                            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70">
                                <p class="text-[10px] font-extrabold uppercase tracking-wider text-amber-700 mb-1 flex items-center gap-1.5">
                                    <span>🔍</span> Gejala Serangan
                                </p>
                                <p class="text-xs text-slate-700 leading-relaxed">{{ $item->gejala }}</p>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-emerald-50/60 border border-emerald-200/70">
                                <p class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-800 mb-1 flex items-center gap-1.5">
                                    <span>🛡️</span> Cara Penanganan (SOP)
                                </p>
                                <p class="text-xs text-slate-700 leading-relaxed">{{ $item->penanganan }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Aksi -->
                    <div class="px-5 py-3 bg-slate-50/80 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[11px] text-slate-400 font-medium">ID: #HMA-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</span>
                        <div class="flex items-center gap-2">
                            <button type="button"
                                onclick='openHamaModal("edit", @json($item))'
                                class="px-3 py-1.5 rounded-xl bg-white hover:bg-amber-50 text-amber-700 border border-slate-200 hover:border-amber-200 text-xs font-bold transition cursor-pointer">
                                ✏️ Edit
                            </button>
                            <form action="{{ route('hama.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data hama {{ $item->nama_hama }}?')">
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
        <section id="hamaTableView" class="hidden bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                            <th class="py-3.5 px-5">No</th>
                            <th class="py-3.5 px-5">Nama Hama</th>
                            <th class="py-3.5 px-5">Jenis Tanaman</th>
                            <th class="py-3.5 px-5">Gejala Serangan</th>
                            <th class="py-3.5 px-5">Cara Penanganan</th>
                            <th class="py-3.5 px-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @foreach($listHama as $index => $item)
                            @php
                                $searchText = strtolower(($item->nama_hama ?? '') . ' ' . ($item->jenis_tanaman ?? '') . ' ' . ($item->gejala ?? '') . ' ' . ($item->penanganan ?? ''));
                            @endphp
                            <tr class="hama-row hover:bg-emerald-50/40 transition" data-search="{{ $searchText }}">
                                <td class="py-3.5 px-5 font-bold text-slate-400">{{ $index + 1 }}</td>
                                <td class="py-3.5 px-5 font-extrabold text-slate-800">{{ $item->nama_hama }}</td>
                                <td class="py-3.5 px-5">
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold">
                                        {{ $item->jenis_tanaman }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 text-slate-600 max-w-xs">{{ $item->gejala }}</td>
                                <td class="py-3.5 px-5 text-slate-600 max-w-xs">{{ $item->penanganan }}</td>
                                <td class="py-3.5 px-5 text-right">
                                    <div class="inline-flex items-center gap-1.5">
                                        <button type="button" onclick='openHamaModal("edit", @json($item))'
                                            class="px-3 py-1.5 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-700 font-bold transition cursor-pointer">
                                            Edit
                                        </button>
                                        <form action="{{ route('hama.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data hama ini?')">
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

<!-- MODAL TAMBAH / EDIT DATA HAMA -->
<div id="hamaModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4">
    <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-[#064e3b] to-[#047857] text-white flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <span class="text-xl">🪲</span>
                <h3 id="hamaModalTitle" class="text-base font-extrabold">Tambah Data Hama Baru</h3>
            </div>
            <button type="button" onclick="closeHamaModal()" class="w-8 h-8 rounded-full bg-white/15 hover:bg-white/25 text-white flex items-center justify-center text-sm cursor-pointer">✕</button>
        </div>

        <form id="hamaForm" method="POST" action="{{ route('hama.store') }}" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="_method" id="hamaFormMethod" value="POST">

            <div>
                <label class="block text-xs font-extrabold text-slate-700 mb-1.5">Nama Hama <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_hama" id="inputNamaHama" required placeholder="Contoh: Ulat Api (Setothosea asigna)"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-extrabold text-slate-700 mb-1.5">Jenis Tanaman <span class="text-rose-500">*</span></label>
                <input type="text" name="jenis_tanaman" id="inputJenisTanaman" value="Kelapa Sawit" required
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-extrabold text-slate-700 mb-1.5">Gejala Serangan <span class="text-rose-500">*</span></label>
                <textarea name="gejala" id="inputGejalaHama" rows="3" required placeholder="Deskripsi gejala pada daun/batang kelapa sawit..."
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-emerald-500"></textarea>
            </div>

            <div>
                <label class="block text-xs font-extrabold text-slate-700 mb-1.5">Cara Penanganan <span class="text-rose-500">*</span></label>
                <textarea name="penanganan" id="inputPenangananHama" rows="3" required placeholder="Langkah pengendalian sesuai referensi pertanian..."
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-emerald-500"></textarea>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeHamaModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm transition cursor-pointer">
                    💾 Simpan Data Hama
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openHamaModal(mode, data = null) {
        const modal = document.getElementById('hamaModal');
        const form = document.getElementById('hamaForm');
        const title = document.getElementById('hamaModalTitle');
        const method = document.getElementById('hamaFormMethod');

        if (mode === 'edit' && data) {
            title.textContent = 'Edit Data Hama';
            form.action = `/hama/${data.id}`;
            method.value = 'PUT';
            document.getElementById('inputNamaHama').value = data.nama_hama || '';
            document.getElementById('inputJenisTanaman').value = data.jenis_tanaman || 'Kelapa Sawit';
            document.getElementById('inputGejalaHama').value = data.gejala || '';
            document.getElementById('inputPenangananHama').value = data.penanganan || '';
        } else {
            title.textContent = 'Tambah Data Hama Baru';
            form.action = "{{ route('hama.store') }}";
            method.value = 'POST';
            form.reset();
            document.getElementById('inputJenisTanaman').value = 'Kelapa Sawit';
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeHamaModal() {
        const modal = document.getElementById('hamaModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function switchHamaView(view) {
        const cardView = document.getElementById('hamaCardView');
        const tableView = document.getElementById('hamaTableView');
        const btnCard = document.getElementById('btnHamaCard');
        const btnTable = document.getElementById('btnHamaTable');
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

    function filterHama() {
        const keyword = document.getElementById('searchHamaInput').value.toLowerCase();
        document.querySelectorAll('.hama-item, .hama-row').forEach(el => {
            const text = el.getAttribute('data-search') || '';
            el.style.display = text.includes(keyword) ? '' : 'none';
        });
    }
</script>
@endsection