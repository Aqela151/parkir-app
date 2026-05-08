@extends('layouts.app')

@section('title', 'Grafik Pendapatan')
@section('page-title', 'Grafik Pendapatan')

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

    /* ===== FILTER BAR ===== */
    .filter-bar {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .filter-label {
        font-size: 12px;
        font-weight: 700;
        color: #aaa;
        letter-spacing: 0.5px;
    }

    .filter-select {
        padding: 8px 14px;
        border-radius: 10px;
        border: 1.5px solid #eee;
        background: #fff;
        font-size: 13px;
        font-weight: 600;
        color: #1a1a1a;
        cursor: pointer;
        outline: none;
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23aaa' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
        padding-right: 32px;
        transition: border-color 0.2s;
    }

    .filter-select:focus {
        border-color: #F8C61E;
    }

    .btn-export {
        margin-left: auto;
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 9px 20px;
        background: #1C1C1E;
        color: #fff;
        border: none;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s;
        text-decoration: none;
    }

    .btn-export:hover { background: #333; }

    /* ===== EXPORT DROPDOWN ===== */
    .export-dropdown {
        position: relative;
    }

    .export-menu {
        display: none;
        position: absolute;
        right: 0;
        top: 100%;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        min-width: 140px;
        z-index: 100;
        margin-top: 8px;
    }

    .export-menu.active {
        display: block;
    }

    .export-menu a {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        color: #1a1a1a;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        border: none;
        background: none;
        cursor: pointer;
        width: 100%;
        transition: background 0.2s;
    }

    .export-menu a:first-child {
        border-radius: 12px 12px 0 0;
    }

    .export-menu a:last-child {
        border-radius: 0 0 12px 12px;
    }

    .export-menu a:hover {
        background: #f5f3ef;
    }

    /* ===== SUMMARY CARDS ===== */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .summary-card {
        background: #fff;
        border-radius: 16px;
        padding: 22px 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .summary-card-label {
        font-size: 12px;
        color: #aaa;
        font-weight: 600;
    }

    .summary-card-value {
        font-size: 26px;
        font-weight: 900;
        color: #1a1a1a;
        letter-spacing: -0.8px;
        line-height: 1;
    }

    .summary-card-note {
        font-size: 11px;
        font-weight: 700;
        margin-top: 2px;
    }

    .note-up   { color: #4caf7d; }
    .note-down { color: #e05a5a; }
    .note-info { color: #4a9eff; }

    /* ===== MAIN CHART CARD ===== */
    .main-chart-card {
        background: #fff;
        border-radius: 20px;
        padding: 28px 28px 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        margin-bottom: 24px;
    }

    .chart-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 6px;
    }

    .chart-title {
        font-size: 18px;
        font-weight: 800;
        color: #1a1a1a;
        letter-spacing: -0.4px;
    }

    .chart-subtitle {
        font-size: 12px;
        color: #aaa;
        margin-top: 2px;
    }

    .chart-legend {
        display: flex;
        gap: 16px;
        align-items: center;
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
        width: 10px;
        height: 10px;
        border-radius: 3px;
    }

    .legend-dot.motor  { background: #F8C61E; }
    .legend-dot.mobil  { background: #1C1C1E; }
    .legend-dot.total  { background: #d0e8ff; }

    /* ===== BAR CHART ===== */
    .bar-chart-wrap {
        position: relative;
        margin-top: 20px;
    }

    /* Y-axis grid lines */
    .grid-lines {
        position: absolute;
        left: 0; right: 0; top: 0;
        height: 200px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        pointer-events: none;
    }

    .grid-line {
        width: 100%;
        height: 1px;
        background: #f0ede7;
    }

    /* Y labels */
    .y-labels {
        position: absolute;
        left: -44px;
        top: 0;
        height: 200px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: flex-end;
    }

    .y-label {
        font-size: 10px;
        color: #ccc;
        font-weight: 600;
        transform: translateY(50%);
    }

    .y-label:first-child { transform: translateY(0); }
    .y-label:last-child  { transform: translateY(100%); }

    .chart-inner {
        padding-left: 48px;
        padding-bottom: 32px;
        position: relative;
    }

    .bars-row {
        display: flex;
        align-items: flex-end;
        gap: 8px;
        height: 200px;
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
        border-radius: 5px 5px 0 0;
        min-height: 4px;
        transition: opacity 0.2s;
        cursor: pointer;
        position: relative;
    }

    .bar:hover { opacity: 0.8; }

    .bar.motor { background: #F8C61E; }
    .bar.mobil { background: #1C1C1E; }

    /* Tooltip on hover */
    .bar-tooltip {
        display: none;
        position: absolute;
        bottom: calc(100% + 6px);
        left: 50%;
        transform: translateX(-50%);
        background: #1C1C1E;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        padding: 5px 10px;
        border-radius: 8px;
        white-space: nowrap;
        z-index: 10;
        pointer-events: none;
    }

    .bar:hover .bar-tooltip { display: block; }

    .bar-month-label {
        position: absolute;
        bottom: -24px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 10px;
        color: #bbb;
        font-weight: 600;
        white-space: nowrap;
    }

    /* Active / highlighted bar group */
    .bar-group.highlight .bar.motor { background: #F8C61E; }
    .bar-group.highlight .bar-month-label { color: #1a1a1a; font-weight: 800; }

    /* ===== DETAIL TABLE ===== */
    .detail-card {
        background: #fff;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        margin-bottom: 24px;
    }

    .detail-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
    }

    .detail-card-title {
        font-size: 16px;
        font-weight: 800;
        color: #1a1a1a;
    }

    .detail-table {
        width: 100%;
        border-collapse: collapse;
    }

    .detail-table th {
        text-align: left;
        font-size: 11px;
        color: #aaa;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        padding: 0 12px 12px;
        border-bottom: 1px solid #f0ede7;
    }

    .detail-table td {
        padding: 14px 12px;
        font-size: 13px;
        color: #1a1a1a;
        font-weight: 600;
        border-bottom: 1px solid #f5f3ef;
    }

    .detail-table tr:last-child td { border-bottom: none; }

    .detail-table tr:hover td { background: #fafaf8; }

    .trend-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
    }

    .trend-badge.up   { background: #e8f8ef; color: #4caf7d; }
    .trend-badge.down { background: #fdf0f0; color: #e05a5a; }
    .trend-badge.flat { background: #f0f0f0; color: #888; }

    .month-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 2px;
        background: #F8C61E;
        margin-right: 8px;
        flex-shrink: 0;
    }

    /* ===== BOTTOM GRID (area breakdown) ===== */
    .area-breakdown-card {
        background: #fff;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .area-breakdown-header {
        font-size: 16px;
        font-weight: 800;
        color: #1a1a1a;
        margin-bottom: 18px;
    }

    .area-row {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 12px 0;
        border-bottom: 1px solid #f5f3ef;
    }

    .area-row:last-child { border-bottom: none; }

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

    .area-rank.gold { background: #F8C61E; }

    .area-name {
        font-size: 13px;
        font-weight: 800;
        color: #1a1a1a;
        min-width: 120px;
    }

    .area-bar-wrap {
        flex: 1;
    }

    .area-bar-bg {
        height: 6px;
        background: #ede9e0;
        border-radius: 10px;
        overflow: hidden;
    }

    .area-bar-fill      { height: 100%; border-radius: 10px; background: #1C1C1E; }
    .area-bar-fill.gold { background: #F8C61E; }

    .area-value {
        font-size: 13px;
        font-weight: 700;
        color: #1a1a1a;
        white-space: nowrap;
        min-width: 90px;
        text-align: right;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1000px) {
        .summary-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 700px) {
        .summary-grid { grid-template-columns: 1fr; }
        .filter-bar   { flex-direction: column; align-items: flex-start; }
        .btn-export   { margin-left: 0; }
        .greeting h1  { font-size: 26px; }
    }
</style>

{{-- TOP HEADER --}}
<div class="top-header">
    <span></span>
    <span class="owner-panel-badge">OWNER PANEL</span>
</div>

{{-- GREETING --}}
<div class="greeting">
    <h1>Grafik Pendapatan</h1>
    <p>Trend pendapatan bulanan {{ $tahun ?? date('Y') }} – Kota Malang</p>
</div>

{{-- FILTER BAR --}}
<div class="filter-bar">
    <span class="filter-label">FILTER</span>

    <form method="GET" action="{{ route('owner.grafik-pendapatan') }}" style="display:contents;">
        <select name="tahun" class="filter-select" onchange="this.form.submit()">
            @foreach($tahunList ?? [date('Y'), date('Y')-1, date('Y')-2] as $t)
                <option value="{{ $t }}" {{ ($tahun ?? date('Y')) == $t ? 'selected' : '' }}>{{ $t }}</option>
            @endforeach
        </select>

        <select name="area_id" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Area</option>
            @foreach($areaList ?? [] as $area)
                <option value="{{ $area['id'] }}" {{ ($selectedArea ?? '') == $area['id'] ? 'selected' : '' }}>
                    {{ $area['nama'] }}
                </option>
            @endforeach
        </select>
    </form>

    <a href="{{ route('owner.grafik-pendapatan', array_merge(request()->query(), ['export' => 'xlsx'])) }}"
       class="btn-export">
        <i class="fa-solid fa-arrow-up-from-bracket"></i> Export Excel
    </a>

</div>

{{-- SUMMARY CARDS --}}
<div class="summary-grid">
    <div class="summary-card">
        <div class="summary-card-label">Total Pendapatan {{ $tahun ?? date('Y') }}</div>
        <div class="summary-card-value">{{ $totalTahunFormatted ?? 'Rp 0' }}</div>
        <div class="summary-card-note {{ isset($trendTahunan) && $trendTahunan >= 0 ? 'note-up' : 'note-down' }}">
            {{ isset($trendTahunan) ? ($trendTahunan >= 0 ? '▲' : '▼') . ' ' . abs($trendTahunan) . '%' : '▲ 0%' }}
            vs tahun lalu
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-card-label">Bulan Tertinggi</div>
        <div class="summary-card-value">{{ $bulanTertinggi ?? '-' }}</div>
        <div class="summary-card-note note-up">{{ $pendapatanBulanTertinggiFormatted ?? 'Rp 0' }}</div>
    </div>
    <div class="summary-card">
        <div class="summary-card-label">Rata-rata per Bulan</div>
        <div class="summary-card-value">{{ $rataRataBulanFormatted ?? 'Rp 0' }}</div>
        <div class="summary-card-note note-info">Dari {{ $jumlahBulanData ?? 0 }} bulan data</div>
    </div>
</div>

{{-- MAIN CHART --}}
<div class="main-chart-card">
    <div class="chart-header">
        <div>
            <div class="chart-title">Pendapatan per Bulan {{ $tahun ?? date('Y') }}</div>
            <div class="chart-subtitle">dalam jutaan rupiah – semua lokasi</div>
        </div>
        <div class="chart-legend">
            <div class="legend-item"><div class="legend-dot motor"></div> Motor</div>
            <div class="legend-item"><div class="legend-dot mobil"></div> Mobil</div>
        </div>
    </div>

    <div class="chart-inner">
        @php
            $bulanLabels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            $grafikData  = $grafikBulanan ?? array_fill(0, 12, ['motor' => 0, 'mobil' => 0]);
            $maxGrafik   = collect($grafikData)
                ->map(fn($d) => ($d['motor'] ?? 0) + ($d['mobil'] ?? 0))
                ->max() ?: 1;
            // Y-axis labels (in jutaan)
            $yMax   = ceil($maxGrafik / 1000000);
            $yStep  = max(1, ceil($yMax / 4));
            $yLabels = [];
            for ($yi = 4; $yi >= 0; $yi--) {
                $yLabels[] = ($yi * $yStep) . 'jt';
            }
            $yMaxVal = $yStep * 4 * 1000000;
        @endphp

        <div class="bar-chart-wrap">
            {{-- Grid lines --}}
            <div class="grid-lines">
                @foreach($yLabels as $yl)
                    <div class="grid-line"></div>
                @endforeach
            </div>

            {{-- Y labels --}}
            <div class="y-labels" style="left: 0;">
                @foreach($yLabels as $yl)
                    <span class="y-label">{{ $yl }}</span>
                @endforeach
            </div>

            <div class="bars-row">
                @foreach($grafikData as $i => $d)
                    @php
                        $motorH = $yMaxVal > 0 ? round(($d['motor'] ?? 0) / $yMaxVal * 200) : 0;
                        $mobilH = $yMaxVal > 0 ? round(($d['mobil'] ?? 0) / $yMaxVal * 200) : 0;
                        $motorJt = number_format(($d['motor'] ?? 0) / 1000000, 1);
                        $mobilJt = number_format(($d['mobil'] ?? 0) / 1000000, 1);
                        $bulanNow = (int)date('n') - 1;
                        $isHighlight = $i === $bulanNow;
                    @endphp

                    <div class="bar-group {{ $isHighlight ? 'highlight' : '' }}">
                        <div class="bar motor" style="height: {{ max($motorH, 4) }}px;">
                            <div class="bar-tooltip">
                                Motor: {{ $motorJt }}jt
                            </div>
                        </div>

                        <div class="bar mobil" style="height: {{ max($mobilH, 4) }}px;">
                            <div class="bar-tooltip">
                                Mobil: {{ $mobilJt }}jt
                            </div>
                        </div>

                        <span class="bar-month-label">
                            {{ $bulanLabels[$i] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- DETAIL TABLE --}}
<div class="detail-card">
    <div class="detail-card-header">
        <div class="detail-card-title">Rincian per Bulan</div>
    </div>
    <table class="detail-table">
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Motor</th>
                <th>Mobil</th>
                <th>Total</th>
                <th>vs Bulan Lalu</th>
            </tr>
        </thead>
        <tbody>
            @forelse($detailBulanan ?? [] as $i => $row)
                @php
                    $trendClass = ($row['trend'] ?? 0) > 0 ? 'up' : (($row['trend'] ?? 0) < 0 ? 'down' : 'flat');
                    $trendIcon  = ($row['trend'] ?? 0) > 0 ? '▲' : (($row['trend'] ?? 0) < 0 ? '▼' : '–');
                    $tahun = $tahun ?? date('Y');
                    $tanggalBulan = \Carbon\Carbon::createFromDate($tahun, $i + 1, 1)->format('d M Y');
                @endphp
                <tr>
                    <td>
                        <span class="month-dot"></span>
                        {{ $tanggalBulan }}
                    </td>
                    <td>{{ $row['motorFormatted'] ?? 'Rp 0' }}</td>
                    <td>{{ $row['mobilFormatted'] ?? 'Rp 0' }}</td>
                    <td style="font-weight:800;">{{ $row['totalFormatted'] ?? 'Rp 0' }}</td>
                    <td>
                        <span class="trend-badge {{ $trendClass }}">
                            {{ $trendIcon }} {{ abs($row['trend'] ?? 0) }}%
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center; color:#bbb; padding: 32px 0;">
                        Belum ada data.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- AREA BREAKDOWN --}}
<div class="area-breakdown-card">
    <div class="area-breakdown-header">Pendapatan per Area – {{ $tahun ?? date('Y') }}</div>

    @forelse($pendapatanPerArea ?? [] as $i => $area)
        @php
            $maxArea  = max(1, collect($pendapatanPerArea ?? [])->max(fn($a) => $a['total'] ?? 0) ?: 1);
            $barPct   = round(($area['total'] ?? 0) / $maxArea * 100);
            $isFirst  = $i === 0;
        @endphp
        <div class="area-row">
            <div class="area-rank {{ $isFirst ? 'gold' : '' }}">{{ $i + 1 }}</div>
            <div class="area-name">{{ $area['nama'] ?? 'Area' }}</div>
            <div class="area-bar-wrap">
                <div class="area-bar-bg">
                   <div class="area-bar-fill {{ $isFirst ? 'gold' : '' }}" 
     style="--w: {{ $barPct }}%"></div>
                </div>
            </div>
            <div class="area-value">{{ $area['totalFormatted'] ?? 'Rp 0' }}</div>
        </div>
    @empty
        <div style="text-align:center; color:#bbb; padding: 32px 0; font-size:13px;">
            Belum ada data area.
        </div>
    @endforelse
</div>

@endsection