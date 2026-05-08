{{-- components/sidebar/owner.blade.php --}}

<style>
    .sidebar {
        width: 240px;
        background-color: #1C1C1E;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        position: fixed;
        left: 0; top: 0; bottom: 0;
        border-right: 1px solid #2a2a2a;
        font-family: 'Manrope', sans-serif;
    }

    .sidebar-header { padding: 28px 24px 20px; }

    .sidebar-logo {
        margin-bottom: 12px;
    }

    .sidebar-logo img {
        width: 100%;
        height: auto;
        max-height: 40px;
        object-fit: contain;
    }

    .role-badge {
        display: inline-block;
        padding: 3px 12px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1.2px;
        border: 1.5px solid #b084f5;
        color: #b084f5;
        background: rgba(176,132,245,0.08);
    }

    .sidebar-divider { height: 1px; background: #2a2a2a; }

    .sidebar-nav {
        flex: 1;
        padding: 8px 12px;
        display: flex;
        flex-direction: column;
    }

    .nav-section-label {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1.5px;
        color: #555;
        text-transform: uppercase;
        padding: 16px 12px 8px;
    }

    .nav-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 12px;
        border-radius: 8px;
        color: #888;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s ease;
        margin-bottom: 2px;
    }

    .nav-item i { width: 18px; text-align: center; font-size: 15px; flex-shrink: 0; }

    .nav-item:hover { background-color: #2a2a2a; color: #fff; }

    .nav-item.active {
        background-color: rgba(248,198,30,0.1);
        color: #F8C61E;
        font-weight: 600;
    }

    .nav-item.active i { color: #F8C61E; }
</style>

<aside class="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <img src="{{ asset('assets/images/ParkSys.png') }}" alt="ParkSys Logo">
        </div>
        <span class="role-badge">OWNER</span>
    </div>

    <div class="sidebar-divider"></div>

    <nav class="sidebar-nav">
        <span class="nav-section-label">Laporan</span>
        <a href="{{ route('owner.dashboard') }}" class="nav-item {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-border-all"></i> Dashboard
        </a>
        <a href="{{ route('owner.rekap-transaksi') }}" class="nav-item {{ request()->routeIs('owner.rekap-transaksi*') ? 'active' : '' }}">
            <i class="fa-regular fa-file-lines"></i> Rekap Transaksi
        </a>
        <a href="{{ route('owner.grafik-pendapatan') }}" class="nav-item {{ request()->routeIs('owner.grafik-pendapatan*') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-line"></i> Grafik Pendapatan
        </a>
        <a href="{{ route('owner.performa-area') }}" class="nav-item {{ request()->routeIs('owner.performa-area*') ? 'active' : '' }}">
            <i class="fa-regular fa-clock"></i> Performa Area
        </a>
    </nav>
</aside>