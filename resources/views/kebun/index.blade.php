@extends('layouts.app')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    .simhasa-page { font-family: 'Plus Jakarta Sans', sans-serif; }
    .simhasa-page a { text-decoration: none !important; }
</style>

@php
    $listKebun = $kebuns ?? ($kebun ?? collect());
    $totalBlok = $listKebun->count();
    $totalLuas = $listKebun->sum(fn($item) => (float) preg_replace('/[^0-9.]/', '', $item->luas_lahan ?? 0));
    $rataLuas  = $totalBlok > 0 ? round($totalLuas / $totalBlok, 1) : 0;
@endphp

<div class="simhasa-page space-y-6 pb-8">

    <!-- 1. HERO HEADER AGRI-TECH -->
    <section class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#064e3b] via-[#047857] to-[#10b981] text-white p-7 lg:p-8 shadow-lg shadow-emerald-900/10">
        <div class="absolute -right-12 -top-12 w-56 h-56 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-5">
            <div class="space-y-1.5">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 border border-white/20 text-xs font-semibold text-emerald-50">
                    <span>🌴 Master Data Perkebunan</span>
                </div>
                <h1 class="text-2xl lg:text-3xl font-extrabold tracking-tight text-white">Kelola Data Kebun</h1>
                <p class="text-sm text-emerald-50/90 max-w-xl">
                    Kelola informasi blok perkebunan kelapa sawit, kepemilikan lahan, lokasi wilayah, dan luasan area sebagai dasar pencatatan monitoring lapangan.
                </p>
            </div>

            <button type="button" onclick="openKebunModal('create')"
                class="px-5 py-3 rounded-xl bg-white text-emerald-900 font-bold text-sm shadow-md hover:bg-emerald-50 transition flex items-center gap-2 shrink-0 cursor-pointer">
                <span class="text-base">＋</span> Tambah Kebun
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

    <!-- 2. MINI KPI STATISTIK KEBUN -->
    <section class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 border-l-4 border-l-emerald-500 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Blok Kebun</p>
                <p class="text-2xl font-extrabold text-slate-800 mt-1">{{ $totalBlok }} <span class="text-xs font-semibold text-slate-400">Blok</span></p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">🌴</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 border-l-4 border-l-teal-500 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Luas Lahan</p>
                <p class="text-2xl font-extrabold text-slate-800 mt-1">{{ number_format($totalLuas, 1) }} <span class="text-xs font-semibold text-slate-400">Hektare</span></p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl">📐</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 border-l-4 border-l-sky-500 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Rata-rata Luas Blok</p>
                <p class="text-2xl font-extrabold text-slate-800 mt-1">{{ $rataLuas }} <span class="text-xs font-semibold text-slate-400">Ha / Blok</span></p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-xl">📊</div>
        </div>
    </section>

    <!-- 3. TOOLBAR PENCARIAN & SWITCHER TAMPILAN -->
    <section class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-sm flex flex-wrap items-center justify-between gap-3">
        <div class="relative flex-1 min-w-[240px] max-w-md">
            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm">🔍</span>
            <input type="text" id="searchKebunInput" onkeyup="filterKebun()"
                placeholder="Cari nama kebun, pemilik, atau lokasi..."
                class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-xs font-medium text-slate-700 bg-slate-50 focus:bg-white focus:outline-none focus:border-emerald-500 transition">
        </div>

        <div class="flex items-center gap-2 bg-slate-100 p-1 rounded-xl">
            <button type="button" id="btnViewCard" onclick="switchKebunView('card')"
                class="px-3.5 py-1.5 rounded-lg text-xs font-bold bg-white text-emerald-800 shadow-sm transition cursor-pointer">
                🗂️ Kartu Blok
            </button>
            <button type="button" id="btnViewTable" onclick="switchKebunView('table')"
                class="px-3.5 py-1.5 rounded-lg text-xs font-bold text-slate-500 hover:text-slate-800 transition cursor-pointer">
                📋 Tabel Ringkas
            </button>
        </div>
    </section>

    <!-- 4. KONTEN UTAMA (EMPTY STATE / GRID KARTU / TABEL) -->
    @if($listKebun->isEmpty())
        <section class="bg-white rounded-3xl p-12 border border-slate-200/80 shadow-sm text-center">
            <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-3xl mx-auto mb-3">🌱</div>
            <h3 class="text-base font-extrabold text-slate-800">Belum Ada Data Kebun</h3>
            <p class="text-xs text-slate-500 max-w-md mx-auto mt-1 mb-5">
                Mulai tambahkan data blok kebun kelapa sawit pertama kamu agar dapat dipilih saat pencatatan monitoring lapangan.
            </p>
            <button type="button" onclick="openKebunModal('create')"
                class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm transition cursor-pointer">
                ＋ Tambah Kebun Pertama
            </button>
        </section>
    @else
        <!-- MODE A: GRID KARTU BLOK -->
        <section id="kebunCardView" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            @foreach($listKebun as $index => $item)
                @php
                    $searchText = strtolower(($item->nama_kebun ?? '') . ' ' . ($item->pemilik ?? '') . ' ' . ($item->lokasi ?? ''));
                @endphp
                <div class="kebun-item bg-white rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-md hover:border-emerald-300 transition overflow-hidden flex flex-col justify-between"
                     data-search="{{ $searchText }}">
                    <div>
                        <!-- Top Strip -->
                        <div class="px-5 py-4 bg-gradient-to-r from-emerald-50/80 to-white border-b border-slate-100 flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-11 h-11 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl shrink-0 border border-emerald-200/60">
                                    🌴
                                </div>
                                <div class="min-w-0">
                                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-700">Blok #{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    <h3 class="text-base font-extrabold text-slate-900 truncate">{{ $item->nama_kebun }}</h3>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded-full bg-emerald-600 text-white text-[11px] font-bold shrink-0">
                                {{ $item->luas_lahan }} Ha
                            </span>
                        </div>

                        <!-- Detail Info -->
                        <div class="p-5 space-y-3">
                            <div class="flex items-center gap-2.5 text-xs">
                                <span class="w-7 h-7 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">👤</span>
                                <div class="min-w-0">
                                    <p class="text-[10px] font-bold uppercase text-slate-400">Nama Pemilik / Pengelola</p>
                                    <p class="font-bold text-slate-800 truncate">{{ $item->pemilik }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2.5 text-xs">
                                <span class="w-7 h-7 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">📍</span>
                                <div class="min-w-0">
                                    <p class="text-[10px] font-bold uppercase text-slate-400">Lokasi Kebun</p>
                                    <p class="font-semibold text-slate-700 truncate">{{ $item->lokasi }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Aksi -->
                    <div class="px-5 py-3 bg-slate-50/80 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[11px] text-slate-400 font-medium">ID: #KBN-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</span>
                        <div class="flex items-center gap-2">
                            <button type="button"
                                onclick='openKebunModal("edit", @json($item))'
                                class="px-3 py-1.5 rounded-xl bg-white hover:bg-amber-50 text-amber-700 border border-slate-200 hover:border-amber-200 text-xs font-bold transition cursor-pointer">
                                ✏️ Edit
                            </button>
                            <form action="{{ route('kebun.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data kebun {{ $item->nama_kebun }}?')">
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
        <section id="kebunTableView" class="hidden bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                            <th class="py-3.5 px-5">No</th>
                            <th class="py-3.5 px-5">Nama Kebun</th>
                            <th class="py-3.5 px-5">Nama Pemilik</th>
                            <th class="py-3.5 px-5">Lokasi</th>
                            <th class="py-3.5 px-5">Luas Lahan</th>
                            <th class="py-3.5 px-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @foreach($listKebun as $index => $item)
                            @php
                                $searchText = strtolower(($item->nama_kebun ?? '') . ' ' . ($item->pemilik ?? '') . ' ' . ($item->lokasi ?? ''));
                            @endphp
                            <tr class="kebun-row hover:bg-emerald-50/40 transition" data-search="{{ $searchText }}">
                                <td class="py-3.5 px-5 font-bold text-slate-400">{{ $index + 1 }}</td>
                                <td class="py-3.5 px-5 font-extrabold text-slate-800">{{ $item->nama_kebun }}</td>
                                <td class="py-3.5 px-5 font-semibold text-slate-700">{{ $item->pemilik }}</td>
                                <td class="py-3.5 px-5 text-slate-600">{{ $item->lokasi }}</td>
                                <td class="py-3.5 px-5">
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold">
                                        {{ $item->luas_lahan }} Ha
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 text-right">
                                    <div class="inline-flex items-center gap-1.5">
                                        <button type="button" onclick='openKebunModal("edit", @json($item))'
                                            class="px-3 py-1.5 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-700 font-bold transition cursor-pointer">
                                            Edit
                                        </button>
                                        <form action="{{ route('kebun.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data kebun ini?')">
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

<!-- MODAL TAMBAH / EDIT DATA KEBUN -->
<div id="kebunModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4">
    <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-[#064e3b] to-[#047857] text-white flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <span class="text-xl">🌴</span>
                <h3 id="kebunModalTitle" class="text-base font-extrabold">Tambah Data Kebun</h3>
            </div>
            <button type="button" onclick="closeKebunModal()" class="w-8 h-8 rounded-full bg-white/15 hover:bg-white/25 text-white flex items-center justify-center text-sm cursor-pointer">✕</button>
        </div>

        <form id="kebunForm" method="POST" action="{{ route('kebun.store') }}" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="_method" id="kebunFormMethod" value="POST">

            <div>
                <label class="block text-xs font-extrabold text-slate-700 mb-1.5">Nama Kebun / Blok <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_kebun" id="inputNamaKebun" required placeholder="Contoh: Kebun Inti Kampar - Blok A1"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-extrabold text-slate-700 mb-1.5">Nama Pemilik / Pengelola <span class="text-rose-500">*</span></label>
                <input type="text" name="pemilik" id="inputNamaPemilik" required placeholder="Contoh: PT Sawit Makmur / Bpk. Hendra"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-extrabold text-slate-700 mb-1.5">Lokasi Kebun <span class="text-rose-500">*</span></label>
                <input type="text" name="lokasi" id="inputLokasi" required placeholder="Contoh: Kec. Tapung, Kab. Kampar, Riau"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-extrabold text-slate-700 mb-1.5">Luas Lahan (Hektare) <span class="text-rose-500">*</span></label>
                <input type="text" name="luas_lahan" id="inputLuasLahan" required placeholder="Contoh: 25.5"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:outline-none focus:border-emerald-500">
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeKebunModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm transition cursor-pointer">
                    💾 Simpan Data Kebun
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openKebunModal(mode, data = null) {
        const modal = document.getElementById('kebunModal');
        const form = document.getElementById('kebunForm');
        const title = document.getElementById('kebunModalTitle');
        const method = document.getElementById('kebunFormMethod');

        if (mode === 'edit' && data) {
            title.textContent = 'Edit Data Kebun';
            form.action = `/kebun/${data.id}`;
            method.value = 'PUT';
            document.getElementById('inputNamaKebun').value = data.nama_kebun || '';
            document.getElementById('inputNamaPemilik').value = data.pemilik || '';
            document.getElementById('inputLokasi').value = data.lokasi || '';
            document.getElementById('inputLuasLahan').value = data.luas_lahan || '';
        } else {
            title.textContent = 'Tambah Data Kebun';
            form.action = "{{ route('kebun.store') }}";
            method.value = 'POST';
            form.reset();
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeKebunModal() {
        const modal = document.getElementById('kebunModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function switchKebunView(view) {
        const cardView = document.getElementById('kebunCardView');
        const tableView = document.getElementById('kebunTableView');
        const btnCard = document.getElementById('btnViewCard');
        const btnTable = document.getElementById('btnViewTable');
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

    function filterKebun() {
        const keyword = document.getElementById('searchKebunInput').value.toLowerCase();
        document.querySelectorAll('.kebun-item, .kebun-row').forEach(el => {
            const text = el.getAttribute('data-search') || '';
            el.style.display = text.includes(keyword) ? '' : 'none';
        });
    }
</script>
@endsection