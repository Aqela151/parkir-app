@extends('layouts.app')

@section('title', 'Area Parkir')
@section('page-title', 'Area Parkir')

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

    .btn-tambah {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #F8C61E;
        color: #1a1a1a;
        border: none;
        border-radius: 30px;
        padding: 10px 22px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.15s;
    }

    .btn-tambah:hover { background: #e6b600; }

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

    .td-nama {
        font-weight: 600;
        color: #1a1a1a;
    }

    .td-alamat {
        color: #aaa;
        font-size: 12px;
    }

    .td-angka {
        color: #555;
        font-weight: 500;
    }

    .pill-avail {
        display: inline-block;
        font-size: 11px;
        font-weight: 700;
        padding: 3px 12px;
        border-radius: 20px;
        color: #4caf7d;
        background: rgba(76,175,125,0.08);
    }

    .pill-avail.low {
        color: #C97A1A;
        background: rgba(249,198,30,0.12);
    }

    .pill-avail.critical {
        color: #e53e3e;
        background: rgba(229,83,83,0.08);
    }

    .aksi-edit {
        color: #3b82f6;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        margin-right: 10px;
    }

    .aksi-delete {
        color: #e53e3e;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        background: none;
        border: none;
        padding: 0;
        text-decoration: none;
        font-family: inherit;
    }

    .aksi-edit:hover   { text-decoration: underline; }
    .aksi-delete:hover { text-decoration: underline; }

    /* ===== MODAL ===== */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.45);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-box {
        background: #fff;
        border-radius: 20px;
        padding: 36px 36px 30px;
        width: 100%;
        max-width: 480px;
        box-shadow: 0 8px 40px rgba(0,0,0,0.18);
        animation: modalIn 0.2s ease;
    }

    @keyframes modalIn {
        from { opacity: 0; transform: translateY(16px) scale(0.97); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    .modal-box h2 {
        font-size: 22px;
        font-weight: 900;
        color: #1a1a1a;
        margin-bottom: 24px;
        letter-spacing: -0.5px;
    }

    .modal-field {
        margin-bottom: 18px;
    }

    .modal-field label {
        display: block;
        font-size: 11px;
        font-weight: 700;
        color: #888;
        letter-spacing: 0.5px;
        margin-bottom: 7px;
    }

    .modal-field input {
        width: 100%;
        border: 1.5px solid #e8e4dc;
        border-radius: 10px;
        padding: 11px 14px;
        font-size: 13px;
        color: #1a1a1a;
        outline: none;
        background: #fafaf8;
        box-sizing: border-box;
        transition: border-color 0.15s;
        font-family: inherit;
    }

    .modal-field input:focus {
        border-color: #F8C61E;
        background: #fff;
    }

    .modal-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 24px;
    }

    .btn-batal {
        background: #fff;
        border: 1.5px solid #e0dbd2;
        color: #555;
        border-radius: 30px;
        padding: 10px 24px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: border-color 0.15s;
        font-family: inherit;
    }

    .btn-batal:hover { border-color: #bbb; }

    .btn-simpan {
        background: #F8C61E;
        color: #1a1a1a;
        border: none;
        border-radius: 30px;
        padding: 10px 28px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.15s;
        font-family: inherit;
    }

    .btn-simpan:hover { background: #e6b600; }

    @media (max-width: 768px) {
        .toolbar { flex-direction: column; align-items: stretch; }
        .search-box { max-width: 100%; }
        .table-card { overflow-x: auto; }
        .modal-row { grid-template-columns: 1fr; }
        .modal-box { margin: 16px; padding: 28px 20px 22px; }
    }
</style>

<div class="top-header">
    <span></span>
    <span class="admin-panel-badge">ADMIN PANEL</span>
</div>

<div class="page-heading">
    <h1>Area Parkir</h1>
    <p>Kelola area dan kapasitas parkir</p>
</div>

<div class="toolbar">
    <div class="search-box">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#bbb" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input type="text" id="searchInput" placeholder="Cari area...">
    </div>
    <button class="btn-tambah" onclick="bukaModal()">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#1a1a1a" stroke-width="3" stroke-linecap="round">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Tambah Area
    </button>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>NAMA AREA</th>
                <th>ALAMAT</th>
                <th>MOBIL</th>
                <th>MOTOR</th>
                <th>BUS</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            @forelse($areas ?? [] as $index => $area)
                <tr>
                    <td class="td-num">{{ $index + 1 }}</td>
                    <td class="td-nama">{{ $area->nama_area }}</td>
                    <td class="td-alamat">{{ $area->lokasi ?? '-' }}</td>
                    <td class="td-angka">{{ $area->kapasitas_mobil }}</td>
                    <td class="td-angka">{{ $area->kapasitas_motor }}</td>
                    <td class="td-angka">{{ $area->kapasitas_bus }}</td>
                    <td>
                        <a href="#"
                           class="aksi-edit"
                           data-id="{{ $area->id }}"
                           data-nama="{{ $area->nama_area }}"
                           data-lokasi="{{ $area->lokasi }}"
                           data-mobil="{{ $area->kapasitas_mobil }}"
                           data-motor="{{ $area->kapasitas_motor }}"
                           data-bus="{{ $area->kapasitas_bus }}"
                           data-status="{{ $area->status }}"
                           onclick="editAreaFromLink(this); return false;">
                            Edit
                        </a>
                        <button type="button" class="aksi-delete" data-id="{{ $area->id }}" onclick="hapusAreaFromLink(this); return false;">Delete</button>
                    </td>
                </tr>
            @empty
                {{-- DUMMY DATA sesuai gambar --}}
                @php
                    $dummy = [
                        ['no' => 1, 'nama' => 'Mall Olympic Garden (MOG)', 'alamat' => 'Jl. Kawi No.24, Klojen',          'kapasitas' => 500, 'terisi' => 440],
                        ['no' => 2, 'nama' => 'Malang Town Square',        'alamat' => 'Jl. Veteran No.2, Lowokwaru',     'kapasitas' => 600, 'terisi' => 426],
                        ['no' => 3, 'nama' => 'Malang Plaza',              'alamat' => 'Jl. KH Agus Salim 18, Sukoharjo', 'kapasitas' => 400, 'terisi' => 180],
                        ['no' => 4, 'nama' => 'Alun-alun Merdeka Malang',  'alamat' => 'Jl. Merdeka Selatan, Klojen',     'kapasitas' => 300, 'terisi' => 279],
                    ];
                @endphp
                @foreach($dummy as $row)
                    @php
                        $tersedia = $row['kapasitas'] - $row['terisi'];
                        $persen   = ($row['terisi'] / $row['kapasitas']) * 100;
                        $kelas    = $persen >= 90 ? 'critical' : ($persen >= 75 ? 'low' : '');
                    @endphp
                    <tr>
                        <td class="td-num">{{ $row['no'] }}</td>
                        <td class="td-nama">{{ $row['nama'] }}</td>
                        <td class="td-alamat">{{ $row['alamat'] }}</td>
                        <td class="td-angka">{{ $row['kapasitas'] }}</td>
                        <td class="td-angka">{{ $row['terisi'] }}</td>
                        <td><span class="pill-avail {{ $kelas }}">{{ $tersedia }}</span></td>
                        <td>
                            <a href="#" class="aksi-edit" onclick="bukaEdit(this); return false;">Edit</a>
                            <button type="button" class="aksi-delete">Delete</button>
                        </td>
                    </tr>
                @endforeach
            @endforelse
        </tbody>
    </table>
</div>

<!-- MODAL TAMBAH / EDIT AREA -->
<div class="modal-overlay" id="modalArea" onclick="tutupModalDiluar(event)">
    <div class="modal-box">
        <h2 id="modalTitle">Tambah Area</h2>

        <form id="areaForm" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            <div class="modal-field">
                <label>NAMA AREA</label>
                <input type="text" name="nama_area" id="inputNama" placeholder="Contoh: Mall Olympic Garden" required>
            </div>
            <div class="modal-field">
                <label>LOKASI / ALAMAT</label>
                <input type="text" name="lokasi" id="inputAlamat" placeholder="Contoh: Jl. Kawi No.24, Klojen" required>
            </div>
            <div class="modal-row">
                <div class="modal-field">
                    <label>KAPASITAS MOBIL</label>
                    <input type="number" name="kapasitas_mobil" id="inputMobil" placeholder="0" min="0" required>
                </div>
                <div class="modal-field">
                    <label>KAPASITAS MOTOR</label>
                    <input type="number" name="kapasitas_motor" id="inputMotor" placeholder="0" min="0" required>
                </div>
            </div>
            <div class="modal-field">
                <label>KAPASITAS BUS</label>
                <input type="number" name="kapasitas_bus" id="inputBus" placeholder="0" min="0" required>
            </div>
            <div class="modal-field">
                <label>STATUS</label>
                <select name="status" id="inputStatus" required>
                    <option value="aktif">Aktif</option>
                    <option value="penuh">Penuh</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-batal" onclick="tutupModal()">Batal</button>
                <button type="submit" class="btn-simpan">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function bukaModal() {
        document.getElementById('modalTitle').textContent = 'Tambah Area';
        document.getElementById('areaForm').action = '{{ route("admin.area-parkir.store") }}';
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('areaForm').reset();
        document.getElementById('modalArea').classList.add('active');
    }

    function editArea(id, nama, lokasi, mobil, motor, bus, status) {
        document.getElementById('modalTitle').textContent = 'Edit Area';
        document.getElementById('areaForm').action = '{{ route("admin.area-parkir.update", ":id") }}'.replace(':id', id);
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('inputNama').value = nama;
        document.getElementById('inputAlamat').value = lokasi;
        document.getElementById('inputMobil').value = mobil;
        document.getElementById('inputMotor').value = motor;
        document.getElementById('inputBus').value = bus;
        document.getElementById('inputStatus').value = status;
        document.getElementById('modalArea').classList.add('active');
    }

    function editAreaFromLink(link) {
        const data = link.dataset;
        editArea(data.id, data.nama, data.lokasi, data.mobil, data.motor, data.bus, data.status);
    }

    function hapusAreaFromLink(button) {
        hapusArea(button.dataset.id);
    }

    function tutupModal() {
        document.getElementById('modalArea').classList.remove('active');
    }

    function tutupModalDiluar(event) {
        if (event.target === document.getElementById('modalArea')) tutupModal();
    }

    function hapusArea(id) {
        if (!confirm('Yakin hapus area ini?')) return;
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.area-parkir.destroy", ":id") }}'.replace(':id', id);
        form.innerHTML = '@csrf @method("DELETE")';
        document.body.appendChild(form);
        form.submit();
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') tutupModal();
    });
</script>

@endsection