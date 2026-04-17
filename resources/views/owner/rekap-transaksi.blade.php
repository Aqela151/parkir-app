@extends('layouts.app')

@section('title', 'Rekap Transaksi')
@section('page-title', 'Rekap Transaksi')

@section('sidebar')
    @include('components.sidebar.owner')
@endsection

@section('content')

<style>
    .top-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
    }

    .owner-panel-badge {
        border: 1.5px solid #b084f5;
        color: #b084f5;
        border-radius: 20px;
        padding: 4px 16px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        background: rgba(176,132,245,0.06);
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

    /* ===== FILTER CARD ===== */
    .card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }

    .filter-section {
        padding: 24px;
        border-bottom: 1px solid #f5f3ef;
    }

    .filter-row {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: flex-end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .filter-label {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: #aaa;
    }

    .select-wrapper {
        position: relative;
    }

    .select-wrapper select,
    .filter-input {
        padding: 10px 36px 10px 14px;
        border: 1.5px solid #e8e4dc;
        border-radius: 10px;
        font-size: 13px;
        color: #1a1a1a;
        background: #fff;
        appearance: none;
        -webkit-appearance: none;
        outline: none;
        cursor: pointer;
        font-family: inherit;
        min-width: 150px;
    }

    .filter-input {
        padding-right: 14px;
        min-width: 150px;
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

    .btn-filter {
        padding: 10px 20px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        border: none;
        font-family: inherit;
        transition: all 0.2s;
    }

    .btn-filter.primary {
        background: #F8C61E;
        color: #1a1a1a;
    }

    .btn-filter.primary:hover {
        background: #e6b418;
    }

    .btn-filter.secondary {
        background: #1C1C1E;
        color: #fff;
    }

    .btn-filter.secondary:hover {
        background: #2a2a2a;
    }

    /* ===== STAT CARDS ===== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0;
        border-bottom: 1px solid #f5f3ef;
    }

    .stat-card {
        padding: 24px;
        border-right: 1px solid #f5f3ef;
        text-align: left;
    }

    .stat-card:last-child {
        border-right: none;
        background: #1C1C1E;
        border-radius: 0;
    }

    .stat-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        color: #bbb;
        margin-bottom: 8px;
        text-transform: uppercase;
    }

    .stat-card:last-child .stat-label {
        color: #888;
    }

    .stat-value {
        font-size: 22px;
        font-weight: 800;
        color: #1a1a1a;
        letter-spacing: -0.5px;
    }

    .stat-value.yellow { color: #F8C61E; }
    .stat-card:last-child .stat-value { color: #F8C61E; }

    /* ===== INNER FILTERS (search + sort) ===== */
    .inner-filters {
        display: flex;
        gap: 12px;
        padding: 20px 24px;
        border-bottom: 1px solid #f5f3ef;
        flex-wrap: wrap;
    }

    .filter-search {
        display: flex;
        align-items: center;
        gap: 10px;
        border: 1.5px solid #e8e4dc;
        border-radius: 10px;
        padding: 10px 14px;
        background: #fff;
        flex: 1;
        min-width: 200px;
    }

    .filter-search i {
        color: #aaa;
        font-size: 13px;
    }

    .filter-search input {
        border: none;
        outline: none;
        background: transparent;
        font-family: inherit;
        font-size: 13px;
        color: #1a1a1a;
        width: 100%;
    }

    .filter-search input::placeholder {
        color: #bbb;
    }

    /* ===== TABLE ===== */
    .table-container {
        overflow-x: auto;
    }

    .parkir-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
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

    .parkir-table tbody tr:last-child {
        border-bottom: none;
    }

    .parkir-table tbody tr:hover {
        background: #fdfcf9;
    }

    .parkir-table tbody td {
        padding: 15px 20px;
        font-size: 13px;
        color: #444;
        vertical-align: middle;
    }

    .row-id {
        color: #bbb;
        font-weight: 600;
        width: 60px;
    }

    .plat-text {
        font-weight: 600;
        color: #1a1a1a;
        letter-spacing: 0.3px;
    }

    .jenis-text.motor {
        color: #4a9eff;
        font-weight: 600;
    }

    .jenis-text.mobil {
        color: #F8C61E;
        font-weight: 600;
    }

    .tarif-text {
        color: #F8C61E;
        font-weight: 700;
    }

    .empty-state {
        text-align: center;
        color: #bbb;
        padding: 40px 20px;
        font-size: 13px;
    }

    /* ===== PAGINATION ===== */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        padding: 24px;
        border-top: 1px solid #f5f3ef;
    }

    .page-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: 1.5px solid #e8e4dc;
        background: #fff;
        color: #666;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .page-link:hover {
        background: #f8f6f2;
        border-color: #F8C61E;
    }

    .page-link.active {
        background: #F8C61E;
        border-color: #F8C61E;
        color: #1a1a1a;
        font-weight: 700;
    }

    .page-link.disabled {
        opacity: 0.5;
        pointer-events: none;
    }

    .page-link-text {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 36px;
        padding: 0 12px;
        border-radius: 8px;
        border: 1.5px solid #e8e4dc;
        background: #fff;
        color: #666;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .page-link-text:hover {
        background: #f8f6f2;
        border-color: #F8C61E;
    }

    @media (max-width: 900px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .stat-card:nth-child(2) { border-right: none; }
    }
