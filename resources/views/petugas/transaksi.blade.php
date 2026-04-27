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
        font-size: 40px;
        font-weight: 900;
        color: #1a1a1a;
        letter-spacing: -1.5px;
        margin-bottom: 4px;
        line-height: 1.1;
    }

    .greeting p {
        font-size: 13px;
        color: #aaa;
        margin-bottom: 28px;
    }

    .top-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 36px;
        align-items: stretch;
    }

    .form-card, .search-card {
        background: #fff;
        border-radius: 20px;
        padding: 28px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        display: flex;
        flex-direction: column;
    }

    .card-title {
        font-size: 20px;
        font-weight: 800;
        color: #1a1a1a;
        margin-bottom: 18px;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-label {
        font-size: 12px;
        font-weight: 700;
        color: #666;
        letter-spacing: 0.6px;
        margin-bottom: 8px;
        display: block;
        text-transform: uppercase;
    }

    /* ── PLAT SEARCH INPUT ── */
    .plat-search-wrapper {
        position: relative;
    }

    .plat-search-input {
        width: 100%;
        padding: 14px 16px;
        border: 1.5px solid #e8e4dc;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 700;
        letter-spacing: 1px;
        color: #1a1a1a;
        outline: none;
        transition: border-color 0.2s;
        box-sizing: border-box;
        font-family: inherit;
        background: #fff;
        text-transform: uppercase;
    }

    .plat-search-input:focus { border-color: #F8C61E; }
    .plat-search-input::placeholder { font-weight: 400; letter-spacing: 0; text-transform: none; color: #bbb; }

    .plat-dropdown {
        position: absolute;
        top: calc(100% + 4px);
        left: 0; right: 0;
        background: #fff;
        border: 1.5px solid #e8e4dc;
        border-radius: 10px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.10);
        z-index: 100;
        max-height: 220px;
        overflow-y: auto;
        display: none;
    }

    .plat-dropdown::-webkit-scrollbar { width: 4px; }
    .plat-dropdown::-webkit-scrollbar-track { background: #f5f3ef; border-radius: 4px; }
    .plat-dropdown::-webkit-scrollbar-thumb { background: #ddd; border-radius: 4px; }

    .plat-dropdown-item {
        padding: 10px 14px;
        cursor: pointer;
        font-size: 13px;
        color: #444;
        border-bottom: 1px solid #f5f3ef;
        transition: background 0.15s;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .plat-dropdown-item:last-child { border-bottom: none; }
    .plat-dropdown-item:hover { background: #fffbea; }

    .plat-dropdown-item .dd-plat {
        font-weight: 700;
        color: #1a1a1a;
        font-size: 13px;
        letter-spacing: 0.5px;
    }

    .plat-dropdown-item .dd-detail {
        font-size: 11px;
        color: #aaa;
    }

    .plat-dropdown-no-result {
        padding: 14px;
        text-align: center;
        color: #bbb;
        font-size: 12px;
    }

    /* ── VEHICLE PREVIEW CARD ── */
    .kendaraan-preview {
        display: none;
        margin-top: 20px;
        background: #f8f7f2;
        border-radius: 18px;
        overflow: hidden;
        border: 1.5px solid #e8e4dc;
        animation: slideIn 0.25s ease;
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateY(-6px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .kendaraan-preview .preview-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px 0 20px;
    }

    .kendaraan-preview .preview-label {
        font-size: 12px;
        font-weight: 700;
        color: #666;
        letter-spacing: 1.2px;
        text-transform: uppercase;
    }

    .kendaraan-preview .preview-badge {
        font-size: 12px;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 20px;
        letter-spacing: 0.5px;
    }

    .preview-badge.motor  { background: rgba(74,158,255,0.15); color: #4a9eff; }
    .preview-badge.mobil  { background: rgba(248,198,30,0.15);  color: #F8C61E; }
    .preview-badge.bus    { background: rgba(76,175,125,0.15);  color: #4caf7d; }

    .kendaraan-preview .preview-body {
        display: flex;
        align-items: flex-start;
        gap: 32px;
        padding: 28px;
    }

    .preview-img-box {
        width: 300px;
        height: 280px;
        border-radius: 16px;
        overflow: hidden;
        background: #efefed;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1.5px solid #e0dcd2;
    }

    .preview-img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .preview-img-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        color: #999;
    }

    .preview-info {
        flex: 1;
    }

    .preview-plat {
        font-size: 48px;
        font-weight: 900;
        color: #1a1a1a;
        letter-spacing: 3px;
        line-height: 1;
        margin-bottom: 20px;
        font-family: 'Courier New', monospace;
    }

    .preview-rows {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px 32px;
    }

    .preview-row-item {
        display: flex;
        flex-direction: column;
    }

    .preview-row-key {
        font-size: 13px;
        color: #888;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .preview-row-val {
        font-size: 20px;
        color: #1a1a1a;
        font-weight: 600;
    }

    /* ── FORM SELECT AREA ── */
    .form-select {
        width: 100%;
        padding: 14px 16px;
        border: 1.5px solid #e8e4dc;
        border-radius: 10px;
        font-size: 14px;
        color: #1a1a1a;
        background: #fff;
        appearance: none;
        -webkit-appearance: none;
        outline: none;
        transition: border-color 0.2s;
        box-sizing: border-box;
        font-family: inherit;
        font-weight: 600;
    }

    .form-select:focus { border-color: #F8C61E; }

    .select-wrapper {
        position: relative;
    }

    .select-wrapper::after {
        content: '';
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        width: 0;
        height: 0;
        border-left: 5px solid transparent;
        border-right: 5px solid transparent;
        border-top: 5px solid #aaa;
        pointer-events: none;
    }

    .btn-catat {
        width: 100%;
        padding: 16px;
        background: #F8C61E;
        color: #1a1a1a;
        border: none;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 800;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: background 0.2s, transform 0.1s;
        margin-top: 8px;
    }

    .btn-catat:hover { background: #e6b418; }
    .btn-catat:active { transform: scale(0.98); }

    /* ── SEARCH CARD (Proses Keluar) ── */
    .search-card-label {
        font-size: 12px;
        font-weight: 700;
        color: #666;
        letter-spacing: 0.6px;
        margin-bottom: 10px;
        text-transform: uppercase;
    }

    .search-input-wrapper {
        position: relative;
    }

    .search-input-wrapper svg {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #bbb;
    }

    .search-input {
        width: 100%;
        padding: 14px 16px 14px 44px;
        border: 1.5px solid #e8e4dc;
        border-radius: 10px;
        font-size: 14px;
        color: #1a1a1a;
        outline: none;
        transition: border-color 0.2s;
        box-sizing: border-box;
        font-family: inherit;
        background: #fff;
        font-weight: 600;
    }

    .search-input:focus { border-color: #F8C61E; }

    .search-result-box {
        margin-top: 8px;
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

    /* ── TABLE ── */
    .section-title {
        font-size: 24px;
        font-weight: 800;
        color: #1a1a1a;
        margin-bottom: 4px;
    }

    .section-sub {
        font-size: 13px;
        color: #aaa;
        margin-bottom: 20px;
    }

    .table-card {
        background: #fff;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    }

    .filter-row {
        display: flex;
        gap: 14px;
        padding: 18px 22px;
        align-items: center;
    }

    .filter-search-wrapper {
        position: relative;
        flex: 1;
        max-width: 360px;
    }

    .filter-search-wrapper svg {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #bbb;
    }

    .filter-search {
        width: 100%;
        padding: 12px 16px 12px 42px;
        border: 1.5px solid #e8e4dc;
        border-radius: 50px;
        font-size: 14px;
        color: #1a1a1a;
        outline: none;
        transition: border-color 0.2s;
        box-sizing: border-box;
        font-family: inherit;
        background: #fff;
        font-weight: 600;
    }

    .filter-search:focus { border-color: #F8C61E; }

    .filter-sort-wrapper {
        position: relative;
    }

    .filter-sort-wrapper::after {
        content: '';
        position: absolute;
        right: 16px;
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
        padding: 12px 40px 12px 18px;
        border: 1.5px solid #e8e4dc;
        border-radius: 50px;
        font-size: 13px;
        color: #555;
        outline: none;
        appearance: none;
        -webkit-appearance: none;
        background: #fff;
        font-family: inherit;
        cursor: pointer;
        transition: border-color 0.2s;
        font-weight: 600;
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
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        padding: 16px 22px;
        text-align: left;
    }

    .parkir-table tbody tr {
        border-bottom: 1px solid #f5f3ef;
        transition: background 0.15s;
    }

    .parkir-table tbody tr:last-child { border-bottom: none; }
    .parkir-table tbody tr:hover { background: #fdfcf9; }

    .parkir-table tbody td {
        padding: 18px 22px;
        font-size: 14px;
        color: #444;
        vertical-align: middle;
    }

    .plat-text { font-weight: 700; color: #1a1a1a; letter-spacing: 0.4px; font-size: 15px; }

    .jenis-text.motor { color: #4a9eff; font-weight: 700; font-size: 14px; }
    .jenis-text.mobil { color: #F8C61E; font-weight: 700; font-size: 14px; }
    .jenis-text.bus   { color: #4caf7d; font-weight: 700; font-size: 14px; }

    .tarif-text { color: #F8C61E; font-weight: 700; font-size: 15px; }

    .aksi-btn {
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        border: none;
        background: none;
        padding: 0;
        margin-right: 14px;
    }
    .aksi-btn.keluar { color: #e05a5a; }
    .aksi-btn.struk  { color: #4a9eff; }

    .empty-state {
        text-align: center;
        color: #bbb;
        padding: 50px 20px;
        font-size: 14px;
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
        <div class="card-title">Kendaraan Masuk</div>
        <form method="POST" action="{{ route('petugas.transaksi.store') }}">
            @csrf

            {{-- Hidden input untuk kirim kendaraan_id --}}
            <input type="hidden" name="kendaraan_id" id="selectedKendaraanId">

            <div class="form-group">
                <label class="form-label">Plat Nomor</label>
                <div class="plat-search-wrapper">
                    <input
                        type="text"
                        id="platSearchInput"
                        class="plat-search-input"
                        placeholder="Ketik plat nomor..."
                        autocomplete="off"
                    >
                    <div class="plat-dropdown" id="platDropdown"></div>
                </div>
                @error('kendaraan_id')
                    <div style="color:#e05a5a; font-size:11px; margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>

            {{-- PREVIEW DATA KENDARAAN --}}
            <div class="kendaraan-preview" id="kendaraanPreview">
                <div class="preview-header">
                    <span class="preview-label">Data Kendaraan</span>
                    <span class="preview-badge" id="previewBadge"></span>
                </div>
                <div class="preview-body">
                    <div class="preview-img-box" id="previewImgBox">
                        <div class="preview-img-placeholder" id="previewImgPlaceholder">
                            <svg width="100" height="100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                <rect x="2" y="7" width="20" height="10" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>
                                <circle cx="7" cy="17" r="1.5"/><circle cx="17" cy="17" r="1.5"/>
                            </svg>
                        </div>
                        <img src="" alt="Kendaraan" id="previewImg" style="display:none;">
                    </div>
                    <div class="preview-info">
                        <div class="preview-plat" id="previewPlat">-</div>
                        <div class="preview-rows">
                            <div class="preview-row-item">
                                <span class="preview-row-key">Pemilik</span>
                                <span class="preview-row-val" id="previewPemilik">-</span>
                            </div>
                            <div class="preview-row-item">
                                <span class="preview-row-key">Jenis</span>
                                <span class="preview-row-val" id="previewJenis">-</span>
                            </div>
                            <div class="preview-row-item">
                                <span class="preview-row-key">Warna</span>
                                <span class="preview-row-val" id="previewWarna">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group" style="margin-top:14px;">
                <label class="form-label">Area</label>
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

            <button type="submit" class="btn-catat" id="btnCatat" disabled style="opacity:0.5;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                </svg>
                Catat Masuk
            </button>
        </form>
    </div>

    {{-- SEARCH KENDARAAN (Proses Keluar) --}}
    <div class="search-card">
        <div class="card-title">Proses Keluar</div>
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
        {{-- BUTTON CARI DIHAPUS --}}
        <div class="search-result-box" id="searchResultBox"></div>
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
                <th>Durasi</th>
                <th>Estimasi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="parkirTableBody">
            @forelse ($kendaraanParkir as $t)
                <tr
                    data-plat="{{ strtolower($t->kendaraan->plat_nomor ?? '') }}"
                    data-masuk="{{ $t->waktu_masuk }}"
                >
                    <td><span class="plat-text">{{ $t->kendaraan->plat_nomor ?? '-' }}</span></td>
                    <td>
                        <span class="jenis-text {{ strtolower(str_replace('/', '', $t->kendaraan->jenis ?? 'motor')) }}">
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

<script>
(function() {
    'use strict';

    // Data kendaraan lengkap dari DB (include gambar, warna, nama_pemilik)
    const kendaraanData = @json($kendaraanList);

    document.addEventListener('DOMContentLoaded', function() {
        initPlatSearch();
        initProseKeluar();
        initializeFilter();
    });

    // ─── PLAT SEARCH + PREVIEW ─────────────────────────────────────
    function initPlatSearch() {
        const input      = document.getElementById('platSearchInput');
        const dropdown   = document.getElementById('platDropdown');
        const hiddenId   = document.getElementById('selectedKendaraanId');
        const preview    = document.getElementById('kendaraanPreview');
        const btnCatat   = document.getElementById('btnCatat');

        if (!input) return;

        input.addEventListener('focus', function() {
            const q = this.value.trim().toUpperCase();
            renderDropdown(q);
        });

        input.addEventListener('input', function() {
            const q = this.value.trim().toUpperCase();
            renderDropdown(q);
        });

        input.addEventListener('blur', function() {
            setTimeout(() => closeDropdown(), 200);
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeDropdown();
        });

        document.addEventListener('click', function(e) {
            if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                closeDropdown();
            }
        });

        function renderDropdown(q) {
            dropdown.innerHTML = '';

            // Tampilkan semua kendaraan jika input kosong, atau filter berdasarkan query
            const matches = q.length < 1 
                ? kendaraanData 
                : kendaraanData.filter(k => k.plat_nomor.toUpperCase().includes(q));

            if (matches.length === 0) {
                dropdown.innerHTML = '<div class="plat-dropdown-no-result">Tidak ditemukan</div>';
                dropdown.style.display = 'block';
                return;
            }

            matches.forEach(function(k) {
                const item = document.createElement('div');
                item.className = 'plat-dropdown-item';
                item.innerHTML =
                    '<div>' +
                        '<div class="dd-plat">' + escapeHtml(k.plat_nomor) + '</div>' +
                        '<div class="dd-detail">' + escapeHtml(k.jenis) + ' · ' + escapeHtml(k.warna) + ' · ' + escapeHtml(k.nama_pemilik) + '</div>' +
                    '</div>';

                item.addEventListener('mousedown', function(e) {
                    e.preventDefault();
                    selectKendaraan(k);
                });

                dropdown.appendChild(item);
            });

            dropdown.style.display = 'block';
        }

        function selectKendaraan(k) {
            input.value        = k.plat_nomor;
            hiddenId.value     = k.id;
            closeDropdown();
            showPreview(k);

            btnCatat.disabled = false;
            btnCatat.style.opacity = '1';
        }

        function closeDropdown() {
            dropdown.style.display = 'none';
        }

        function showPreview(k) {
            // Badge jenis
            const badge = document.getElementById('previewBadge');
            const jenisKey = k.jenis.toLowerCase().replace(/truk/i, '').replace(/bus/i, '');
            badge.textContent  = k.jenis;
            badge.className    = 'preview-badge ' + (
                k.jenis === 'Motor'    ? 'motor' :
                k.jenis === 'Mobil'   ? 'mobil' :
                'bus'
            );

            // Plat
            document.getElementById('previewPlat').textContent    = k.plat_nomor;
            document.getElementById('previewPemilik').textContent = k.nama_pemilik || '-';
            document.getElementById('previewJenis').textContent   = k.jenis || '-';
            document.getElementById('previewWarna').textContent   = k.warna || '-';

            // Gambar - PASTIKAN PATH BENAR DARI DB
            const img         = document.getElementById('previewImg');
            const placeholder = document.getElementById('previewImgPlaceholder');

            if (k.gambar && k.gambar.trim() !== '') {
                // Image path dari database
                const imagePath = k.gambar.startsWith('/') ? k.gambar : '/storage/' + k.gambar;
                img.src              = imagePath;
                img.style.display    = 'block';
                placeholder.style.display = 'none';
                
                // Handle image load error - tampilkan placeholder jika gagal
                img.onerror = function() {
                    img.style.display = 'none';
                    placeholder.style.display = 'flex';
                };
            } else {
                img.style.display    = 'none';
                placeholder.style.display = 'flex';
            }

            preview.style.display = 'block';
        }
    }

    // ─── PROSES KELUAR: SEARCH PLAT (auto hasil, tanpa tombol) ────────
    function initProseKeluar() {
        const searchInput = document.getElementById('searchPlat');
        const resultBox   = document.getElementById('searchResultBox');

        if (!searchInput || !resultBox) return;

        // Data kendaraan yang sedang parkir (dari tabel)
        const parkirRows = Array.from(document.querySelectorAll('#parkirTableBody tr[data-plat]'));

        searchInput.addEventListener('input', function() {
            const q = this.value.toLowerCase().trim();
            resultBox.innerHTML = '';
            resultBox.style.display = 'none';

            if (q.length < 1) return;

            const matches = parkirRows.filter(row => row.dataset.plat.includes(q));

            if (matches.length === 0) {
                resultBox.innerHTML = '<div class="search-result-item" style="color:#bbb;">Tidak ditemukan di area parkir.</div>';
                resultBox.style.display = 'block';
                return;
            }

            matches.forEach(function(row) {
                const plat   = row.querySelector('.plat-text')?.textContent || '';
                const jenis  = row.querySelector('.jenis-text')?.textContent || '';
                const area   = row.cells[3]?.textContent || '';
                const masuk  = row.cells[2]?.textContent || '';
                const aksiEl = row.querySelector('.aksi-btn.keluar');

                const item = document.createElement('div');
                item.className = 'search-result-item';
                item.innerHTML =
                    '<div class="plat">' + escapeHtml(plat) + '</div>' +
                    '<div class="detail">' + escapeHtml(jenis) + ' · ' + escapeHtml(area) + ' · Masuk ' + escapeHtml(masuk) + '</div>';

                if (aksiEl) {
                    item.addEventListener('click', function() {
                        window.location.href = aksiEl.href;
                    });
                }

                resultBox.appendChild(item);
            });

            resultBox.style.display = 'block';
        });

        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !resultBox.contains(e.target)) {
                resultBox.style.display = 'none';
            }
        });
    }

    // ─── FILTER TABEL ────────────────────────────────────────────────
    function initializeFilter() {
        const filterPlat = document.getElementById('filterPlat');
        const filterSort = document.getElementById('filterSort');
        const tbody      = document.getElementById('parkirTableBody');

        if (!filterPlat || !filterSort || !tbody) return;

        filterPlat.addEventListener('input', applyFilter);
        filterSort.addEventListener('change', applyFilter);

        function applyFilter() {
            const query  = filterPlat.value.toLowerCase().trim();
            const sortBy = filterSort.value;

            let rows = Array.from(tbody.querySelectorAll('tr[data-plat]'));

            rows.forEach(function(row) {
                const plat = row.dataset.plat || '';
                row.style.display = plat.includes(query) ? '' : 'none';
            });

            const visibleRows = rows.filter(r => r.style.display !== 'none');

            visibleRows.sort(function(a, b) {
                const aMasuk = new Date(a.dataset.masuk);
                const bMasuk = new Date(b.dataset.masuk);
                const aPlat  = a.dataset.plat;
                const bPlat  = b.dataset.plat;

                switch(sortBy) {
                    case 'masuk_asc':  return aMasuk - bMasuk;
                    case 'masuk_desc': return bMasuk - aMasuk;
                    case 'plat_asc':   return aPlat.localeCompare(bPlat);
                    default:           return 0;
                }
            });

            visibleRows.forEach(row => tbody.appendChild(row));
        }
    }

    function escapeHtml(text) {
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return String(text).replace(/[&<>"']/g, m => map[m]);
    }

})();
</script>

@endsection