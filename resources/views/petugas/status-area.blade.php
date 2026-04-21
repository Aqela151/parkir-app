@extends('layouts.app')

@section('title', 'Status Area')
@section('page-title', 'Status Area')

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

    .progress-wrap {
        width: 100%;
        height: 8px;
        background: #e8e2d6;
        border-radius: 99px;
        overflow: hidden;
        margin-bottom: 12px;
    }

    .progress-bar          { height: 100%; border-radius: 99px; }
    .bar-dark              { background: #1C1C1E; }
    .bar-gold              { background: #F8C61E; }
    .bar-grey              { background: #ccc; }

    .stats-row {
        display: flex;
        justify-content: space-between;
    }

    .stat-lbl {
        font-size: 12px;
        font-weight: 500;
        color: #aaa49a;
    }

    @media (max-width: 900px) {
        .area-grid { grid-template-columns: 1fr; }
    }
</style>

{{-- TOP HEADER --}}
<div class="top-header">
    <span></span>
    <span class="petugas-panel-badge">PETUGAS PANEL</span>
</div>

{{-- HEADING --}}
<h1 class="page-heading">Status Area</h1>
<p class="page-subtitle">Status Real-time Hari Ini</p>

{{-- GRID --}}
<div class="area-grid">
    @forelse ($statusArea as $area)
        @php
            $pct = ($area['kapasitas'] ?? 0) > 0
                ? round(($area['terisi'] / $area['kapasitas']) * 100)
                : null;

            if ($pct === null)   { $barClass = 'bar-grey';  $badgeType = 'toggle'; }
            elseif ($pct >= 75)  { $barClass = 'bar-dark';  $badgeType = 'dark'; }
            else                 { $barClass = 'bar-gold';  $badgeType = 'yellow'; }
        @endphp

        <div class="area-card">
            <div class="card-header">
                <span class="card-name">{{ $area['nama'] }}</span>

                @if ($badgeType === 'toggle')
                    <span class="toggle-badge"></span>
                @else
                    <span class="pct-badge {{ $badgeType }}">{{ $pct }}%</span>
                @endif
            </div>

            <p class="card-address">{{ $area['alamat'] ?? '' }}</p>

            <div class="progress-wrap">
                <div class="progress-bar {{ $barClass }}"
                     style="width: {{ $pct !== null ? $pct : 0 }}%"></div>
            </div>

            <div class="stats-row">
                @if ($pct !== null)
                    <span class="stat-lbl">{{ $area['terisi'] }} terisi</span>
                    <span class="stat-lbl">{{ $area['kapasitas'] - $area['terisi'] }} tersedia</span>
                @else
                    <span class="stat-lbl"></span>
                @endif
                <span class="stat-lbl">{{ $area['kapasitas'] }} total</span>
            </div>
        </div>

    @empty
        <div class="area-card" style="grid-column:1/-1; text-align:center; color:#bbb; padding:48px 20px; font-size:13px;">
            Belum ada data area parkir.
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
                updateCardDisplay(data);
            })
            .catch(error => {
                console.error('Error updating status area:', error);
            });
    }

    /**
     * Update display area cards berdasarkan data API
     */
    function updateCardDisplay(data) {
        const areaCards = document.querySelectorAll('.area-card');

        data.forEach((area, index) => {
            if (areaCards[index]) {
                const card = areaCards[index];
                const pct = area.kapasitas > 0 
                    ? Math.round((area.terisi / area.kapasitas) * 100) 
                    : 0;

                let barClass = 'bar-grey';
                let badgeType = 'toggle';

                if (pct >= 75) {
                    barClass = 'bar-dark';
                    badgeType = 'dark';
                } else if (pct > 0) {
                    barClass = 'bar-gold';
                    badgeType = 'yellow';
                }

                // Update badge
                const badge = card.querySelector('.pct-badge');
                if (badge) {
                    badge.className = `pct-badge ${badgeType}`;
                    badge.textContent = `${pct}%`;
                }

                // Update progress bar
                const progressBar = card.querySelector('.progress-bar');
                if (progressBar) {
                    progressBar.className = `progress-bar ${barClass}`;
                    progressBar.style.width = `${pct}%`;
                }

                // Update stats
                const statLbls = card.querySelectorAll('.stat-lbl');
                if (statLbls.length >= 3) {
                    statLbls[0].textContent = `${area.terisi} terisi`;
                    statLbls[1].textContent = `${area.kapasitas - area.terisi} tersedia`;
                    statLbls[2].textContent = `${area.kapasitas} total`;
                }
            }
        });
    }

})();
</script>