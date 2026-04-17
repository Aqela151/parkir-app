@extends('layouts.app')

@section('title', 'Transaksi Parkir')
@section('page-title', 'Transaksi Parkir')

@section('sidebar')
    @include('components.sidebar.petugas')
@endsection

@section('content')

<style>
    .top-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
    }

    .petugas-panel-badge {
        border: 1.5px solid #4a9eff;
        color: #4a9eff;
        border-radius: 20px;
        padding: 4px 16px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        background: rgba(74,158,255,0.06);
    }

    .greeting h1 {
        font-size: 36px;
        font-weight: 900;
        color: #1a1a1a;
        letter-spacing: -1px;
        margin-bottom: 6px;
    }

    .greeting p {
        font-size: 13px;
        color: #aaa;
        margin-bottom: 28px;
    }

    /* ===== TWO COL LAYOUT ===== */
    .top-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 36px;
    }

    /* ===== FORM CARD ===== */
    .form-card, .search-card {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .form-group {
        margin-bottom: 14px;
    }

    .form-label {
        font-size: 11px;
        font-weight: 700;
        color: #aaa;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
        display: block;
    }

    .form-select, .form-input {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid #e8e4dc;
        border-radius: 10px;
        font-size: 13px;
        color: #1a1a1a;
        background: #fff;
        appearance: none;
        -webkit-appearance: none;
        outline: none;
        transition: border-color 0.2s;
        box-sizing: border-box;
        font-family: inherit;
    }

    .form-select:focus, .form-input:focus {
        border-color: #F8C61E;
    }

    .select-wrapper {
        position: relative;
    }

    .select-wrapper::after {
        content: '';
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        width: 0;
        height: 0;
        border-left: 5px solid transparent;
        border-right: 5px solid transparent;
        border-top: 5px solid #aaa;
        pointer-events: none;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 14px;
    }

    .btn-catat {
        width: 100%;
        padding: 13px;
        background: #F8C61E;
        color: #1a1a1a;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 800;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: background 0.2s, transform 0.1s;
        margin-top: 4px;
    }

    .btn-catat:hover { background: #e6b418; }
    .btn-catat:active { transform: scale(0.98); }

    /* ===== SEARCH CARD ===== */
    .search-card-label {
        font-size: 13px;
        color: #bbb;
        margin-bottom: 16px;
    }

    .search-input-wrapper {
        position: relative;
    }

    .search-input-wrapper svg {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #bbb;
    }

    .search-input {
        width: 100%;
        padding: 10px 14px 10px 38px;
        border: 1.5px solid #e8e4dc;
        border-radius: 10px;
        font-size: 13px;
        color: #1a1a1a;
        outline: none;
        transition: border-color 0.2s;
        box-sizing: border-box;
        font-family: inherit;
    }

    .search-input:focus { border-color: #F8C61E; }

    /* search result box */
    .search-result-box {
        margin-top: 12px;
        display: none;
    }

    .search-result-item {
        padding: 10px 12px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 13px;
        color: #444;
        transition: background 0.15s;
    }

    .search-result-item:hover { background: #f5f3ef; }

    .search-result-item .plat { font-weight: 700; color: #1a1a1a; }
    .search-result-item .detail { font-size: 11px; color: #aaa; margin-top: 2px; }

    /* ===== BOTTOM TABLE ===== */
    .section-title {
        font-size: 18px;
        font-weight: 800;
        color: #1a1a1a;
        margin-bottom: 2px;
    }

    .section-sub {
        font-size: 12px;
        color: #aaa;
        margin-bottom: 16px;
    }

    .table-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .filter-row {
        display: flex;
        gap: 12px;
        padding: 16px 20px;
        border-bottom: 1px solid #f0ede8;
        align-items: center;
    }

    .filter-search-wrapper {
        position: relative;
        flex: 1;
        max-width: 300px;
    }

    .filter-search-wrapper svg {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #bbb;
    }

    .filter-search {
        width: 100%;
        padding: 9px 14px 9px 36px;
        border: 1.5px solid #e8e4dc;
        border-radius: 50px;
        font-size: 13px;
        color: #1a1a1a;
        outline: none;
        transition: border-color 0.2s;
        box-sizing: border-box;
        font-family: inherit;
        background: #fff;
    }

    .filter-search:focus { border-color: #F8C61E; }

    .filter-sort-wrapper {
        position: relative;
    }

    .filter-sort-wrapper::after {
        content: '';
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        width: 0;
        height: 0;
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        border-top: 4px solid #aaa;
        pointer-events: none;
    }

    .filter-sort {
        padding: 9px 34px 9px 14px;
        border: 1.5px solid #e8e4dc;
        border-radius: 50px;
        font-size: 13px;
        color: #888;
        outline: none;
        appearance: none;
        -webkit-appearance: none;
        background: #fff;
        font-family: inherit;
        cursor: pointer;
        transition: border-color 0.2s;
    }

    .filter-sort:focus { border-color: #F8C61E; }

    .parkir-table {
        width: 100%;
        border-collapse: collapse;
    }

    .parkir-table thead tr {
        background: #1C1C1E;
    }

    .parkir-table thead th {
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        padding: 14px 20px;
        text-align: left;
    }

    .parkir-table tbody tr {
        border-bottom: 1px solid #f5f3ef;
        transition: background 0.15s;
    }

    .parkir-table tbody tr:last-child { border-bottom: none; }
    .parkir-table tbody tr:hover { background: #fdfcf9; }

    .parkir-table tbody td {
        padding: 15px 20px;
        font-size: 13px;
        color: #444;
        vertical-align: middle;
    }

    .plat-text { font-weight: 600; color: #1a1a1a; letter-spacing: 0.3px; }

    .jenis-text.motor { color: #4a9eff; font-weight: 600; }
    .jenis-text.mobil { color: #F8C61E; font-weight: 600; }
    .jenis-text.bus   { color: #4caf7d; font-weight: 600; }

    .tarif-text { color: #F8C61E; font-weight: 700; }

    .aksi-btn {
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        border: none;
        background: none;
        padding: 0;
        margin-right: 12px;
    }
    .aksi-btn.keluar { color: #e05a5a; }
    .aksi-btn.struk  { color: #4a9eff; }

    .empty-state {
        text-align: center;
        color: #bbb;
        padding: 40px 20px;
        font-size: 13px;
    }

    @media (max-width: 900px) {
        .top-grid { grid-template-columns: 1fr; }
    }
</style>

{{-- TOP HEADER --}}
<div class="top-header">
    <span></span>
    <span class="petugas-panel-badge">PETUGAS PANEL</span>
</div>

{{-- GREETING --}}
<div class="greeting">
    <h1>Transaksi Parkir</h1>
    <p>Catat kendaraan masuk dan keluar</p>
</div>

@if(session('success'))
    <div style="background:#d4edda; color:#155724; padding:12px 16px; border-radius:10px; font-size:13px; font-weight:600; margin-bottom:20px;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="background:#f8d7da; color:#721c24; padding:12px 16px; border-radius:10px; font-size:13px; font-weight:600; margin-bottom:20px;">
        {{ session('error') }}
    </div>
@endif

{{-- TOP: FORM + SEARCH --}}
<div class="top-grid">

    {{-- FORM CATAT MASUK --}}
    <div class="form-card">
        <form method="POST" action="{{ route('petugas.transaksi.store') }}">
            @csrf

            {{-- Dropdown Kendaraan --}}
            <div class="form-group">
                <label class="form-label">Plat Nomor Kendaraan</label>
                <div class="select-wrapper">
                    <select name="kendaraan_id" class="form-select" required>
                        <option value="" disabled selected>-- Pilih Kendaraan --</option>
                        @foreach ($kendaraanList as $k)
                            <option value="{{ $k->id }}" {{ old('kendaraan_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->plat_nomor }} — {{ ucfirst($k->jenis) }}
                        @endforeach
                    </select>
                </div>
                @error('kendaraan_id')
                    <div style="color:#e05a5a; font-size:11px; margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-row">
                {{-- Dropdown Area --}}
                <div>
                    <label class="form-label">Area Parkir</label>
                    <div class="select-wrapper">
                        <select name="area_id" class="form-select" required>
                            <option value="" disabled selected>-- Pilih Area --</option>
                            @foreach ($areaList as $area)
                                <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>
                                    {{ $area->nama_area }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('area_id')
                        <div style="color:#e05a5a; font-size:11px; margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Waktu Masuk --}}
                <div>
                    <label class="form-label">Waktu Masuk</label>
                    <input
                        type="time"
                        name="waktu_masuk"
                        class="form-input"
                        value="{{ old('waktu_masuk', now()->format('H:i')) }}"
                        required
                    >
                    @error('waktu_masuk')
                        <div style="color:#e05a5a; font-size:11px; margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn-catat">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                </svg>
                Catat Masuk
            </button>
        </form>
    </div>

    {{-- SEARCH KENDARAAN --}}
    <div class="search-card">
        <div class="search-card-label">Cari Plat Nomor</div>
        <div class="search-input-wrapper">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input
                type="text"
                id="searchPlat"
                class="search-input"
                placeholder="Ketik plat nomor..."
                autocomplete="off"
            >
        </div>
        <div class="search-result-box" id="searchResultBox">
            {{-- hasil muncul via JS --}}
        </div>
    </div>

</div>

{{-- KENDARAAN SEDANG PARKIR --}}
<div class="section-title">Kendaraan Sedang Parkir</div>
<div class="section-sub">Data real-time</div>

<div class="table-card">
    <div class="filter-row">
        <div class="filter-search-wrapper">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text" id="filterPlat" class="filter-search" placeholder="Cari plat...">
        </div>
        <div class="filter-sort-wrapper">
            <select id="filterSort" class="filter-sort">
                <option value="default">Urutkan: Default</option>
                <option value="masuk_asc">Masuk: Terlama</option>
                <option value="masuk_desc">Masuk: Terbaru</option>
                <option value="plat_asc">Plat: A–Z</option>
            </select>
        </div>
    </div>

    <table class="parkir-table" id="parkirTable">
        <thead>
            <tr>
                <th>Plat Nomor</th>
                <th>Jenis</th>
                <th>Masuk</th>
                <th>Area</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody id="parkirTableBody">
            @forelse ($kendaraanParkir as $t)
                <tr
                    data-plat="{{ strtolower($t->kendaraan->no_plat ?? '') }}"
                    data-masuk="{{ $t->waktu_masuk }}"
                >
                    <td><span class="plat-text">{{ $t->kendaraan->plat_nomor ?? '-' }}</span></td>
                    <td>
                        <span class="jenis-text {{ strtolower($t->kendaraan->jenis ?? 'motor') }}">
                            {{ ucfirst($t->kendaraan->jenis ?? '-') }}
                        </span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($t->waktu_masuk)->format('H:i') }}</td>
                    <td>{{ $t->area->nama_area ?? '-' }}</td>
                    <td style="color:#888; font-size:12px;">
                        {{ \Carbon\Carbon::parse($t->waktu_masuk)->diffForHumans(null, true) }}
                    </td>
                    <td><span class="tarif-text">Rp {{ number_format($t->tarif_sementara ?? 0, 0, ',', '.') }}</span></td>
                    <td>
                        <a href="{{ route('petugas.transaksi.keluar', $t->id) }}" class="aksi-btn keluar">Keluar</a>
                        <a href="{{ route('petugas.transaksi.struk', $t->id) }}" class="aksi-btn struk">Struk</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="empty-state">Tidak ada kendaraan yang sedang parkir.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- SCRIPT: search plat + filter tabel --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ===== SEARCH KENDARAAN (card kanan) =====
    const kendaraanData = @json($kendaraanList->map(function($k) {
        return [
            'id' => $k->id,
            'plat' => $k->plat_nomor,
            'jenis' => ucfirst($k->jenis)
        ];
    }));

    const searchInput = document.getElementById('searchPlat');
    const resultBox = document.getElementById('searchResultBox');
    const selectKendaraan = document.querySelector('select[name="kendaraan_id"]');

    searchInput.addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        resultBox.innerHTML = '';
        resultBox.style.display = 'none';
        if (q.length < 1) return;

        const matches = kendaraanData.filter(k => k.plat.toLowerCase().includes(q));
        if (matches.length === 0) {
            resultBox.innerHTML = '<div class="search-result-item" style="color:#bbb;">Tidak ditemukan.</div>';
            resultBox.style.display = 'block';
            return;
        }

        matches.forEach(function (k) {
            const el = document.createElement('div');
            el.className = 'search-result-item';
            el.innerHTML = '<div class="plat">' + k.plat + '</div><div class="detail">' + k.jenis + '</div>';
            el.addEventListener('click', function () {
                // set dropdown di form
                selectKendaraan.value = k.id;
                searchInput.value = k.plat;
                resultBox.style.display = 'none';
            });
            resultBox.appendChild(el);
        });
        resultBox.style.display = 'block';
    });

    document.addEventListener('click', function (e) {
        if (!searchInput.contains(e.target) && !resultBox.contains(e.target)) {
            resultBox.style.display = 'none';
        }
    });

    // ===== FILTER TABEL BAWAH =====
    const filterPlat = document.getElementById('filterPlat');
    const filterSort = document.getElementById('filterSort');
    const tbody      = document.getElementById('parkirTableBody');

    function applyFilter() {
        const q    = filterPlat.value.toLowerCase().trim();
        const sort = filterSort.value;
        let rows   = Array.from(tbody.querySelectorAll('tr[data-plat]'));

        // filter plat
        rows.forEach(function (r) {
            const plat = r.dataset.plat || '';
            r.style.display = plat.includes(q) ? '' : 'none';
        });

        // sort
        const visible = rows.filter(r => r.style.display !== 'none');
        visible.sort(function (a, b) {
            if (sort === 'masuk_asc')  return new Date(a.dataset.masuk) - new Date(b.dataset.masuk);
            if (sort === 'masuk_desc') return new Date(b.dataset.masuk) - new Date(a.dataset.masuk);
            if (sort === 'plat_asc')   return a.dataset.plat.localeCompare(b.dataset.plat);
            return 0;
        });
        visible.forEach(r => tbody.appendChild(r));
    }

    filterPlat.addEventListener('input', applyFilter);
    filterSort.addEventListener('change', applyFilter);
});
</script>

@endsection