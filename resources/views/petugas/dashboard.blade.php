@extends('layouts.app')

@section('title', 'Dashboard Petugas')
@section('page-title', 'Dashboard Petugas:')

@section('sidebar')
    @include('components.sidebar.petugas')
@endsection

@section('content')

<style>
    /* ===== TOP HEADER ===== */
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

    /* ===== GREETING ===== */
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

    /* ===== STATS (3 kolom untuk petugas) ===== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 36px;
    }

    .stat-card {
        background: #fff;
        border-radius: 16px;
        padding: 24px 20px 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .stat-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: #fdf6e3;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-value {
        font-size: 40px;
        font-weight: 900;
        color: #1a1a1a;
        margin-bottom: 6px;
        letter-spacing: -1px;
        line-height: 1;
    }

    .stat-label {
        font-size: 12px;
        color: #aaa;
    }

    /* ===== RECENT TRANSACTIONS ===== */
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

    .transaksi-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        margin-bottom: 36px;
    }

    .transaksi-table {
        width: 100%;
        border-collapse: collapse;
    }

    .transaksi-table thead tr {
        background: #1C1C1E;
    }

    .transaksi-table thead th {
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        padding: 14px 20px;
        text-align: left;
    }

    .transaksi-table tbody tr {
        border-bottom: 1px solid #f5f3ef;
    }

    .transaksi-table tbody tr:last-child {
        border-bottom: none;
    }

    .transaksi-table tbody td {
        padding: 16px 20px;
        font-size: 13px;
        color: #444;
        vertical-align: middle;
    }

    .plat-text {
        font-weight: 600;
        color: #1a1a1a;
        letter-spacing: 0.3px;
    }

    .jenis-text.motor { color: #888; font-weight: 500; }
    .jenis-text.mobil { color: #F8C61E; font-weight: 700; }
    .jenis-text.bus   { color: #4a9eff; font-weight: 700; }

    .status-badge {
        font-size: 12px;
        font-weight: 700;
    }
    .status-badge.parkir   { color: #F8C61E; }
    .status-badge.selesai  { color: #4caf7d; }

    .aksi-btn {
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        border: none;
        background: none;
        padding: 0;
    }
    .aksi-btn.keluar { color: #e05a5a; }
    .aksi-btn.struk  { color: #4a9eff; }

    /* ===== STATUS AREA ===== */
    .status-area-card {
        background: #fff;
        border-radius: 16px;
        padding: 20px 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .area-item {
        padding: 18px 0;
        border-bottom: 1px solid #f0ede8;
    }

    .area-item:last-child { border-bottom: none; }

    .area-item-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .area-item-name {
        font-size: 14px;
        font-weight: 800;
        color: #1a1a1a;
    }

    .area-pct-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 3px 12px;
        border-radius: 20px;
    }

    .area-pct-badge.dark   { background: #1C1C1E; color: #fff; }
    .area-pct-badge.yellow { background: #F8C61E; color: #1a1a1a; }

    .area-bar-bg {
        height: 8px;
        background: #ede9e0;
        border-radius: 10px;
        margin-bottom: 8px;
        overflow: hidden;
    }

    .area-bar-fill           { height: 100%; border-radius: 10px; background: #1C1C1E; }
    .area-bar-fill.yellow    { background: #F8C61E; }
    .area-bar-fill.grey      { background: #ccc; }

    .area-slot {
        font-size: 11px;
        color: #bbb;
    }

    @media (max-width: 900px) {
        .stats-grid { grid-template-columns: repeat(2,1fr); }
    }

    @media (max-width: 600px) {
        .stats-grid { grid-template-columns: 1fr; }
    }
</style>

{{-- TOP HEADER --}}
<div class="top-header">
    <span></span>
    <span class="petugas-panel-badge">PETUGAS PANEL</span>
</div>

{{-- GREETING --}}
<div class="greeting">
    <h1>Selamat Bertugas, {{ auth()->user()->name ?? 'Petugas' }}</h1>
</div>

{{-- STATS --}}
<div class="stats-grid">
    {{-- Kendaraan Masuk --}}
    <div class="stat-card">
        <div class="stat-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#F8C61E" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/>
            </svg>
        </div>
        <div class="stat-value">{{ $kendaraanMasuk ?? 0 }}</div>
        <div class="stat-label">Kendaraan Masuk Hari Ini</div>
    </div>

    {{-- Kendaraan Keluar --}}
    <div class="stat-card">
        <div class="stat-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#F8C61E" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"/><polyline points="19 12 12 19 5 12"/>
            </svg>
        </div>
        <div class="stat-value">{{ $kendaraanKeluar ?? 0 }}</div>
        <div class="stat-label">Kendaraan Keluar Hari Ini</div>
    </div>

    {{-- Kendaraan Parkir Sekarang --}}
    <div class="stat-card">
        <div class="stat-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#F8C61E" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="1" y="11" width="22" height="7" rx="2"/>
                <path d="M6 11V7a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v4"/>
                <circle cx="8" cy="18" r="2"/><circle cx="16" cy="18" r="2"/>
            </svg>
        </div>
        <div class="stat-value">{{ $kendaraanParkir ?? 0 }}</div>
        <div class="stat-label">Kendaraan Parkir Sekarang</div>
    </div>
</div>

{{-- TRANSAKSI TERAKHIR --}}
<div class="section-title">Transaksi Terakhir</div>
<div class="section-sub">{{ $jumlahTransaksiBaru ?? 0 }} transaksi baru</div>
<div class="transaksi-card">
    <table class="transaksi-table">
        <thead>
            <tr>
                <th>Plat</th>
                <th>Jenis</th>
                <th>Masuk</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transaksiTerakhir as $t)
                <tr>
                    <td><span class="plat-text">{{ $t->no_plat }}</span></td>
                    <td>
                        <span class="jenis-text {{ strtolower($t->jenis_kendaraan) }}">
                            {{ ucfirst($t->jenis_kendaraan) }}
                        </span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($t->waktu_masuk)->format('H:i') }}</td>
                    <td>
                        <span class="status-badge {{ $t->status === 'parkir' ? 'parkir' : 'selesai' }}">
                            {{ ucfirst($t->status) }}
                        </span>
                    </td>
                    <td>
                        @if ($t->status === 'parkir')
                            <a href="{{ route('petugas.transaksi.keluar', $t->id) }}" class="aksi-btn keluar">Keluar</a>
                        @else
                            <a href="{{ route('petugas.transaksi.struk', $t->id) }}" class="aksi-btn struk">Struk</a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center; color:#bbb; padding: 32px 20px; font-size:13px;">
                        Belum ada transaksi hari ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- STATUS AREA --}}
<div class="section-title">Status Area</div>
<div class="section-sub">Status Real-time Hari Ini</div>
<div class="status-area-card">
    @forelse ($statusArea as $area)
        @php
            $pct = $area['kapasitas'] > 0
                ? round(($area['terisi'] / $area['kapasitas']) * 100)
                : 0;

            if ($pct >= 90)      $barClass = 'dark';
            elseif ($pct >= 50)  $barClass = 'yellow';
            else                 $barClass = 'grey';

            $badgeClass = ($pct === 0) ? 'yellow' : 'dark';
        @endphp
        <div class="area-item">
            <div class="area-item-top">
                <span class="area-item-name">{{ $area['nama'] }}</span>
                <span class="area-pct-badge {{ $badgeClass }}">{{ $pct }}%</span>
            </div>
            <div class="area-bar-bg">
                <div class="area-bar-fill {{ $barClass }}"style="width: {{ $pct . '%' }}""></div>
            </div>
            <div class="area-slot">
                @if (!empty($area['keterangan']))
                    {{ $area['keterangan'] }}
                @else
                    {{ $area['terisi'] }}/{{ $area['kapasitas'] }} slot
                @endif
            </div>
        </div>
    @empty
        <div class="area-item">
            <div class="area-item-top">
                <span class="area-item-name">Belum ada data area</span>
                <span class="area-pct-badge yellow">0%</span>
            </div>
            <div class="area-bar-bg"><div class="area-bar-fill grey" style="width:0%"></div></div>
            <div class="area-slot">Hubungi admin untuk mengatur area parkir.</div>
        </div>
    @endforelse
</div>

@endsection

<script>
(function() {
    'use strict';

    const API_URL = '{{ route("petugas.api.status-area") }}';
    const UPDATE_INTERVAL = 10000; // 10 detik

    // Inisialisasi saat DOM siap
    document.addEventListener('DOMContentLoaded', function() {
        // Update initial status
        updateStatusArea();
        // Set interval untuk update berkala
        setInterval(updateStatusArea, UPDATE_INTERVAL);
    });

    /**
     * Update status area dari API
     */
    function updateStatusArea() {
        fetch(API_URL)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                updateAreaDisplay(data);
            })
            .catch(error => {
                console.error('Error updating status area:', error);
            });
    }

    /**
     * Update display area items berdasarkan data API
     */
    function updateAreaDisplay(data) {
        const areaItems = document.querySelectorAll('.status-area-card .area-item');

        data.forEach((area, index) => {
            if (areaItems[index]) {
                const item = areaItems[index];
                const pct = area.kapasitas > 0 
                    ? Math.round((area.terisi / area.kapasitas) * 100) 
                    : 0;

                let barClass = 'grey';
                let badgeClass = 'yellow';

                if (pct >= 90) {
                    barClass = 'dark';
                    badgeClass = 'dark';
                } else if (pct >= 50) {
                    barClass = 'yellow';
                    badgeClass = 'yellow';
                }

                // Update badge
                const badge = item.querySelector('.area-pct-badge');
                if (badge) {
                    badge.className = `area-pct-badge ${badgeClass}`;
                    badge.textContent = `${pct}%`;
                }

                // Update progress bar
                const barFill = item.querySelector('.area-bar-fill');
                if (barFill) {
                    barFill.className = `area-bar-fill ${barClass}`;
                    barFill.style.width = `${pct}%`;
                }

                // Update slot text
                const slot = item.querySelector('.area-slot');
                if (slot) {
                    slot.textContent = `${area.terisi}/${area.kapasitas} slot`;
                }
            }
        });
    }

})();
</script>