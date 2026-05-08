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
        transition: all 0.2s;
    }

    .search-box:focus-within {
        border-color: #F8C61E;
        box-shadow: 0 0 0 3px rgba(248,198,30,0.1);
    }

    .search-box input {
        border: none;
        outline: none;
        font-size: 13px;
        color: #1a1a1a;
        background: transparent;
        width: 100%;
    }

    .search-box input::placeholder { color: #bbb; }
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
        transition: all 0.2s;
    }

    .btn-export:hover { 
        background: #333;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    /* TABLE CARD */
    .table-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        overflow: hidden;
        border: 1px solid #f0ede8;
    }

    .table-card table {
        width: 100%;
        border-collapse: collapse;
    }

    .table-card thead tr {
        background: linear-gradient(135deg, #1C1C1E 0%, #2a2a2d 100%);
    }

    .table-card thead th {
        padding: 16px 20px;
        font-size: 11px;
        font-weight: 700;
        color: #fff;
        letter-spacing: 0.8px;
        text-align: left;
        text-transform: uppercase;
    }

    .table-card tbody tr {
        border-bottom: 1px solid #f5f2ed;
        transition: all 0.12s;
    }

    .table-card tbody tr:last-child { border-bottom: none; }
    .table-card tbody tr:hover { 
        background: #fafaf8;
        box-shadow: inset 0 0 0 1px #f0ede8;
    }

    .table-card tbody td {
        padding: 16px 20px;
        font-size: 13px;
        color: #555;
        vertical-align: middle;
    }

    .td-num {
        color: #bbb;
        font-weight: 600;
        width: 40px;
    }

    .td-user {
        font-weight: 700;
        color: #1a1a1a;
        font-size: 14px;
    }

    .td-waktu {
        color: #999;
        font-size: 12px;
        font-weight: 500;
    }

    .td-lokasi {
        color: #888;
        font-size: 12px;
        max-width: 250px;
    }

    .td-aktivitas {
        color: #666;
        font-size: 12px;
        max-width: 300px;
    }

    /* ROLE BADGES */
    .role-badge {
        display: inline-block;
        font-size: 11px;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 20px;
        letter-spacing: 0.3px;
        white-space: nowrap;
    }

    .role-badge.admin {
        background: linear-gradient(135deg, rgba(255,152,0,0.1), rgba(255,193,7,0.1));
        color: #e67e22;
        border: 1.5px solid #ffe0b2;
    }

    .role-badge.petugas {
        background: linear-gradient(135deg, rgba(59,130,246,0.1), rgba(96,165,250,0.1));
        color: #3b82f6;
        border: 1.5px solid #bfdbfe;
    }

    .role-badge.owner {
        background: linear-gradient(135deg, rgba(76,175,125,0.1), rgba(129,199,132,0.1));
        color: #2e7d32;
        border: 1.5px solid #c8e6c9;
    }

    /* STATUS BADGES */
    .status-badge {
        display: inline-block;
        font-size: 11px;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 20px;
        letter-spacing: 0.3px;
    }

    .status-badge.aktif {
        background: linear-gradient(135deg, rgba(76,175,125,0.15), rgba(129,199,132,0.15));
        color: #2e7d32;
        border: 1px solid #c8e6c9;
    }

    .status-badge.draft {
        background: linear-gradient(135deg, rgba(255,193,7,0.15), rgba(255,213,79,0.15));
        color: #f57f17;
        border: 1px solid #ffe082;
    }

    /* ACTION LINKS */
    .td-aksi {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .btn-action {
        padding: 6px 12px;
        border-radius: 6px;
        border: none;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        background: none;
        font-family: inherit;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }

    .btn-action.edit {
        color: #3b82f6;
        background: rgba(59,130,246,0.08);
    }

    .btn-action.edit:hover {
        background: rgba(59,130,246,0.15);
        transform: translateX(2px);
    }

    .btn-action.delete {
        color: #ef4444;
        background: rgba(239,68,68,0.08);
    }

    .btn-action.delete:hover {
        background: rgba(239,68,68,0.15);
        transform: translateX(-2px);
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
                            {{ strtolower($log->user->role ?? '-') }}
                        </span>
                    </td>
                    <td class="td-aktivitas">{{ $log->aktivitas }}</td>
                </tr>
            @empty
                {{-- DUMMY DATA DENGAN LOKASI REAL --}}
                @php
                    $dummy = [
                        ['no' => 1, 'waktu' => '09 Apr 14:32', 'user' => 'Aqela',  'lokasi' => 'Jl. Kawi No.24, Klojen',          'role' => 'petugas', 'aktivitas' => 'Login ke sistem'],
                        ['no' => 2, 'waktu' => '09 Apr 14:28', 'user' => 'Qela',   'lokasi' => '-',                             'role' => 'admin',   'aktivitas' => 'Tambah tarif Bus - Rp 5.000/jam'],
                        ['no' => 3, 'waktu' => '09 Apr 14:20', 'user' => 'Qey',    'lokasi' => 'Jl. Veteran No.2, Lowokwaru',   'role' => 'petugas', 'aktivitas' => 'Login ke sistem'],
                        ['no' => 4, 'waktu' => '09 Apr 14:15', 'user' => 'Nisa',   'lokasi' => '-',                             'role' => 'admin',   'aktivitas' => 'Update area - Kapasitas: 600, Status: aktif'],
                        ['no' => 5, 'waktu' => '09 Apr 13:58', 'user' => 'Niisaa', 'lokasi' => 'Jl. KH Agus Salim 18, Sukoharjo', 'role' => 'owner',   'aktivitas' => 'Logout dari sistem'],
                    ];
                @endphp
                @foreach($dummy as $row)
                    <tr>
                        <td class="td-num">{{ $row['no'] }}</td>
                        <td class="td-waktu">{{ $row['waktu'] }}</td>
                        <td class="td-user">{{ $row['user'] }}</td>
                        <td class="td-lokasi">{{ $row['lokasi'] }}</td>
                        <td>
                            <span class="role-badge {{ $row['role'] }}">
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

@if($logs instanceof \Illuminate\Pagination\Paginator)
    <div style="margin-top: 24px; display: flex; justify-content: center;">
        {{ $logs->links() }}
    </div>
@endif

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