@extends('layouts.app')

@section('title', 'Struk Parkir')
@section('page-title', 'Struk Parkir')

@section('sidebar')
    @include('components.sidebar.petugas')
@endsection

@section('content')

<style>
    .struk-container {
        max-width: 500px;
        margin: 40px auto;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        padding: 32px;
        font-family: 'Courier New', monospace;
    }

    .struk-header {
        text-align: center;
        margin-bottom: 24px;
        border-bottom: 1px dashed #ddd;
        padding-bottom: 16px;
    }

    .struk-header h2 {
        font-size: 18px;
        font-weight: 700;
        margin: 0;
        color: #1a1a1a;
    }

    .struk-header p {
        font-size: 12px;
        color: #aaa;
        margin: 4px 0 0 0;
    }

    .struk-row {
        display: flex;
        justify-content: space-between;
        font-size: 13px;
        margin-bottom: 8px;
        line-height: 1.5;
    }

    .struk-label {
        color: #666;
    }

    .struk-value {
        color: #1a1a1a;
        font-weight: 600;
    }

    .struk-section {
        margin-top: 16px;
        padding-top: 12px;
        border-top: 1px dashed #ddd;
    }

    .struk-total {
        display: flex;
        justify-content: space-between;
        font-size: 16px;
        font-weight: 700;
        margin-top: 16px;
        padding-top: 12px;
        border-top: 1px solid #F8C61E;
        color: #1a1a1a;
    }

    .struk-footer {
        text-align: center;
        margin-top: 24px;
        padding-top: 12px;
        border-top: 1px dashed #ddd;
        font-size: 11px;
        color: #aaa;
    }

    .btn-print {
        width: 100%;
        padding: 12px;
        background: #F8C61E;
        color: #1a1a1a;
        border: none;
        border-radius: 10px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        margin-top: 24px;
        transition: background 0.2s;
    }

    .btn-print:hover {
        background: #e6b418;
    }

    @media print {
        body { background: #fff; }
        .btn-print { display: none; }
    }
</style>

<div class="struk-container">
    <div class="struk-header">
        <h2>STRUK PARKIR</h2>
        <p>{{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="struk-row">
        <span class="struk-label">Plat Nomor:</span>
        <span class="struk-value">{{ $transaksi->kendaraan->plat_nomor ?? '-' }}</span>
    </div>

    <div class="struk-row">
        <span class="struk-label">Jenis Kendaraan:</span>
        <span class="struk-value">{{ ucfirst($transaksi->kendaraan->jenis ?? '-') }}</span>
    </div>

    <div class="struk-row">
        <span class="struk-label">Area Parkir:</span>
        <span class="struk-value">{{ $transaksi->area->nama_area ?? '-' }}</span>
    </div>

    <div class="struk-section">
        <div class="struk-row">
            <span class="struk-label">Waktu Masuk:</span>
            <span class="struk-value">{{ $transaksi->waktu_masuk->format('H:i:s') }}</span>
        </div>

        @if ($transaksi->waktu_keluar)
            <div class="struk-row">
                <span class="struk-label">Waktu Keluar:</span>
                <span class="struk-value">{{ $transaksi->waktu_keluar->format('H:i:s') }}</span>
            </div>

            <div class="struk-row">
                <span class="struk-label">Durasi:</span>
                <span class="struk-value">{{ $transaksi->durasi_menit ?? 0 }} menit</span>
            </div>
        @endif
    </div>

    @if ($transaksi->tarif_akhir)
        <div class="struk-total">
            <span>TOTAL TARIF:</span>
            <span>Rp {{ number_format($transaksi->tarif_akhir, 0, ',', '.') }}</span>
        </div>
    @endif

    <div class="struk-footer">
        <p>Terima kasih telah menggunakan layanan parkir kami</p>
    </div>
</div>

<button class="btn-print" onclick="window.print()">
    🖨️ Cetak Struk
</button>

@endsection
