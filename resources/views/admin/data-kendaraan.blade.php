@extends('layouts.app')

@section('title', 'Data Kendaraan')
@section('page-title', 'Data Kendaraan')

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

    .td-plat {
        font-weight: 600;
        color: #1a1a1a;
    }

    .jenis-badge {
        display: inline-block;
        font-size: 12px;
        font-weight: 700;
        padding: 3px 0;
    }

    .jenis-badge.motor { color: #3b82f6; }
    .jenis-badge.mobil { color: #F8C61E; }
    .jenis-badge.bus   { color: #9b59b6; }

    .td-gambar img {
        width: 70px;
        height: 50px;
        object-fit: contain;
        border-radius: 6px;
        background: #f5f5f3;
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

    .modal-field input,
    .modal-field select {
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

    .modal-field input:focus,
    .modal-field select:focus {
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
    <h1>Data Kendaraan</h1>
    <p>Daftar kendaraan terdaftar dalam sistem</p>
</div>

<div class="toolbar">
    <div class="search-box">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#bbb" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input type="text" placeholder="Cari plat nomor...">
    </div>
    <button class="btn-tambah" onclick="bukaModal()">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#1a1a1a" stroke-width="3" stroke-linecap="round">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Tambah Kendaraan
    </button>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>PLAT NOMOR</th>
                <th>JENIS</th>
                <th>GAMBAR</th>
                <th>WARNA</th>
                <th>PEMILIK</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kendaraans ?? [] as $index => $kendaraan)
                <tr>
                    <td class="td-num">{{ $index + 1 }}</td>
                    <td class="td-plat">{{ $kendaraan->plat_nomor }}</td>
                    <td>
                        <span class="jenis-badge {{ strtolower($kendaraan->jenis) }}">
                            {{ $kendaraan->jenis }}
                        </span>
                    </td>
                    <td class="td-gambar">
                        @if($kendaraan->gambar)
                            <img src="{{ asset('storage/' . $kendaraan->gambar) }}" alt="{{ $kendaraan->jenis }}">
                        @else
                            <img src="{{ asset('assets/images/image1.jpg') }}" alt="No Image">
                        @endif
                    </td>
                    <td>{{ $kendaraan->warna }}</td>
                    <td>{{ $kendaraan->nama_pemilik }}</td>
                    <td>
                        <a href="#"
                           class="aksi-edit"
                           data-id="{{ $kendaraan->id }}"
                           data-plat="{{ $kendaraan->plat_nomor }}"
                           data-jenis="{{ $kendaraan->jenis }}"
                           data-warna="{{ $kendaraan->warna }}"
                           data-pemilik="{{ $kendaraan->nama_pemilik }}"
                           data-gambar="{{ $kendaraan->gambar }}"
                           onclick="editKendaraanFromLink(this); return false;">
                            Edit
                        </a>
                        <button type="button" class="aksi-delete" data-id="{{ $kendaraan->id }}" onclick="hapusKendaraanFromLink(this); return false;">Delete</button>
                    </td>
                </tr>
            @empty
                {{-- DUMMY DATA (tampil saat tidak ada data dari database) --}}
                @php
                    $dummy = [
                        ['no' => 1, 'plat' => 'N 1234 AB', 'jenis' => 'Motor',  'warna' => 'Hitam', 'pemilik' => 'Aqela'],
                        ['no' => 2, 'plat' => 'N 5678 CD', 'jenis' => 'Mobil',  'warna' => 'Putih', 'pemilik' => 'Qela'],
                        ['no' => 3, 'plat' => 'N 9012 EF', 'jenis' => 'Motor',  'warna' => 'Merah', 'pemilik' => 'Qey'],
                        ['no' => 4, 'plat' => 'AG 5588 DE','jenis' => 'Mobil',  'warna' => 'Hitam', 'pemilik' => 'Nisa'],
                        ['no' => 5, 'plat' => 'L 9977 BY', 'jenis' => 'Mobil',  'warna' => 'Kuning','pemilik' => 'Niisaa'],
                    ];
                @endphp
                @foreach($dummy as $row)
                    <tr>
                        <td class="td-num">{{ $row['no'] }}</td>
                        <td class="td-plat">{{ $row['plat'] }}</td>
                        <td>
                            <span class="jenis-badge {{ strtolower($row['jenis']) }}">
                                {{ $row['jenis'] }}
                            </span>
                        </td>
                        <td class="td-gambar">
                            <img src="{{ asset('assets/images/image' . ($loop->index + 1) . '.jpg') }}" alt="{{ $row['jenis'] }}">
                        </td>
                        <td>{{ $row['warna'] }}</td>
                        <td>{{ $row['pemilik'] }}</td>
                        <td>
                            <a href="#" class="aksi-edit">Edit</a>
                            <button type="button" class="aksi-delete">Delete</button>
                        </td>
                    </tr>
                @endforeach
            @endforelse
        </tbody>
    </table>
</div>

<!-- MODAL TAMBAH KENDARAAN -->
<div class="modal-overlay" id="modalTambahKendaraan" onclick="tutupModalDiluar(event)">
    <div class="modal-box">
        <h2 id="modalTitle">Tambah Kendaraan</h2>

        <form id="kendaraanForm" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            <div class="modal-field">
                <label>PLAT NOMOR</label>
                <input type="text" name="plat_nomor" id="inputPlat" placeholder="Contoh: N 1234 AB" required>
            </div>

            <div class="modal-row">
                <div class="modal-field">
                    <label>JENIS</label>
                    <select name="jenis" id="inputJenis" required>
                        <option value="" disabled selected>Pilih jenis</option>
                        @foreach($tarifParkirs ?? [] as $tarif)
                            <option value="{{ $tarif->jenis_kendaraan }}">{{ $tarif->jenis_kendaraan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-field">
                    <label>WARNA</label>
                    <input type="text" name="warna" id="inputWarna" placeholder="Contoh: Hitam" required>
                </div>
            </div>

            <div class="modal-field">
                <label>NAMA PEMILIK</label>
                <input type="text" name="nama_pemilik" id="inputPemilik" placeholder="Nama pemilik kendaraan" required>
            </div>

            <div class="modal-field">
                <label>GAMBAR</label>
                <input type="file" name="gambar" id="inputGambar" accept="image/*" onchange="previewGambar(this)">
                <small style="color: #999;">Format: JPG, PNG, GIF (Max: 2MB)</small>
                <div id="gambarPreview" style="margin-top: 10px; display: none;">
                    <img id="previewImg" src="" alt="Preview" style="max-width: 200px; max-height: 150px; border-radius: 8px; border: 2px solid #e8e4dc;">
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-batal" onclick="tutupModal()">Batal</button>
                <button type="submit" class="btn-simpan">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    let editingId = null;

    function bukaModal() {
        editingId = null;
        document.getElementById('modalTitle').textContent = 'Tambah Kendaraan';
        document.getElementById('kendaraanForm').action = '{{ route("admin.kendaraan.store") }}';
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('kendaraanForm').reset();
        document.getElementById('modalTambahKendaraan').classList.add('active');
    }

    function tutupModal() {
        document.getElementById('modalTambahKendaraan').classList.remove('active');
    }

    function tutupModalDiluar(event) {
        if (event.target === document.getElementById('modalTambahKendaraan')) {
            tutupModal();
        }
    }

    function editKendaraan(id, plat, jenis, warna, pemilik) {
        editingId = id;
        document.getElementById('modalTitle').textContent = 'Edit Kendaraan';
        document.getElementById('kendaraanForm').action = '{{ route("admin.kendaraan.update", ":id") }}'.replace(':id', id);
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('inputPlat').value = plat;
        document.getElementById('inputJenis').value = jenis;
        document.getElementById('inputWarna').value = warna;
        document.getElementById('inputPemilik').value = pemilik;
        document.getElementById('inputGambar').value = '';

        // Reset preview
        const previewDiv = document.getElementById('gambarPreview');
        const previewImg = document.getElementById('previewImg');
        previewDiv.style.display = 'none';
        previewImg.src = '';

        document.getElementById('modalTambahKendaraan').classList.add('active');
    }

    function hapusKendaraan(id) {
        if (!confirm('Yakin hapus data kendaraan ini?')) return;
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.kendaraan.destroy", ":id") }}'.replace(':id', id);
        form.innerHTML = '@csrf @method("DELETE")';
        document.body.appendChild(form);
        form.submit();
    }

    function editKendaraanFromLink(link) {
        const data = link.dataset;
        editKendaraan(data.id, data.plat, data.jenis, data.warna, data.pemilik);

        // Show existing image if available
        if (data.gambar) {
            const previewDiv = document.getElementById('gambarPreview');
            const previewImg = document.getElementById('previewImg');
            previewImg.src = '{{ asset("storage/") }}/' + data.gambar;
            previewDiv.style.display = 'block';
        }
    }

    function previewGambar(input) {
        const previewDiv = document.getElementById('gambarPreview');
        const previewImg = document.getElementById('previewImg');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewDiv.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            previewDiv.style.display = 'none';
            previewImg.src = '';
        }
    }

    function hapusKendaraanFromLink(button) {
        hapusKendaraan(button.dataset.id);
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') tutupModal();
    });
</script>

@endsection