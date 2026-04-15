<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ParkSys - @yield('title', 'Dashboard')</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --sidebar-bg: #1C1C1E;
            --main-bg: #F5F0E8;
            --accent: #F8C61E;
            --border: #2a2a2a;
            --sidebar-width: 240px;
        }

        body {
            font-family: 'Manrope', sans-serif;
            background-color: var(--main-bg);
            color: #1a1a1a;
            display: flex;
            min-height: 100vh;
        }

        .main-wrapper {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: var(--main-bg);
        }

        /* TOPBAR */
        .topbar {
            background-color: var(--main-bg);
            border-bottom: 1px solid #e0dbd0;
            padding: 14px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-title {
            font-size: 15px;
            font-weight: 600;
            color: #555;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .topbar-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            color: #1a1a1a;
            flex-shrink: 0;
        }

        .topbar-username {
            font-size: 13px;
            font-weight: 600;
            color: #1a1a1a;
        }

        .topbar-role {
            font-size: 11px;
            color: #999;
        }

        .btn-logout {
            display: flex;
            align-items: center;
            gap: 6px;
            background: transparent;
            border: 1px solid #d0ccc4;
            color: #888;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            font-family: 'Manrope', sans-serif;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-logout:hover {
            border-color: #ff5555;
            color: #ff5555;
            background: rgba(255,85,85,0.06);
        }

        .content {
            padding: 28px;
            flex: 1;
        }
    </style>

    @stack('styles')
</head>
<body>

    {{-- SIDEBAR SESUAI ROLE --}}
    @auth
        @include('components.sidebar.' . Auth::user()->role)
    @endauth

    <div class="main-wrapper">

        @auth
        <header class="topbar">
            <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
            <div class="topbar-right">
                <div class="topbar-user">
                    <div class="topbar-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="topbar-username">{{ Auth::user()->name }}</div>
                        <div class="topbar-role">{{ ucfirst(Auth::user()->role) }}</div>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                    </button>
                </form>
            </div>
        </header>
        @endauth

        <main class="content">
            @yield('content')
        </main>

    </div>

    @stack('scripts')
</body>
</html>