@extends('layouts.app')

@section('title', 'Dashboard Owner')
@section('page-title', 'Dashboard Owner')

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
        border: 1.5px solid #b084f5;
        color: #b084f5;
        border-radius: 20px;
        padding: 4px 16px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        background: rgba(176,132,245,0.06);
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

    /* ===== STAT CARDS ===== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
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
        font-size: 18px;
        color: #F8C61E;
    }

    .stat-value {
        font-size: 32px;
        font-weight: 900;
        color: #1a1a1a;
        margin-bottom: 4px;
        letter-spacing: -1px;
        line-height: 1;
    }

    .stat-label {
        font-size: 12px;
        color: #aaa;
        margin-bottom: 6px;
    }

    .stat-note {
        font-size: 11px;
        font-weight: 700;
    }

    .stat-note.down  { color: #e05a5a; }
    .stat-note.up    { color: #4caf7d; }
    .stat-note.info  { color: #4a9eff; }
    .stat-note.grey  { color: #4caf7d; }

    /* ===== SECTION TITLE ===== */
    .section-title {
        font-size: 20px;
        font-weight: 800;
        color: #1a1a1a;
        margin-bottom: 4px;
        letter-spacing: -0.4px;
    }

    .section-sub {
        font-size: 13px;
        color: #aaa;
        margin-bottom: 20px;
    }

    /* ===== PENDAPATAN GRID ===== */
    .pendapatan-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    /* ===== CHART CARD ===== */
    .chart-card {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .chart-legend {
        display: flex;
        gap: 16px;
        justify-content: flex-end;
        margin-bottom: 16px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: #888;
        font-weight: 600;
    }

    .legend-dot {
        width: 12px;
        height: 12px;
        border-radius: 3px;
    }

    .legend-dot.motor { background: #F8C61E; }
    .legend-dot.mobil { background: #ccc; }

    .chart-bars {
        display: flex;
        align-items: flex-end;
        gap: 10px;
        height: 160px;
        padding-bottom: 28px;
        position: relative;
    }

    .bar-group {
        flex: 1;
        display: flex;
        align-items: flex-end;
        gap: 3px;
        position: relative;
    }

    .bar {
        flex: 1;
        border-radius: 4px 4px 0 0;
        min-height: 4px;
    }

    .bar.motor { background: #F8C61E; }
    .bar.mobil { background: #ccc; }

    .bar-label {
        position: absolute;
        bottom: -22px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 10px;
        color: #bbb;
        font-weight: 600;
        white-space: nowrap;
    }

    /* ===== AREA LIST CARD ===== */
    .area-list-card {
        background: #fff;
        border-radius: 16px;
        padding: 20px 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .area-list-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 0;
        border-bottom: 1px solid #f5f3ef;
    }

    .area-list-item:last-child { border-bottom: none; }

    .area-rank {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: #f5f0e8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 800;
        color: #1a1a1a;
        flex-shrink: 0;
    }

    .area-rank.gold { background: #F8C61E; color: #1a1a1a; }

    .area-info { flex: 1; min-width: 0; }

    .area-info-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 6px;
    }

    .area-info-name {
        font-size: 13px;
        font-weight: 800;
        color: #1a1a1a;
    }

    .area-income {
        font-size: 13px;
        font-weight: 700;
        color: #1a1a1a;
        flex-shrink: 0;
    }

    .area-address {
        font-size: 11px;
        color: #bbb;
        margin-bottom: 8px;
    }

    .area-bar-bg {
        height: 5px;
        background: #ede9e0;
        border-radius: 10px;
        overflow: hidden;
    }

    .area-bar-fill        { height: 100%; border-radius: 10px; background: #1C1C1E; }
    .area-bar-fill.gold   { background: #F8C61E; }

    /* ===== BOTTOM STATS ===== */
    .bottom-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 20px;
    }

    .bottom-card {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .bottom-stat-row {
        margin-bottom: 10px;
    }

    .bottom-stat-label {
        font-size: 12px;
        color: #bbb;
        margin-bottom: 2px;
    }

    .bottom-stat-value {
        font-size: 15px;
        font-weight: 700;
        color: #1a1a1a;
    }

    .highlight-badge {
        display: inline-block;
        background: #F8C61E;
        color: #1a1a1a;
        font-size: 13px;
        font-weight: 800;
        padding: 4px 14px;
        border-radius: 20px;
        margin-top: 4px;
    }

    @media (max-width: 1100px) {
        .stats-grid { grid-template-columns: repeat(2,1fr); }
    }
    @media (max-width: 700px) {
        .stats-grid { grid-template-columns: 1fr; }
        .pendapatan-grid { grid-template-columns: 1fr; }
        .bottom-grid { grid-template-columns: 1fr; }
    }
</style>

{{-- TOP HEADER --}}
<div class="top-header">
    <span></span>
    <span class="owner-panel-badge">OWNER PANEL</span>
</div>

{{-- GREETING --}}
<div class="greeting">
    <h1>Selamat Datang, {{ auth()->check() ? (auth()->user()->name ?? 'Owner') : 'Owner' }}</h1>
    <p>Ringkasan sistem parkir hari ini</p>
</div>

{{-- STATS --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon-box"><i class="fa-solid fa-dollar-sign"></i></div>
        <div class="stat-value">{{ $pendapatanHariIniFormatted ?? 'Rp 0' }}</div>
        <div class="stat-label">Pendapatan Hari Ini</div>
        <div class="stat-note {{ isset($pendapatanTrend) && $pendapatanTrend >= 0 ? 'up' : 'down' }}">
            {{ isset($pendapatanTrend) ? ($pendapatanTrend >= 0 ? '+' : '') . $pendapatanTrend . '%' : '+0%' }} dari kemarin
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon-box"><i class="fa-solid fa-van-shuttle"></i></div>
        <div class="stat-value">{{ $transaksiHariIni ?? 0 }}</div>
        <div class="stat-label">Transaksi Hari Ini</div>
        <div class="stat-note info">{{ $lokasiAktif ?? 'Semua lokasi' }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon-box"><i class="fa-solid fa-building"></i></div>
        <div class="stat-value">{{ $pendapatanBulanIniFormatted ?? 'Rp 0' }}</div>
        <div class="stat-label">Pendapatan Bulan Ini</div>
        <div class="stat-note {{ isset($pendapatanBulanTrend) && $pendapatanBulanTrend >= 0 ? 'up' : 'down' }}">
            {{ isset($pendapatanBulanTrend) ? ($pendapatanBulanTrend >= 0 ? '+' : '') . $pendapatanBulanTrend . '%' : '+0%' }} vs bulan lalu
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon-box"><i class="fa-regular fa-building"></i></div>
        <div class="stat-value">{{ $jumlahAreaAktif ?? 0 }}</div>
        <div class="stat-label">Area Aktif</div>
        <div class="stat-note grey">Kapasitas rata-rata {{ $kapasitasRataRata ?? 0 }}%</div>
    </div>
</div>

{{-- PENDAPATAN PER AREA --}}
<div class="section-title">Pendapatan per Area</div>
<div class="section-sub">Bulan ini–semua titik parkir</div>

<div class="pendapatan-grid">

    {{-- BAR CHART --}}
    <div class="chart-card">
        <div class="chart-legend">
            <div class="legend-item"><div class="legend-dot motor"></div> Motor</div>
            <div class="legend-item"><div class="legend-dot mobil"></div> Mobil</div>
        </div>
        <div class="chart-bars">
            @php
                $days = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
                // Gunakan data dari controller jika ada, fallback ke dummy
                $chartData = $chartHarian ?? [
                    ['motor' => 40, 'mobil' => 55],
                    ['motor' => 45, 'mobil' => 60],
                    ['motor' => 50, 'mobil' => 65],
                    ['motor' => 48, 'mobil' => 70],
                    ['motor' => 60, 'mobil' => 80],
                    ['motor' => 70, 'mobil' => 90],
                    ['motor' => 75, 'mobil' => 100],
                ];
                $maxVal = collect($chartData)->map(fn($d) => max($d['motor'] ?? 0, $d['mobil'] ?? 0))->max() ?: 100;
            @endphp

            @forelse ($chartData as $i => $day)
                <div class="bar-group">
                    <div class="bar motor" style="height: {{ round(($day['motor'] ?? 0) / $maxVal * 130) }}px"></div>
                    <div class="bar mobil" style="height: {{ round(($day['mobil'] ?? 0) / $maxVal * 130) }}px"></div>
                    <span class="bar-label">{{ $days[$i] ?? '' }}</span>
                </div>
            @empty
                <div style="text-align: center; width: 100%; color: #bbb; padding: 40px 0;">No data</div>
            @endforelse
        </div>
    </div>

    {{-- AREA LIST --}}
    <div class="area-list-card">
        @forelse ($pendapatanPerArea ?? [] as $i => $area)
            @php
                $maxIncome = collect($pendapatanPerArea ?? [])->max(fn($a) => $a['pendapatan'] ?? 0) ?: 1;
                $barWidth  = $maxIncome > 0 ? round(($area['pendapatan'] ?? 0) / $maxIncome * 100) : 0;
                $isFirst   = $i === 0;
            @endphp
            <div class="area-list-item">
                <div class="area-rank {{ $isFirst ? 'gold' : '' }}">{{ $i + 1 }}</div>
                <div class="area-info">
                    <div class="area-info-top">
                        <span class="area-info-name">{{ $area['nama'] ?? 'Area' }}</span>
                        <span class="area-income">{{ $area['pendapatanFormatted'] ?? 'Rp 0' }}</span>
                    </div>
                    <div class="area-address">{{ $area['alamat'] ?? '-' }}</div>
                    <div class="area-bar-bg">
                        <div class="area-bar-fill {{ $isFirst ? 'gold' : '' }}" style="width: {{ $barWidth }}%"></div>
                    </div>
                </div>
            </div>
        @empty
            <div style="text-align:center; color:#bbb; padding: 32px 0; font-size:13px;">
                Belum ada data pendapatan area.
            </div>
        @endforelse
    </div>
</div>

{{-- BOTTOM STATS --}}
<div class="bottom-grid">
    {{-- Kendaraan --}}
    <div class="bottom-card">
        <div class="bottom-stat-row">
            <div class="bottom-stat-label">Total masuk hari ini</div>
            <div class="bottom-stat-value">{{ $totalMasuk ?? 0 }} kendaraan</div>
        </div>
        <div class="bottom-stat-row">
            <div class="bottom-stat-label">Total keluar hari ini</div>
            <div class="bottom-stat-value">{{ $totalKeluar ?? 0 }} kendaraan</div>
        </div>
        <div class="bottom-stat-row">
            <div class="bottom-stat-label">Pendapatan hari ini</div>
            <span class="highlight-badge">{{ $pendapatanHariIniFormatted ?? 'Rp 0' }}</span>
        </div>
    </div>

    {{-- Area tersibuk --}}
    <div class="bottom-card">
        <div class="bottom-stat-row">
            <div class="bottom-stat-label">Area tersibuk</div>
            <div class="bottom-stat-value">{{ $areaTersibuk ?? '-' }}</div>
        </div>
        <div class="bottom-stat-row">
            <div class="bottom-stat-label">Jam tersibuk</div>
            <div class="bottom-stat-value">{{ $jamTersibuk ?? '-' }}</div>
        </div>
        <div class="bottom-stat-row">
            <div class="bottom-stat-label">Petugas aktif</div>
            <div class="bottom-stat-value">{{ $petugasAktif ?? 0 }} orang</div>
        </div>
    </div>

    {{-- Perbandingan --}}
    <div class="bottom-card">
        <div class="bottom-stat-row">
            <div class="bottom-stat-label">Pendapatan bulan lalu</div>
            <div class="bottom-stat-value">{{ $pendapatanBulanLaluFormatted ?? 'Rp 0' }}</div>
        </div>
        <div class="bottom-stat-row">
            <div class="bottom-stat-label">Pendapatan bulan ini</div>
            <div class="bottom-stat-value">{{ $pendapatanBulanIniFormatted ?? 'Rp 0' }}</div>
        </div>
        <div class="bottom-stat-row">
            <div class="bottom-stat-label">Pertumbuhan</div>
            <div class="bottom-stat-value {{ isset($pendapatanBulanTrend) && $pendapatanBulanTrend >= 0 ? '' : 'down' }}">
                {{ isset($pendapatanBulanTrend) ? ($pendapatanBulanTrend >= 0 ? '+' : '') . $pendapatanBulanTrend . '%' : '+0%' }}
            </div>
        </div>
    </div>
</div>

@endsection