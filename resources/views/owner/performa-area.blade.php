@extends('layouts.app')

@section('title', 'Performa Area')
@section('page-title', 'Performa Area')

@section('sidebar')
    @include('components.sidebar.owner')
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

    .owner-panel-badge {
        border: 1.5px solid #4a9eff;
        color: #4a9eff;
        border-radius: 20px;
        padding: 4px 16px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        background: rgba(74,158,255,0.06);
    }

    /* ===== HEADING ===== */
    .page-heading {
        font-size: 36px;
        font-weight: 900;
        color: #1a1a1a;
        letter-spacing: -1px;
        margin-bottom: 6px;
    }

    .page-subtitle {
        font-size: 13px;
        color: #aaa;
        margin-bottom: 32px;
    }

    /* ===== AREA GRID ===== */
    .area-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    /* ===== AREA CARD ===== */
    .area-card {
        background: #fff;
        border-radius: 16px;
        padding: 24px 26px 22px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }

    .card-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 4px;
    }

    .card-name {
        font-size: 16px;
        font-weight: 800;
        color: #1C1C1E;
        letter-spacing: -0.3px;
    }

    .card-address {
        font-size: 12px;
        font-weight: 500;
        color: #aaa49a;
        margin-bottom: 14px;
    }

    /* ===== PCT BADGE ===== */
    .pct-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 44px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .pct-badge.dark   { background: #1C1C1E; color: #fff; }
    .pct-badge.yellow { background: #F8C61E; color: #1a1a1a; }

    .toggle-badge {
        width: 44px;
        height: 24px;
        border-radius: 20px;
        background: #e8e2d6;
        position: relative;
        flex-shrink: 0;
    }

    .toggle-badge::after {
        content: '';
        position: absolute;
        top: 3px;
        right: 3px;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #F8C61E;
    }

    /* ===== PROGRESS ===== */
    .progress-wrap {
        width: 100%;
        height: 8px;
        background: #e8e2d6;
        border-radius: 99px;
        overflow: hidden;
        margin-bottom: 12px;
    }

    .progress-bar { height: 100%; border-radius: 99px; }
    .bar-dark     { background: #1C1C1E; }
    .bar-gold     { background: #F8C61E; }
    .bar-grey     { background: #ccc; }

    /* ===== STATS ROW ===== */
    .stats-row {
        display: flex;
        justify-content: space-between;
        gap: 8px;
    }

    .stat-box {
        flex: 1;
        background: #f5f3ef;
        border-radius: 12px;
        padding: 12px 14px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 2px;
    }

    .stat-val {
        font-size: 18px;
        font-weight: 800;
        color: #1C1C1E;
        letter-spacing: -0.5px;
        white-space: nowrap;
    }

    .stat-val.highlight {
        background: #1C1C1E;
        color: #F8C61E;
        border-radius: 8px;
        padding: 2px 10px;
        font-size: 16px;
    }

    .stat-lbl {
        font-size: 11px;
        font-weight: 500;
        color: #aaa49a;
        text-align: center;
    }

    @media (max-width: 900px) {
        .area-grid { grid-template-columns: 1fr; }
    }
</style>

{{-- TOP HEADER --}}
<div class="top-header">
    <span></span>
    <span class="owner-panel-badge">OWNER PANEL</span>
</div>

{{-- HEADING --}}
<h1 class="page-heading">Performa Area</h1>
<p class="page-subtitle">Status Kapasitas real-time–Kota Malang</p>

{{-- GRID --}}
<div class="area-grid">
    @forelse ($performaArea as $area)
        @php
            $pct = ($area['kapasitas'] ?? 0) > 0
                ? round(($area['terisi'] / $area['kapasitas']) * 100)
                : null;

            if ($pct === null)  { $barClass = 'bar-grey'; $badgeType = 'toggle'; }
            elseif ($pct >= 75) { $barClass = 'bar-dark'; $badgeType = 'dark'; }
            else                { $barClass = 'bar-gold'; $badgeType = 'yellow'; }

            $pendapatan = $area['pendapatan'] ?? 0;
            $pendapatanFmt = $pendapatan >= 1_000_000
                ? 'Rp ' . number_format($pendapatan / 1_000_000, 1, ',', '.') . ' Jt'
                : 'Rp ' . number_format($pendapatan, 0, ',', '.');
        @endphp

        <div class="area-card">

            {{-- Card Header --}}
            <div class="card-header">
                <span class="card-name">{{ $area['nama'] }}</span>

                @if ($badgeType === 'toggle')
                    <span class="toggle-badge"></span>
                @else
                    <span class="pct-badge {{ $badgeType }}">{{ $pct }}%</span>
                @endif
            </div>

            <p class="card-address">{{ $area['alamat'] ?? '' }}</p>

            {{-- Progress Bar --}}
            <div class="progress-wrap">
                <div class="progress-bar {{ $barClass }}"
                     style="width: {{ $pct !== null ? $pct : 0 }}%"></div>
            </div>

            {{-- Stats --}}
            <div class="stats-row">
                <div class="stat-box">
                    <span class="stat-val">{{ number_format($area['tx_bulan_ini'] ?? 0, 0, ',', '.') }}</span>
                    <span class="stat-lbl">Tx bulan ini</span>
                </div>
                <div class="stat-box">
                    <span class="stat-val">{{ $pendapatanFmt }}</span>
                    <span class="stat-lbl">Pendapatan</span>
                </div>
                <div class="stat-box">
                    <span class="stat-val highlight">{{ $area['rata_durasi'] ?? 0 }} mnt</span>
                    <span class="stat-lbl">Rata durasi</span>
                </div>
            </div>

        </div>

    @empty
        <div class="area-card" style="grid-column:1/-1; text-align:center; color:#bbb; padding:48px 20px; font-size:13px;">
            Belum ada data area parkir.
        </div>
    @endforelse
</div>

@endsection