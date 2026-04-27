@extends('layouts.app')

@section('title', 'Struk Parkir')
@section('page-title', 'Struk Parkir')

@section('sidebar')
    @include('components.sidebar.petugas')
@endsection

@section('content')

<style>
    .struk-container {
        max-width: 220px;
        margin: 20px auto;
        background: #fff;
        border: 1px dashed #ccc;
        border-radius: 4px;
        padding: 10px;
        font-family: 'Courier New', monospace;
        font-size: 10px;
        color: #1a1a1a;
    }

    .struk-header {
        text-align: center;
        border-bottom: 1px dashed #ccc;
        padding-bottom: 6px;
        margin-bottom: 6px;
    }

    .struk-header h2 {
        font-size: 12px;
        font-weight: 700;
        margin: 0;
        letter-spacing: 1px;
        color: #1a1a1a;
    }

    .struk-header p {
        font-size: 9px;
        color: #aaa;
        margin: 2px 0 0 0;
    }

    .struk-row {
        display: flex;
        justify-content: space-between;
        font-size: 10px;
        margin-bottom: 3px;
        line-height: 1.4;
    }

    .struk-label {
        color: #666;
    }

    .struk-value {
        color: #1a1a1a;
        font-weight: 600;
        text-align: right;
    }

    .struk-section {
        margin-top: 6px;
        padding-top: 6px;
        border-top: 1px dashed #ccc;
    }

    .struk-total {
        display: flex;
        justify-content: space-between;
        font-size: 11px;
        font-weight: 700;
        margin-top: 5px;
        padding-top: 5px;
        border-top: 1px solid #F8C61E;
        color: #1a1a1a;
    }

    .struk-footer {
        text-align: center;
        margin-top: 6px;
        padding-top: 6px;
        border-top: 1px dashed #ccc;
        font-size: 8px;
        color: #aaa;
    }

    .btn-print {
        width: 100%;
        padding: 6px;
        background: #F8C61E;
        color: #1a1a1a;
        border: none;
        border-radius: 6px;
        font-weight: 700;
        font-size: 11px;
        cursor: pointer;
        margin-top: 10px;
        transition: background 0.2s;
    }

    .btn-print:hover {
        background: #e6b418;
    }

    @media print {
        body * {
            visibility: hidden;
        }

        .struk-container,
        .struk-container * {
            visibility: visible;
        }

        .struk-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 220px;
            margin: 0;
            box-shadow: none;
            border-radius: 0;
        }

        .btn-print {
            display: none;
        }
    }
</style>

<div class="struk-container">
    <div class="struk-header">
        <h2>STRUK PARKIR</h2>
        <p>{{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="struk-row">
        <span class="struk-label">Plat</span>
        <span class="struk-value">{{ $transaksi->kendaraan->plat_nomor ?? '-' }}</span>
    </div>

    <div class="struk-row">
        <span class="struk-label">Kendaraan</span>
        <span class="struk-value">{{ ucfirst($transaksi->kendaraan->jenis ?? '-') }}</span>
    </div>

    <div class="struk-row">
        <span class="struk-label">Area</span>
        <span class="struk-value">{{ $transaksi->area->nama_area ?? '-' }}</span>
    </div>

    <div class="struk-section">
        <div class="struk-row">
            <span class="struk-label">Masuk</span>
            <span class="struk-value">{{ $transaksi->waktu_masuk->format('H:i:s') }}</span>
        </div>

        @if ($transaksi->waktu_keluar)
            <div class="struk-row">
                <span class="struk-label">Keluar</span>
                <span class="struk-value">{{ $transaksi->waktu_keluar->format('H:i:s') }}</span>
            </div>

            <div class="struk-row">
                <span class="struk-label">Durasi</span>
                <span class="struk-value">{{ $transaksi->durasi_menit ?? 0 }} menit</span>
            </div>
        @endif
    </div>

    @if ($transaksi->tarif_akhir)
        <div class="struk-total">
            <span>TOTAL</span>
            <span>Rp {{ number_format($transaksi->tarif_akhir, 0, ',', '.') }}</span>
        </div>
    @endif

    <div class="struk-footer">
        <p>Terima kasih atas kunjungan Anda</p>
    </div>
</div>

<script>
    const autoPrint = "{{ request()->get('autoprint') }}";

    if (autoPrint === '1') {
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
                setTimeout(function() {
                    window.location.href = "{{ route('petugas.transaksi.index') }}";
                }, 500);
            }, 500);
        });
    }
</script>

@endsection