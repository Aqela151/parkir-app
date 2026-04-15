@extends('layouts.app')

@section('title', 'Log Aktivitas')
@section('page-title', 'Log Aktivitas')

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

    .page-heading h1 {
        font-size: 36px;
        font-weight: 900;
        color: #1a1a1a;
        letter-spacing: -1px;
        margin-bottom: 6px;
    }

    .page-heading p {
        font-size: 13px;
        color: #aaa;
        margin-bottom: 28px;
    }

    /* TOOLBAR */
    .toolbar {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
    }

    .search-box {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #fff;
        border: 1.5px solid #e8e4dc;
        border-radius: 30px;
        padding: 10px 18px;
        flex: 1;
        max-width: 340px;
    }

    .search-box input {
        border: none;
        outline: none;
        font-size: 13px;
        color: #aaa;
        background: transparent;
        width: 100%;
    }

    .search-box svg { flex-shrink: 0; }

    .btn-export {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #1C1C1E;
        color: #fff;
        border: none;
        border-radius: 30px;
        padding: 10px 22px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.15s;
    }

    .btn-export:hover { background: #333; }

    /* TABLE CARD */
    .table-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .table-card table {
        width: 100%;
        border-collapse: collapse;
    }

    .table-card thead tr {
        background: #1C1C1E;
    }

    .table-card thead th {
        padding: 14px 20px;
        font-size: 11px;
        font-weight: 700;
        color: #fff;
        letter-spacing: 0.5px;
        text-align: left;
    }

    .table-card tbody tr {
        border-bottom: 1px solid #f0ede8;
        transition: background 0.12s;
    }

    .table-card tbody tr:last-child { border-bottom: none; }
    .table-card tbody tr:hover { background: #fafaf8; }

    .table-card tbody td {
        padding: 16px 20px;
        font-size: 13px;
        color: #666;
    }

    .td-num {
        color: #bbb;
        font-weight: 600;
        width: 40px;
    }

    .td-user {
        font-weight: 700;
        color: #1a1a1a;
    }

    .td-waktu {
        color: #aaa;
        font-size: 12px;
    }

    .td-lokasi {
        color: #aaa;
    }

    .td-aktivitas {
        color: #999;
        font-size: 12px;
    }

    .role-badge {
        display: inline-block;
        font-size: 11px;
        font-weight: 600;
        padding: 3px 12px;
        border-radius: 20px;
        border: 1.5px solid #d0ccc4;
        color: #555;
        background: #fff;
    }

    .role-badge.admin {
        border-color: #e8c84a;
        color: #a07c00;
        background: rgba(248,198,30,0.10);
    }

    .role-badge.owner {
        border-color: #1C1C1E;
        color: #1a1a1a;
        background: rgba(28,28,30,0.08);
    }

    @media (max-width: 768px) {
        .toolbar { flex-direction: column; align-items: stretch; }
        .search-box { max-width: 100%; }
        .table-card { overflow-x: auto; }
    }
</style>

<div class="top-header">
    <span></span>
    <span class="admin-panel-badge">ADMIN PANEL</span>
</div>

<div class="page-heading">
    <h1>Log Aktivitas</h1>
    <p>Rekam jejak seluruh aktivitas pengguna sitem</p>
</div>

<div class="toolbar">
    <div class="search-box">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#bbb" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input type="text" id="searchInput" placeholder="Cari log...">
    </div>
    <button class="btn-export">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
            <polyline points="17 8 12 3 7 8"/>
            <line x1="12" y1="3" x2="12" y2="15"/>
        </svg>
        Export
    </button>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>WAKTU</th>
                <th>USER</th>
                <th>LOKASI</th>
                <th>ROLE</th>
                <th>AKTIVITAS</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            @forelse($logs ?? [] as $index => $log)
                <tr>
                    <td class="td-num">{{ $index + 1 }}</td>
                    <td class="td-waktu">{{ $log->created_at->format('d M H:i') }}</td>
                    <td class="td-user">{{ $log->user->name ?? '-' }}</td>
                    <td class="td-lokasi">{{ $log->lokasi ?? '-' }}</td>
                    <td>
                        <span class="role-badge {{ strtolower($log->user->role ?? '') }}">
                            {{ ucfirst($log->user->role ?? '-') }}
                        </span>
                    </td>
                    <td class="td-aktivitas">{{ $log->aktivitas }}</td>
                </tr>
            @empty
                {{-- DUMMY DATA --}}
                @php
                    $dummy = [
                        ['no' => 1, 'waktu' => '09 Apr 14:32', 'user' => 'Aqela',  'lokasi' => 'MOG',   'role' => 'Petugas', 'aktivitas' => 'Login ke sistem'],
                        ['no' => 2, 'waktu' => '09 Apr 14.28', 'user' => 'Qela',   'lokasi' => '-',     'role' => 'Admin',   'aktivitas' => 'Tambah tarif Bus-Rp 5.000/jam'],
                        ['no' => 3, 'waktu' => '09 Apr 14.20', 'user' => 'Qey',    'lokasi' => 'Matos', 'role' => 'Petugas', 'aktivitas' => 'Login ke sistem'],
                        ['no' => 4, 'waktu' => '09 Apr 14.15', 'user' => 'Nisa',   'lokasi' => '-',     'role' => 'Admin',   'aktivitas' => 'Update kapasitas Matos'],
                        ['no' => 5, 'waktu' => '09 Apr 13.58', 'user' => 'Niisaa', 'lokasi' => 'Plaza', 'role' => 'Petugas', 'aktivitas' => 'Logout dari sistem'],
                    ];
                @endphp
                @foreach($dummy as $row)
                    <tr>
                        <td class="td-num">{{ $row['no'] }}</td>
                        <td class="td-waktu">{{ $row['waktu'] }}</td>
                        <td class="td-user">{{ $row['user'] }}</td>
                        <td class="td-lokasi">{{ $row['lokasi'] }}</td>
                        <td>
                            <span class="role-badge {{ strtolower($row['role']) }}">
                                {{ $row['role'] }}
                            </span>
                        </td>
                        <td class="td-aktivitas">{{ $row['aktivitas'] }}</td>
                    </tr>
                @endforeach
            @endforelse
        </tbody>
    </table>
</div>

<script>
    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('#tableBody tr').forEach(row => {
            const user = row.querySelector('.td-user');
            const aktivitas = row.querySelector('.td-aktivitas');
            const lokasi = row.querySelector('.td-lokasi');
            
            const searchableText = (user?.textContent || '') + ' ' + 
                                 (aktivitas?.textContent || '') + ' ' + 
                                 (lokasi?.textContent || '');
            
            if (searchableText.toLowerCase().includes(q)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>

@endsection