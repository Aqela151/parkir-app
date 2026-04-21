@extends('layouts.app')

@section('title', 'Riwayat Transaksi')
@section('page-title', 'Riwayat Transaksi')

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

    /* ===== CARD ===== */
    .card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    /* ===== FILTERS ===== */
    .filters {
        display: flex;
        gap: 12px;
        padding: 24px;
        flex-wrap: wrap;
        border-bottom: 1px solid #f5f3ef;
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

    .select-wrapper {
        position: relative;
    }

    .select-wrapper select {
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
        min-width: 140px;
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

    /* ===== TABLE ===== */
    .table-container {
        overflow-x: auto;
    }

    .parkir-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
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

    .status-badge {
        font-size: 12px;
        font-weight: 700;
        padding: 4px 8px;
        border-radius: 6px;
        text-transform: uppercase;
    }

    .status-badge.parkir {
        background: rgba(230, 126, 34, 0.1);
        color: #e67e22;
    }

    .status-badge.selesai {
        background: rgba(46, 204, 113, 0.1);
        color: #2ecc71;
    }

    .aksi-btn {
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        border: none;
        background: none;
        padding: 0;
        margin-right: 12px;
        color: #4a9eff;
    }

    .aksi-btn.keluar {
        color: #e05a5a;
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
</style>

{{-- TOP HEADER --}}
<div class="top-header">
    <span></span>
    <span class="petugas-panel-badge">PETUGAS PANEL</span>
</div>

{{-- GREETING --}}
<div class="greeting">
    <h1>Riwayat Transaksi</h1>
    <p>Semua transaksi parkir dengan filter dan paginasi</p>
</div>

<div class="card">
    {{-- FILTERS --}}
    <div class="filters">
        <form id="filter-form" method="GET" action="{{ route('petugas.riwayat-transaksi') }}" style="display:flex; flex-wrap:wrap; gap:12px; align-items:center; width:100%;">
            <div class="filter-search">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input
                    type="text"
                    name="search"
                    placeholder="Cari plat..."
                    value="{{ request('search') }}"
                    oninput="this.form.submit()"
                    autocomplete="off"
                >
            </div>

            <div class="select-wrapper">
                <select name="status" onchange="this.form.submit()">
                    <option value="">Status: semua</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="parkir" {{ request('status') === 'parkir' ? 'selected' : '' }}>Parkir</option>
                </select>
            </div>

            <div class="select-wrapper">
                <select name="jenis" onchange="this.form.submit()">
                    <option value="">Jenis: semua</option>
                    <option value="Mobil" {{ request('jenis') === 'Mobil' ? 'selected' : '' }}>Mobil</option>
                    <option value="Motor" {{ request('jenis') === 'Motor' ? 'selected' : '' }}>Motor</option>
                </select>
            </div>
        </form>
    </div>

    {{-- TABLE --}}
    <div class="table-container">
        <table class="parkir-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Plat Nomor</th>
                    <th>Jenis</th>
                    <th>Area</th>
                    <th>Masuk</th>
                    <th>Keluar</th>
                    <th>Durasi</th>
                    <th>Tarif</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transaksis as $trx)
                <tr>
                    <td class="row-id">{{ str_pad($loop->iteration + (($transaksis->currentPage() - 1) * $transaksis->perPage()), 3, '0', STR_PAD_LEFT) }}</td>
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
                    <td>
                        @if ($trx->waktu_keluar)
                            {{ $trx->durasi_menit ?? 0 }} mnt
                        @else
                            {{ \Carbon\Carbon::parse($trx->waktu_masuk)->diffInMinutes(now()) }} mnt
                        @endif
                    </td>
                    <td><span class="tarif-text">Rp {{ number_format($trx->tarif_akhir ?? 0, 0, ',', '.') }}</span></td>
                    <td>
                        <span class="status-badge {{ $trx->status }}">
                            {{ ucfirst($trx->status) }}
                        </span>
                    </td>
                    <td>
                        @if ($trx->status === 'selesai')
                            <a href="{{ route('petugas.riwayat-transaksi.struk', $trx->id) }}" class="aksi-btn">Struk</a>
                        @else
                            <a href="{{ route('petugas.transaksi.keluar', $trx->id) }}" class="aksi-btn keluar">Keluar</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="empty-state">Tidak ada data transaksi ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    @if ($transaksis->hasPages())
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