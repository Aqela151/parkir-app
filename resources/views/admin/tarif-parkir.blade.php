@extends('layouts.app')

@section('title', 'Tarif Parkir')
@section('page-title', 'Tarif Parkir')

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

    .td-jenis {
        font-weight: 600;
        color: #1a1a1a;
    }

    .status-badge {
        display: inline-block;
        font-size: 11px;
        font-weight: 700;
        padding: 3px 12px;
        border-radius: 20px;
    }

    .status-badge.aktif     { color: #4caf7d; background: rgba(76,175,125,0.08); }
    .status-badge.draft     { color: #F8C61E; background: rgba(248,198,30,0.10); }
    .status-badge.nonaktif  { color: #e57373; background: rgba(229,115,115,0.08); }

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
    <h1>Tarif Parkir</h1>
    <p>Kelola tarif per jenis kendaraan</p>
</div>

<div class="toolbar">
    <div class="search-box">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#bbb" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input type="text" placeholder="Cari parkir...">
    </div>
    <button class="btn-tambah" onclick="bukaModal()">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#1a1a1a" stroke-width="3" stroke-linecap="round">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Tambah Tarif
    </button>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>JENIS KENDARAAN</th>
                <th>TARIF/JAM (RP)</th>
                <th>TARIF FLAT MALAM</th>
                <th>STATUS</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tarifs ?? [] as $index => $tarif)
                <tr>
                    <td class="td-num">{{ $index + 1 }}</td>
                    <td class="td-jenis">{{ $tarif->jenis_kendaraan }}</td>
                    <td>Rp {{ number_format($tarif->tarif_per_jam, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($tarif->tarif_flat_malam, 0, ',', '.') }}</td>
                    <td>
                        <span class="status-badge {{ strtolower($tarif->status) }}">
                            {{ ucfirst($tarif->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="#"
                           class="aksi-edit"
                           data-id="{{ $tarif->id }}"
                           data-jenis="{{ $tarif->jenis_kendaraan }}"
                           data-tarif-jam="{{ $tarif->tarif_per_jam }}"
                           data-tarif-malam="{{ $tarif->tarif_flat_malam }}"
                           data-status="{{ $tarif->status }}"
                           onclick="editTarifFromLink(this); return false;">
                            Edit
                        </a>
                        <button type="button" class="aksi-delete" data-id="{{ $tarif->id }}" onclick="hapusTarifFromButton(this); return false;">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center; color:#bbb; padding: 40px;">Belum ada data tarif.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- MODAL TAMBAH TARIF -->
<div class="modal-overlay" id="modalTambahTarif" onclick="tutupModalDiluar(event)">
    <div class="modal-box">
        <h2 id="modalTitle">Tambah Tarif</h2>

        <form id="tarifForm" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            <div class="modal-field">
                <label>JENIS KENDARAAN</label>
                <input type="text" name="jenis_kendaraan" id="inputJenis" placeholder="Contoh: Motor, Mobil, Bus/Truk" required>
            </div>

            <div class="modal-row">
                <div class="modal-field">
                    <label>TARIF PER JAM (RP)</label>
                    <input type="number" name="tarif_per_jam" id="inputTarifJam" placeholder="0" min="0" required>
                </div>
                <div class="modal-field">
                    <label>TARIF FLAT MALAM (RP)</label>
                    <input type="number" name="tarif_flat_malam" id="inputTarifMalam" placeholder="0" min="0" required>
                </div>
            </div>

            <div class="modal-field">
                <label>STATUS</label>
                <select name="status" id="inputStatus" required>
                    <option value="aktif">Aktif</option>
                    <option value="draft">Draft</option>
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
        document.getElementById('modalTitle').textContent = 'Tambah Tarif';
        document.getElementById('tarifForm').action = '{{ route("admin.tarif-parkir.store") }}';
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('tarifForm').reset();
        document.getElementById('modalTambahTarif').classList.add('active');
    }

    function editTarif(id, jenis, tarifJam, tarifMalam, status) {
        document.getElementById('modalTitle').textContent = 'Edit Tarif';
        document.getElementById('tarifForm').action = '{{ route("admin.tarif-parkir.update", ":id") }}'.replace(':id', id);
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('inputJenis').value = jenis;
        document.getElementById('inputTarifJam').value = tarifJam;
        document.getElementById('inputTarifMalam').value = tarifMalam;
        document.getElementById('inputStatus').value = status;
        document.getElementById('modalTambahTarif').classList.add('active');
    }

    function tutupModal() {
        document.getElementById('modalTambahTarif').classList.remove('active');
    }

    function tutupModalDiluar(event) {
        if (event.target === document.getElementById('modalTambahTarif')) {
            tutupModal();
        }
    }

    function hapusTarif(id) {
        if (!confirm('Yakin hapus tarif ini?')) return;
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.tarif-parkir.destroy", ":id") }}'.replace(':id', id);
        form.innerHTML = '@csrf @method("DELETE")';
        document.body.appendChild(form);
        form.submit();
    }

    function editTarifFromLink(link) {
        const data = link.dataset;
        editTarif(data.id, data.jenis, data.tarifJam, data.tarifMalam, data.status);
    }

    function hapusTarifFromButton(button) {
        hapusTarif(button.dataset.id);
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') tutupModal();
    });
</script>

@endsection