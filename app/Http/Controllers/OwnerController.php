<?php

namespace App\Http\Controllers;

use App\Models\AreaParkir;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
    public function rekapTransaksi(Request $request)
    {
        $areas = AreaParkir::where('status', 'aktif')->get();

        $query = Transaksi::with(['kendaraan', 'area']);

        if ($request->filled('dari_tanggal')) {
            $query->whereDate('waktu_masuk', '>=', $request->dari_tanggal);
        }

        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('waktu_masuk', '<=', $request->sampai_tanggal);
        }

        if ($request->filled('lokasi')) {
            $query->where('area_id', $request->lokasi);
        }

        if ($request->filled('jenis')) {
            $query->whereHas('kendaraan', function ($q) use ($request) {
                $q->where('jenis', $request->jenis);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('kendaraan', function ($q2) use ($search) {
                    $q2->where('plat_nomor', 'like', "%{$search}%");
                })
                ->orWhereHas('area', function ($q2) use ($search) {
                    $q2->where('nama_area', 'like', "%{$search}%");
                });
            });
        }

        $totalsQuery = clone $query;

        $totalTransaksi = $totalsQuery->count();
        $totalPendapatan = $totalsQuery->sum(DB::raw('COALESCE(tarif_akhir, 0)'));
        $rataDurasi = round($totalsQuery->avg('durasi_menit') ?? 0);
        $rataTransaksi = $totalTransaksi > 0 ? round($totalPendapatan / $totalTransaksi) : 0;

        switch ($request->get('sort')) {
            case 'tarif_desc':
                $query->orderByDesc('tarif_akhir');
                break;
            case 'tarif_asc':
                $query->orderBy('tarif_akhir');
                break;
            case 'terlama':
                $query->orderBy('waktu_masuk');
                break;
            default:
                $query->orderByDesc('waktu_masuk');
                break;
        }

        $transaksis = $query->paginate(10)->withQueryString();

        return view('owner.rekap-transaksi', compact(
            'areas',
            'transaksis',
            'totalTransaksi',
            'totalPendapatan',
            'rataDurasi',
            'rataTransaksi'
        ));
    }

    public function grafikPendapatan(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $areaId = $request->get('area_id');

        // Tahun list untuk dropdown
        $tahunList = [date('Y'), date('Y')-1, date('Y')-2];

        // Area list untuk dropdown
        $areaList = AreaParkir::where('status', 'aktif')
            ->select('id', 'nama_area as nama')
            ->get()
            ->toArray();

        // Data grafik bulanan
        $grafikBulanan = [];
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $query = Transaksi::whereYear('waktu_masuk', $tahun)
                ->whereMonth('waktu_masuk', $bulan)
                ->whereNotNull('tarif_akhir');

            if ($areaId) {
                $query->where('area_id', $areaId);
            }

            $motor = (clone $query)->whereHas('kendaraan', function ($q) {
                $q->where('jenis', 'Motor');
            })->sum('tarif_akhir') ?? 0;

            $mobil = (clone $query)->whereHas('kendaraan', function ($q) {
                $q->where('jenis', 'Mobil');
            })->sum('tarif_akhir') ?? 0;

            $grafikBulanan[] = [
                'motor' => $motor,
                'mobil' => $mobil
            ];
        }

        // Total pendapatan tahun ini
        $totalTahun = collect($grafikBulanan)->sum(fn($d) => $d['motor'] + $d['mobil']);
        $totalTahunFormatted = 'Rp ' . number_format($totalTahun, 0, ',', '.');

        // Trend tahunan (vs tahun lalu)
        $totalTahunLalu = Transaksi::whereYear('waktu_masuk', $tahun - 1)
            ->when($areaId, fn($q) => $q->where('area_id', $areaId))
            ->sum('tarif_akhir') ?? 0;

        $trendTahunan = $totalTahunLalu > 0 ? round((($totalTahun - $totalTahunLalu) / $totalTahunLalu) * 100) : 0;

        // Bulan tertinggi
        $bulanTertinggi = '';
        $pendapatanBulanTertinggi = 0;
        $bulanLabels = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                       'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        foreach ($grafikBulanan as $i => $data) {
            $totalBulan = $data['motor'] + $data['mobil'];
            if ($totalBulan > $pendapatanBulanTertinggi) {
                $pendapatanBulanTertinggi = $totalBulan;
                $bulanTertinggi = $bulanLabels[$i];
            }
        }

        $pendapatanBulanTertinggiFormatted = 'Rp ' . number_format($pendapatanBulanTertinggi, 0, ',', '.');

        // Rata-rata per bulan
        $jumlahBulanData = collect($grafikBulanan)->filter(fn($d) => ($d['motor'] + $d['mobil']) > 0)->count();
        $rataRataBulan = $jumlahBulanData > 0 ? $totalTahun / $jumlahBulanData : 0;
        $rataRataBulanFormatted = 'Rp ' . number_format($rataRataBulan, 0, ',', '.');

        // Detail bulanan untuk tabel
        $detailBulanan = [];
        for ($i = 0; $i < 12; $i++) {
            $motor = $grafikBulanan[$i]['motor'];
            $mobil = $grafikBulanan[$i]['mobil'];
            $total = $motor + $mobil;

            // Trend vs bulan lalu
            $trend = 0;
            if ($i > 0) {
                $totalLalu = $grafikBulanan[$i-1]['motor'] + $grafikBulanan[$i-1]['mobil'];
                if ($totalLalu > 0) {
                    $trend = round((($total - $totalLalu) / $totalLalu) * 100);
                }
            }

            $detailBulanan[] = [
                'bulan' => $bulanLabels[$i],
                'motorFormatted' => 'Rp ' . number_format($motor, 0, ',', '.'),
                'mobilFormatted' => 'Rp ' . number_format($mobil, 0, ',', '.'),
                'totalFormatted' => 'Rp ' . number_format($total, 0, ',', '.'),
                'trend' => $trend
            ];
        }

        // Pendapatan per area
        $pendapatanPerArea = AreaParkir::where('area_parkirs.status', 'aktif')
            ->select('nama_area', DB::raw('SUM(COALESCE(t.tarif_akhir, 0)) as total'))
            ->leftJoin('transaksis as t', function ($join) use ($tahun, $areaId) {
                $join->on('area_parkirs.id', '=', 't.area_id')
                     ->whereYear('t.waktu_masuk', $tahun);
                if ($areaId) {
                    $join->where('t.area_id', $areaId);
                }
            })
            ->groupBy('area_parkirs.id', 'area_parkirs.nama_area')
            ->orderByDesc('total')
            ->get()
            ->map(function ($area) {
                return [
                    'nama' => $area->nama_area,
                    'total' => $area->total,
                    'totalFormatted' => 'Rp ' . number_format($area->total, 0, ',', '.')
                ];
            })
            ->toArray();

        // Handle export PDF (placeholder)
        if ($request->get('export') === 'pdf') {
            // TODO: Implement PDF export
            return redirect()->back()->with('info', 'Export PDF belum diimplementasi');
        }

        $selectedArea = $areaId;

        return view('owner.grafik-pendapatan', compact(
            'tahun',
            'tahunList',
            'areaList',
            'selectedArea',
            'grafikBulanan',
            'totalTahunFormatted',
            'trendTahunan',
            'bulanTertinggi',
            'pendapatanBulanTertinggiFormatted',
            'rataRataBulanFormatted',
            'jumlahBulanData',
            'detailBulanan',
            'pendapatanPerArea'
        ));
    }
}
