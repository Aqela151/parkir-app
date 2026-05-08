@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')

<style>
    .top-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
    }

    .admin-panel-badge {
        border: 1.5px solid #F8C61E;
        color: #F8C61E;
        border-radius: 20px;
        padding: 4px 16px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        background: rgba(248,198,30,0.06);
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

    /* STATS */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 36px;
    }

    .stat-card {
        background: #fff;
        border-radius: 16px;
        padding: 20px;
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
        font-size: 28px;
        font-weight: 900;
        color: #1a1a1a;
        margin-bottom: 4px;
        letter-spacing: -0.5px;
    }

    .stat-label {
        font-size: 12px;
        color: #aaa;
        margin-bottom: 8px;
    }

    .stat-sub { font-size: 11px; font-weight: 700; }
    .stat-sub.green  { color: #4caf7d; }
    .stat-sub.grey   { color: #888; }
    .stat-sub.yellow { color: #F8C61E; }

    /* BOTTOM */
    .bottom-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

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

    /* AREA CARD */
    .area-card, .log-card {
        background: #fff;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .area-item {
        padding: 14px 0;
        border-bottom: 1px solid #f0ede8;
    }

    .area-item:last-child { border-bottom: none; }

    .area-item-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 3px;
    }

    .area-item-name {
        font-size: 13px;
        font-weight: 700;
        color: #1a1a1a;
    }

    .area-item-addr {
        font-size: 11px;
        color: #bbb;
        margin-bottom: 10px;
    }

    .area-pct-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
        flex-shrink: 0;
    }

    .area-pct-badge.dark   { background: #1C1C1E; color: #fff; }
    .area-pct-badge.yellow { background: #F8C61E; color: #1a1a1a; }

    .area-bar-bg {
        height: 7px;
        background: #ede9e0;
        border-radius: 10px;
        margin-bottom: 6px;
        overflow: hidden;
    }

    .area-bar-fill { height: 100%; border-radius: 10px; background: #1C1C1E; }
    .area-bar-fill.yellow { background: #F8C61E; }

    .area-slot { font-size: 11px; color: #bbb; }

    /* LOG */
    .log-item {
        display: flex;
        align-items: baseline;
        gap: 14px;
        padding: 13px 0;
        border-bottom: 1px solid #f0ede8;
        font-size: 13px;
    }

    .log-item:last-child { border-bottom: none; }

    .log-time { color: #bbb; font-size: 12px; font-weight: 600; min-width: 38px; }
    .log-text { color: #666; }
    .log-text .actor { color: #F8C61E; font-weight: 700; }

    @media (max-width: 1000px) {
        .stats-grid { grid-template-columns: repeat(2,1fr); }
        .bottom-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="top-header">
    <span></span>
    <span class="admin-panel-badge">ADMIN PANEL</span>
</div>

<div class="greeting">
    <h1>Selamat Datang, Admin</h1>
    <p>Ringkasan sistem parkir hari ini</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#F8C61E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="1" y="11" width="22" height="7" rx="2"/>
                <path d="M6 11V7a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v4"/>
                <circle cx="8" cy="18" r="2"/><circle cx="16" cy="18" r="2"/>
            </svg>
        </div>
        <div class="stat-value">{{ $kendaraanCount }}</div>
        <div class="stat-label">Total kendaraan terdaftar</div>
        <div class="stat-sub green">Jumlah kendaraan pada sistem</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#F8C61E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <path d="M9 12h3.5a2 2 0 1 0 0-4H9v8"/>
            </svg>
        </div>
        <div class="stat-value">{{ $areaCount }}</div>
        <div class="stat-label">Area parkir aktif</div>
        <div class="stat-sub green">Area yang saat ini berstatus aktif</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#F8C61E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
            </svg>
        </div>
        <div class="stat-value">{{ $userCount }}</div>
        <div class="stat-label">Total pengguna sistem</div>
        <div class="stat-sub grey">Admin, petugas, owner terdaftar</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon-box">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#F8C61E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="12 6 12 12 16 14"/>
            </svg>
        </div>
        <div class="stat-value">{{ $tarifCount }}</div>
        <div class="stat-label">Tarif terkonfigurasi</div>
        <div class="stat-sub yellow">Jumlah tarif parkir di database</div>
    </div>
</div>

<div class="bottom-grid">
    <div>
        <div class="section-title">Area Parkir</div>
        <div class="section-sub">Status kapasitas real-time</div>
        <div class="area-card">
            @forelse ($areaCards as $area)
                @php
                    $totalCapacity = $area->kapasitas;
                    $terisi = $area->terisi ?? 0;
                    $persen = $totalCapacity > 0 ? ($terisi / $totalCapacity) * 100 : 0;
                    $badgeClass = $area->status === 'aktif' ? 'dark' : 'yellow';
                @endphp
                <div class="area-item">
                    <div class="area-item-top">
                        <div>
                            <div class="area-item-name">{{ $area->nama_area }}</div>
                            <div class="area-item-addr">{{ $area->lokasi ?? 'Lokasi belum diisi' }}</div>
                        </div>
                        <span class="area-pct-badge {{ $badgeClass }}">{{ ucfirst($area->status) }}</span>
                    </div>
                    @if ($area->status === 'penuh')
                        <div class="area-bar-bg"><div class="area-bar-fill yellow" style="width: 100%"></div></div>
                    @elseif ($area->status === 'aktif')
                        <div class="area-bar-bg"><div class="area-bar-fill" style="width: {{ $persen }}%"></div></div>
                    @else
                        <div class="area-bar-bg"><div class="area-bar-fill yellow" style="width: {{ $persen }}%"></div></div>
                    @endif
                    <div class="area-slot">Terisi {{ $terisi }} / {{ $totalCapacity }} slot</div>
                </div>
            @empty
                <div class="area-item">
                    <div class="area-item-top">
                        <div>
                            <div class="area-item-name">Belum ada area parkir</div>
                            <div class="area-item-addr">Tambahkan area baru di menu Area Parkir.</div>
                        </div>
                        <span class="area-pct-badge yellow">Kosong</span>
                    </div>
                    <div class="area-bar-bg"><div class="area-bar-fill yellow" style="width:30%"></div></div>
                    <div class="area-slot">Tambahkan area untuk menampilkan data di dashboard.</div>
                </div>
            @endforelse
        </div>
    </div>

    <div>
        <div class="section-title">Log Terbaru</div>
        <div class="section-sub">Aktivitas 1 jam terakhir</div>
        <div class="log-card">
            @forelse ($logAktivitas as $log)
                <div class="log-item">
                    <span class="log-time">{{ $log->created_at->format('H.i') }}</span>
                    <span class="log-text">
                        <span class="actor">{{ $log->user->name }} ({{ $log->user->penempatan ?? 'SYS' }})</span> 
                        {{ $log->aktivitas }}
                    </span>
                </div>
            @empty
                <div class="log-item">
                    <div class="log-text" style="color: #aaa;">Belum ada aktivitas dalam 1 jam terakhir</div>
                </div>
            @endforelse
        </div>
    </div>
</div>

@endsection

<script>
(function() {
    'use strict';

    const API_URL = '{{ route("admin.api.status-area") }}';
    const UPDATE_INTERVAL = 5000; // 5 detik untuk update lebih cepat
    let lastData = null;

    // Inisialisasi saat DOM siap
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Admin Dashboard initialized');
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
                console.log('API Response:', data);
                if (data && data.length > 0) {
                    updateAreaDisplay(data);
                    lastData = data;
                }
            })
            .catch(error => {
                console.error('Error updating status area:', error);
            });
    }

    /**
     * Update display area items berdasarkan data API
     */
    function updateAreaDisplay(data) {
        const areaCard = document.querySelector('.area-card');
        if (!areaCard) {
            console.log('Area card not found');
            return;
        }

        const areaItems = areaCard.querySelectorAll('.area-item');
        console.log(`Found ${areaItems.length} area items, updating with ${data.length} items from API`);

        data.forEach((area, index) => {
            if (areaItems[index]) {
                const item = areaItems[index];
                console.log(`Updating area ${index}:`, area);

                const pct = area.kapasitas > 0 
                    ? Math.round((area.terisi / area.kapasitas) * 100) 
                    : 0;

                // Determine status dan badge class
                let isFull = area.terisi >= area.kapasitas;
                let badgeClass = isFull ? 'yellow' : 'dark';
                let badgeText = isFull ? 'Penuh' : 'Aktif';
                let barClass = isFull ? 'yellow' : 'dark';

                // Update badge
                const badge = item.querySelector('.area-pct-badge');
                if (badge) {
                    badge.className = `area-pct-badge ${badgeClass}`;
                    badge.textContent = badgeText;
                    console.log(`Updated badge to: ${badgeText}`);
                }

                // Update progress bar container
                const barBg = item.querySelector('.area-bar-bg');
                if (barBg) {
                    // Remove existing bar-fill
                    const existingFill = barBg.querySelector('.area-bar-fill');
                    if (existingFill) {
                        existingFill.remove();
                    }

                    // Create new bar-fill dengan class yang tepat
                    const barFill = document.createElement('div');
                    barFill.className = `area-bar-fill ${barClass}`;
                    barFill.style.width = `${pct}%`;
                    barBg.appendChild(barFill);
                    console.log(`Updated bar width to: ${pct}%, class: ${barClass}`);
                }

                // Update slot text
                const slot = item.querySelector('.area-slot');
                if (slot) {
                    slot.textContent = `Terisi ${area.terisi} / ${area.kapasitas} slot`;
                    console.log(`Updated slot to: ${area.terisi}/${area.kapasitas}`);
                }
            }
        });
    }

})();
</script>