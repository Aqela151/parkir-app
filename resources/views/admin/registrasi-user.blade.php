@extends('layouts.app')

@section('title', 'Registrasi User')
@section('page-title', 'Registrasi User')

@section('content')
<style>
    .page-header { margin-bottom: 28px; }
    .page-header h1 { font-size: 32px; font-weight: 900; color: #1a1a1a; letter-spacing: -1px; margin-bottom: 4px; }
    .page-header p  { font-size: 13px; color: #aaa; }

    .table-card {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    }

    .toolbar {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
    }

    .search-wrap { position: relative; flex: 1; max-width: 360px; }
    .search-wrap i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #ccc; font-size: 13px; }

    .search-input {
        width: 100%;
        padding: 10px 14px 10px 38px;
        border: 1.5px solid #ede9e0;
        border-radius: 50px;
        font-size: 13px;
        font-family: 'Manrope', sans-serif;
        background: #faf9f7;
        color: #1a1a1a;
        outline: none;
        transition: border-color 0.2s;
    }

    .search-input:focus { border-color: #F8C61E; }
    .search-input::placeholder { color: #ccc; }

    .btn-tambah {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #F8C61E;
        color: #1a1a1a;
        border: none;
        padding: 10px 22px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 700;
        font-family: 'Manrope', sans-serif;
        cursor: pointer;
        transition: opacity 0.2s;
        white-space: nowrap;
    }

    .btn-tambah:hover { opacity: 0.85; }

    table { 
        width: 100%; 
        border-collapse: collapse; 
        table-layout: auto;
    }

    thead {
        position: sticky;
        top: 0;
        z-index: 10;
    }

    thead tr { 
        background: linear-gradient(135deg, #1C1C1E 0%, #2a2a2d 100%);
        border-radius: 12px;
    }

    thead th {
        padding: 14px 16px;
        font-size: 11px;
        font-weight: 700;
        color: #fff;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        text-align: left;
    }

    thead th:first-child { border-radius: 12px 0 0 0; }
    thead th:last-child { border-radius: 0 12px 0 0; }

    tbody tr { 
        border-bottom: 1px solid #f5f0e8; 
        transition: all 0.12s;
    }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { 
        background: #fafaf8;
        box-shadow: inset 0 0 0 1px #f0ede8;
    }

    tbody td { 
        padding: 14px 16px; 
        font-size: 13px; 
        color: #aaa; 
        vertical-align: middle;
    }

    .td-no   { color: #ccc; font-size: 12px; width: 45px; font-weight: 500; }
    .td-name { font-weight: 700; color: #1a1a1a; font-size: 14px; }
    .td-user { font-weight: 600; color: #555; }

    .badge-role { 
        display: inline-block; 
        padding: 4px 12px; 
        border-radius: 20px; 
        font-size: 12px; 
        font-weight: 700;
    }
    .badge-admin   { color: #F8C61E; }
    .badge-petugas { color: #4a9eff; }
    .badge-owner   { color: #4caf7d; }

    .badge-aktif    { color: #4caf7d; font-weight: 700; font-size: 13px; }
    .badge-nonaktif { color: #ff5555; font-weight: 700; font-size: 13px; }

    .aksi-wrap { 
        display: flex; 
        gap: 12px;
        align-items: center;
    }

    .btn-edit {
        background: none; 
        border: none;
        font-size: 13px; 
        font-weight: 700;
        font-family: 'Manrope', sans-serif;
        color: #888; 
        cursor: pointer; 
        padding: 4px 8px;
        transition: color 0.2s;
    }
    .btn-edit:hover { color: #1a1a1a; }

    .btn-delete {
        background: none; 
        border: none;
        font-size: 13px; 
        font-weight: 700;
        font-family: 'Manrope', sans-serif;
        color: #ffaaaa; 
        cursor: pointer; 
        padding: 4px 8px;
        transition: color 0.2s;
    }
    .btn-delete:hover { color: #ff5555; }

    /* MODAL */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.3);
        z-index: 200;
        align-items: center;
        justify-content: center;
    }
    .modal-overlay.show { display: flex; }

    .modal {
        background: #fff;
        border-radius: 16px;
        padding: 32px;
        width: 100%;
        max-width: 440px;
        box-shadow: 0 8px 40px rgba(0,0,0,0.12);
    }

    .modal h2 { font-size: 18px; font-weight: 800; color: #1a1a1a; margin-bottom: 24px; }

    .modal-group { margin-bottom: 16px; }

    .modal-label {
        display: block;
        font-size: 11px; font-weight: 700;
        color: #888; text-transform: uppercase;
        letter-spacing: 0.5px; margin-bottom: 6px;
    }

    .modal-input, .modal-select {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid #ede9e0;
        border-radius: 10px;
        font-size: 13px;
        font-family: 'Manrope', sans-serif;
        color: #1a1a1a;
        background: #faf9f7;
        outline: none;
        transition: border-color 0.2s;
    }
    .modal-input:focus, .modal-select:focus { border-color: #F8C61E; }

    .modal-footer { display: flex; gap: 10px; margin-top: 24px; justify-content: flex-end; }

    .btn-cancel {
        padding: 10px 20px; background: #f5f0e8;
        border: none; border-radius: 10px;
        font-size: 13px; font-weight: 700;
        font-family: 'Manrope', sans-serif;
        cursor: pointer; color: #888;
    }

    .btn-save {
        padding: 10px 24px; background: #F8C61E;
        border: none; border-radius: 10px;
        font-size: 13px; font-weight: 700;
        font-family: 'Manrope', sans-serif;
        cursor: pointer; color: #1a1a1a;
    }
    .btn-save:hover { opacity: 0.85; }
</style>

<div class="page-header">
    <h1>Registrasi User</h1>
    <p>Tambah dan kelola akun pengguna sistem</p>
</div>

@if(session('success'))
<div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
    <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="table-card">
    <div class="toolbar">
        <div class="search-wrap">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" class="search-input" placeholder="Cari user..." id="searchInput" onkeyup="filterTable()">
        </div>
        <button class="btn-tambah" onclick="openModal()">
            <i class="fa-solid fa-plus"></i> Tambah User
        </button>
    </div>

    <table id="userTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Lengkap</th>
                <th>Email</th>
                <th>Username</th>
                <th>Penempatan</th>
                <th>Role</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $index => $user)
            <tr>
                <td class="td-no">{{ $index + 1 }}</td>
                <td class="td-name">{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td class="td-user">{{ $user->username }}</td>
                <td>{{ $user->penempatan }}</td>
                <td><span class="badge-role badge-{{ strtolower($user->role) }}">{{ $user->role }}</span></td>
                <td><span class="badge-{{ $user->status == 'aktif' ? 'aktif' : 'nonaktif' }}">{{ ucfirst($user->status) }}</span></td>
                <td class="aksi-wrap">
                    <button class="btn-edit"
                            type="button"
                            data-id="{{ $user->id }}"
                            data-name="{{ $user->name }}"
                            data-email="{{ $user->email }}"
                            data-username="{{ $user->username }}"
                            data-penempatan="{{ $user->penempatan }}"
                            data-role="{{ $user->role }}"
                            onclick="editUserFromButton(this)">
                        Edit
                    </button>
                    <form method="POST" action="{{ route('admin.registrasi-user.destroy', $user->id) }}" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn-delete" onclick="return confirm('Yakin hapus user ini?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- MODAL --}}
<div class="modal-overlay" id="modalOverlay">
    <form id="userForm" method="POST">
        @csrf
        <div class="modal">
            <h2 id="modalTitle">Tambah User</h2>
            <input type="hidden" id="userId" name="user_id">
            <div class="modal-group">
                <label class="modal-label">Nama Lengkap</label>
                <input type="text" class="modal-input" id="inputNama" name="name" placeholder="Nama lengkap" required>
            </div>
            <div class="modal-group">
                <label class="modal-label">Email</label>
                <input type="email" class="modal-input" id="inputEmail" name="email" placeholder="email@example.com" required>
            </div>
            <div class="modal-group">
                <label class="modal-label">Username</label>
                <input type="text" class="modal-input" id="inputUsername" name="username" placeholder="Username" required>
            </div>
            <div class="modal-group">
                <label class="modal-label">Password</label>
                <input type="password" class="modal-input" id="inputPassword" name="password" placeholder="••••••••">
            </div>
            <div class="modal-group">
                <label class="modal-label">Penempatan</label>
                <select class="modal-select" id="inputPenempatan" name="penempatan" required>
                    <option value="">-- Pilih Lokasi --</option>
                    <option>Mall Olympic Garden</option>
                    <option>Malang Town Square</option>
                    <option>Malang Plaza</option>
                    <option>Alun-alun Merdeka Malang</option>
                    <option>Pasar Besar Malang</option>
                </select>
            </div>
            <div class="modal-group">
                <label class="modal-label">Role</label>
                <select class="modal-select" id="inputRole" name="role" required>
                    <option value="">-- Pilih Role --</option>
                    <option>Admin</option>
                    <option>Petugas</option>
                    <option>Owner</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn-save">Simpan</button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    let editingId = null;

    function openModal() {
        editingId = null;
        document.getElementById('modalTitle').textContent = 'Tambah User';
        document.getElementById('userForm').action = '{{ route("admin.registrasi-user.store") }}';
        document.getElementById('userForm').querySelector('input[name="_method"]')?.remove();
        ['inputNama','inputEmail','inputUsername','inputPassword','inputPenempatan','inputRole'].forEach(id => {
            document.getElementById(id).value = '';
        });
        document.getElementById('inputPassword').required = true;
        document.getElementById('modalOverlay').classList.add('show');
    }

    function closeModal() {
        document.getElementById('modalOverlay').classList.remove('show');
    }

    function editUser(id, name, email, username, penempatan, role) {
        editingId = id;
        document.getElementById('modalTitle').textContent = 'Edit User';
        document.getElementById('userForm').action = '{{ route("admin.registrasi-user.update", ":id") }}'.replace(':id', id);
        let methodInput = document.getElementById('userForm').querySelector('input[name="_method"]');
        if (!methodInput) {
            methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            document.getElementById('userForm').appendChild(methodInput);
        }
        document.getElementById('inputNama').value = name;
        document.getElementById('inputEmail').value = email;
        document.getElementById('inputUsername').value = username;
        document.getElementById('inputPassword').value = '';
        document.getElementById('inputPenempatan').value = penempatan;
        document.getElementById('inputRole').value = role;
        document.getElementById('inputPassword').required = false;
        document.getElementById('modalOverlay').classList.add('show');
    }

    function editUserFromButton(button) {
        const data = button.dataset;
        editUser(data.id, data.name, data.email, data.username, data.penempatan, data.role);
    }

    function filterTable() {
        const q = document.getElementById('searchInput').value.toLowerCase();
        document.querySelectorAll('#userTable tbody tr').forEach(tr => {
            tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    }

    // Close modal when clicking outside
    document.getElementById('modalOverlay').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });
</script>
@endpush

@endsection