</style>

{{-- TOP HEADER --}}
<div class="top-header">
    <span></span>
    <span class="owner-panel-badge">OWNER PANEL</span>
</div>

{{-- GREETING --}}
<div class="greeting">
    <h1>Rekap Transaksi</h1>
    <p>Filter berdasarkan lokasi dan rentang waktu</p>
</div>

{{-- FILTER + STATS CARD --}}
<div class="card">
    {{-- DATE/LOKASI/JENIS FILTERS --}}
    <div class="filter-section">
        <form id="rekap-form" method="GET" action="{{ route('owner.rekap-transaksi') }}">
            <div class="filter-row">
                <div class="filter-group">
                    <span class="filter-label">Dari Tanggal</span>
                    <input
                        type="date"
                        name="dari_tanggal"
                        class="filter-input"
                        value="{{ request('dari_tanggal') }}"
                    >
                </div>
                <div class="filter-group">
                    <span class="filter-label">Sampai Tanggal</span>
                    <input
                        type="date"
                        name="sampai_tanggal"
                        class="filter-input"
                        value="{{ request('sampai_tanggal', now()->format('Y-m-d')) }}"
                    >
                </div>
                <div class="filter-group">
                    <span class="filter-label">Lokasi</span>
                    <div class="select-wrapper">
                        <select name="lokasi">
                            <option value="">Semua Lokasi</option>
                            @foreach ($areas ?? [] as $area)
                                <option value="{{ $area->id }}" {{ request('lokasi') == $area->id ? 'selected' : '' }}>
                                    {{ $area->nama_area }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="filter-group">
                    <span class="filter-label">Jenis</span>
                    <div class="select-wrapper">
                        <select name="jenis">
                            <option value="">Semua Jenis</option>
                            <option value="Mobil" {{ request('jenis') === 'Mobil' ? 'selected' : '' }}>Mobil</option>
                            <option value="Motor" {{ request('jenis') === 'Motor' ? 'selected' : '' }}>Motor</option>
                        </select>
                    </div>
                </div>
                <div class="filter-group" style="justify-content: flex-end;">
                    <span class="filter-label">&nbsp;</span>
                    <div style="display:flex; gap:8px;">
                        <button type="submit" class="btn-filter primary">Filter</button>
                        <a href="{{ route('owner.rekap-transaksi') }}" class="btn-filter secondary" style="text-decoration:none; display:inline-flex; align-items:center;">Reset</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- STAT CARDS --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Transaksi</div>
            <div class="stat-value yellow">{{ number_format($totalTransaksi ?? 0, 0, ',', '.') }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total Pendapatan</div>
            <div class="stat-value">Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Rata-rata Durasi</div>
            <div class="stat-value">{{ $rataDurasi ?? 0 }} mnt</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Rata-rata Transaksi</div>
            <div class="stat-value">Rp {{ number_format($rataTransaksi ?? 0, 0, ',', '.') }}</div>
        </div>
    </div>
</div>

{{-- TABLE CARD --}}
<div class="card">
    {{-- INNER SEARCH + SORT --}}
    <div class="inner-filters">
        <div class="filter-search">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input
                type="text"
                placeholder="Cari plat, area..."
                name="search"
                value="{{ request('search') }}"
                form="rekap-form"
                oninput="document.getElementById('rekap-form').submit()"
            >
        </div>
        <div class="select-wrapper">
            <select name="sort" form="rekap-form" onchange="document.getElementById('rekap-form').submit()">
                <option value="terbaru" {{ request('sort', 'terbaru') === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                <option value="terlama" {{ request('sort') === 'terlama' ? 'selected' : '' }}>Terlama</option>
                <option value="tarif_desc" {{ request('sort') === 'tarif_desc' ? 'selected' : '' }}>Tarif Tertinggi</option>
                <option value="tarif_asc" {{ request('sort') === 'tarif_asc' ? 'selected' : '' }}>Tarif Terendah</option>
            </select>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="table-container">
        <table class="parkir-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tanggal</th>
                    <th>Plat Nomor</th>
                    <th>Jenis</th>
                    <th>Area</th>
                    <th>Masuk</th>
                    <th>Keluar</th>
                    <th>Durasi</th>
                    <th>Tarif</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transaksis ?? collect() as $trx)
                <tr>
                    <td class="row-id">{{ str_pad(($loop->iteration + ((($transaksis->currentPage() ?? 1) - 1) * ($transaksis->perPage() ?? 10))), 3, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ \Carbon\Carbon::parse($trx->waktu_masuk)->format('d M') }}</td>
                    <td><span class="plat-text">{{ $trx->kendaraan->plat_nomor ?? '-' }}</span></td>
                    <td>
                        <span class="jenis-text {{ strtolower($trx->kendaraan->jenis ?? 'motor') }}">
                            {{ ucfirst($trx->kendaraan->jenis ?? '-') }}
                        </span>
                    </td>
                    <td>{{ $trx->area->nama_area ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($trx->waktu_masuk)->format('H:i') }}</td>
                    <td>
                        @if ($trx->waktu_keluar)
                            {{ \Carbon\Carbon::parse($trx->waktu_keluar)->format('H:i') }}
                        @else
                            <span style="color:#ccc;">–</span>
                        @endif
                    </td>
                    <td>{{ $trx->durasi_menit ?? \Carbon\Carbon::parse($trx->waktu_masuk)->diffInMinutes(now()) }} mnt</td>
                    <td><span class="tarif-text">Rp {{ number_format($trx->tarif_akhir ?? 0, 0, ',', '.') }}</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="empty-state">Tidak ada data transaksi ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    @if (isset($transaksis) && $transaksis->hasPages())
    <div class="pagination-wrapper">
        {{-- Previous --}}
        @if ($transaksis->onFirstPage())
            <span class="page-link disabled">
                <i class="fa-solid fa-chevron-left"></i>
            </span>
        @else
            <a href="{{ $transaksis->previousPageUrl() }}&{{ http_build_query(request()->except('page')) }}" class="page-link">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
        @endif

        {{-- Page Numbers --}}
        @foreach ($transaksis->getUrlRange(1, $transaksis->lastPage()) as $page => $url)
            <a href="{{ $url }}&{{ http_build_query(request()->except('page')) }}"
               class="page-link {{ $page == $transaksis->currentPage() ? 'active' : '' }}">
                {{ $page }}
            </a>
        @endforeach

        {{-- Next --}}
        @if ($transaksis->hasMorePages())
            <a href="{{ $transaksis->nextPageUrl() }}&{{ http_build_query(request()->except('page')) }}" class="page-link">
                <i class="fa-solid fa-chevron-right"></i>
            </a>
        @else
            <span class="page-link disabled">
                <i class="fa-solid fa-chevron-right"></i>
            </span>
        @endif
    </div>
    @endif
</div>

@endsection