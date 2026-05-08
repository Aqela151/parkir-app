<?php

namespace App\Http\Controllers;

use App\Models\AreaParkir;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class OwnerController extends Controller
{
    public function dashboard()
    {
        // Pendapatan hari ini (berdasarkan waktu_keluar, hanya transaksi selesai)
        $pendapatanHariIni = Transaksi::whereDate('waktu_keluar', today())
            ->whereNotNull('tarif_akhir')
            ->sum('tarif_akhir') ?? 0;
        $pendapatanHariIniFormatted = 'Rp ' . number_format($pendapatanHariIni, 0, ',', '.');

        // Trend pendapatan (hari ini vs kemarin)
        $pendapatanKemarin = Transaksi::whereDate('waktu_keluar', today()->subDay())
            ->whereNotNull('tarif_akhir')
            ->sum('tarif_akhir') ?? 0;
        $pendapatanTrend = $pendapatanKemarin > 0 ? round((($pendapatanHariIni - $pendapatanKemarin) / $pendapatanKemarin) * 100) : 0;

        // Transaksi hari ini
        $transaksiHariIni = Transaksi::whereDate('waktu_masuk', today())->count();
        $lokasiAktif = AreaParkir::where('status', 'aktif')->count() . ' lokasi';

        // Pendapatan bulan ini (berdasarkan waktu_keluar)
        $pendapatanBulanIni = Transaksi::whereMonth('waktu_keluar', date('m'))
            ->whereYear('waktu_keluar', date('Y'))
            ->whereNotNull('tarif_akhir')
            ->sum('tarif_akhir') ?? 0;
        $pendapatanBulanIniFormatted = 'Rp ' . number_format($pendapatanBulanIni, 0, ',', '.');

        // Trend bulan ini vs bulan lalu (berdasarkan waktu_keluar)
        $pendapatanBulanLalu = Transaksi::whereMonth('waktu_keluar', date('m') - 1)
            ->whereYear('waktu_keluar', date('Y'))
            ->whereNotNull('tarif_akhir')
            ->sum('tarif_akhir') ?? 0;
        $pendapatanBulanTrend = $pendapatanBulanLalu > 0 ? round((($pendapatanBulanIni - $pendapatanBulanLalu) / $pendapatanBulanLalu) * 100) : 0;

        // Jumlah area aktif
        $jumlahAreaAktif = AreaParkir::where('status', 'aktif')->count();
        
        // Kapasitas rata-rata
        $kapasitasRataRata = 0;
        if ($jumlahAreaAktif > 0) {
            $totalKapasitas = AreaParkir::where('status', 'aktif')->sum('kapasitas') ?? 0;
            $totalTerisi = Transaksi::where('status', 'parkir')->count();
            $kapasitasRataRata = $totalKapasitas > 0 ? round(($totalTerisi / $totalKapasitas) * 100) : 0;
        }

        // Chart harian (7 hari terakhir, berdasarkan waktu_keluar)
        $chartHarian = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $motor = Transaksi::whereDate('waktu_keluar', $date)
                ->whereHas('kendaraan', function ($q) {
                    $q->where('jenis', 'Motor');
                })
                ->whereNotNull('tarif_akhir')
                ->sum('tarif_akhir') ?? 0;

            $mobil = Transaksi::whereDate('waktu_keluar', $date)
                ->whereHas('kendaraan', function ($q) {
                    $q->where('jenis', 'Mobil');
                })
                ->whereNotNull('tarif_akhir')
                ->sum('tarif_akhir') ?? 0;

            $chartHarian[] = [
                'motor' => $motor,
                'mobil' => $mobil
            ];
        }

        // Total masuk/keluar hari ini
        $totalMasuk = Transaksi::whereDate('waktu_masuk', today())->count();
        $totalKeluar = Transaksi::whereDate('waktu_keluar', today())->whereNotNull('waktu_keluar')->count();

        // Pendapatan bulan lalu
        $pendapatanBulanLaluFormatted = 'Rp ' . number_format($pendapatanBulanLalu, 0, ',', '.');

        // Pendapatan per area (bulan ini, berdasarkan waktu_keluar)
        $pendapatanPerArea = AreaParkir::where('area_parkirs.status', 'aktif')
            ->select('area_parkirs.id', 'area_parkirs.nama_area as nama', 'area_parkirs.lokasi as alamat', DB::raw('SUM(COALESCE(t.tarif_akhir, 0)) as pendapatan'))
            ->leftJoin('transaksis as t', function ($join) {
                $join->on('area_parkirs.id', '=', 't.area_id')
                     ->whereMonth('t.waktu_keluar', date('m'))
                     ->whereYear('t.waktu_keluar', date('Y'))
                     ->whereNotNull('t.tarif_akhir');
            })
            ->groupBy('area_parkirs.id', 'area_parkirs.nama_area', 'area_parkirs.lokasi')
            ->orderByDesc('pendapatan')
            ->get()
            ->map(function ($area) {
                return [
                    'nama' => $area->nama,
                    'alamat' => $area->alamat,
                    'pendapatan' => $area->pendapatan,
                    'pendapatanFormatted' => 'Rp ' . number_format($area->pendapatan, 0, ',', '.')
                ];
            })
            ->toArray();

        // Area tersibuk hari ini
        $areaTersibuk = Transaksi::whereDate('waktu_masuk', today())
            ->select('area_id', DB::raw('COUNT(*) as count'))
            ->groupBy('area_id')
            ->orderByDesc('count')
            ->first();
        $areaTersibuk = AreaParkir::find($areaTersibuk?->area_id)?->nama_area ?? '-';

        // Jam tersibuk hari ini
        $jamTersibuk = Transaksi::whereDate('waktu_masuk', today())
            ->select(DB::raw('HOUR(waktu_masuk) as jam'), DB::raw('COUNT(*) as count'))
            ->groupBy(DB::raw('HOUR(waktu_masuk)'))
            ->orderByDesc('count')
            ->first();
        $jamTersibuk = $jamTersibuk ? $jamTersibuk->jam . ':00' : '-';

        // Petugas aktif
        $petugasAktif = \App\Models\User::where('role', 'petugas')
            ->where('status', 'aktif')
            ->count();

        return view('owner.dashboard', compact(
            'pendapatanHariIniFormatted',
            'pendapatanTrend',
            'transaksiHariIni',
            'lokasiAktif',
            'pendapatanBulanIniFormatted',
            'pendapatanBulanTrend',
            'jumlahAreaAktif',
            'kapasitasRataRata',
            'chartHarian',
            'totalMasuk',
            'totalKeluar',
            'pendapatanBulanLaluFormatted',
            'pendapatanPerArea',
            'areaTersibuk',
            'jamTersibuk',
            'petugasAktif'
        ));
    }

    public function rekapTransaksi(Request $request)
    {
        $areas = AreaParkir::where('status', 'aktif')->get();

        $query = Transaksi::with(['kendaraan', 'area'])->whereNotNull('tarif_akhir');

        if ($request->filled('dari_tanggal')) {
            $query->whereDate('waktu_keluar', '>=', $request->dari_tanggal);
        }

        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('waktu_keluar', '<=', $request->sampai_tanggal);
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
                $query->orderBy('waktu_keluar');
                break;
            default:
                $query->orderByDesc('waktu_keluar');
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

        // Data grafik bulanan (berdasarkan waktu_keluar)
        $grafikBulanan = [];
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $query = Transaksi::whereYear('waktu_keluar', $tahun)
                ->whereMonth('waktu_keluar', $bulan)
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

        // Trend tahunan (vs tahun lalu, berdasarkan waktu_keluar)
        $totalTahunLalu = Transaksi::whereYear('waktu_keluar', $tahun - 1)
            ->whereNotNull('tarif_akhir')
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
                     ->whereYear('t.waktu_keluar', $tahun)
                     ->whereNotNull('t.tarif_akhir');
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

        // Handle export XLSX
        if ($request->get('export') === 'xlsx') {
            $fileName = 'grafik-pendapatan-' . $tahun . '-' . ($areaId ? AreaParkir::find($areaId)?->nama_area : 'semua-area') . '.xlsx';
            
            $spreadsheet = new Spreadsheet();
            
            // Sheet 1: Ringkasan
            $sheet1 = $spreadsheet->getActiveSheet();
            $sheet1->setTitle('Ringkasan');
            $sheet1->setCellValue('A1', 'Laporan Grafik Pendapatan Tahun ' . $tahun);
            $sheet1->setCellValue('A2', 'Tanggal Export: ' . now()->format('d-m-Y H:i:s'));
            $sheet1->setCellValue('A3', 'Area: ' . ($areaId ? AreaParkir::find($areaId)?->nama_area : 'Semua Area'));
            
            $sheet1->setCellValue('A5', 'Keterangan');
            $sheet1->setCellValue('B5', 'Nilai');
            $sheet1->setCellValue('A6', 'Total Pendapatan Tahun');
            $sheet1->setCellValue('B6', str_replace('Rp ', '', $totalTahunFormatted));
            $sheet1->setCellValue('A7', 'Bulan Tertinggi');
            $sheet1->setCellValue('B7', $bulanTertinggi);
            $sheet1->setCellValue('A8', 'Pendapatan Bulan Tertinggi');
            $sheet1->setCellValue('B8', str_replace('Rp ', '', $pendapatanBulanTertinggiFormatted));
            $sheet1->setCellValue('A9', 'Rata-rata per Bulan');
            $sheet1->setCellValue('B9', str_replace('Rp ', '', $rataRataBulanFormatted));
            $sheet1->setCellValue('A10', 'Trend vs Tahun Lalu');
            $sheet1->setCellValue('B10', ($trendTahunan > 0 ? '+' : '') . $trendTahunan . '%');
            
            $sheet1->getColumnDimension('A')->setAutoSize(true);
            $sheet1->getColumnDimension('B')->setAutoSize(true);
            
            // Sheet 2: Per Bulan
            $sheet2 = $spreadsheet->createSheet();
            $sheet2->setTitle('Per Bulan');
            $sheet2->setCellValue('A1', 'Bulan');
            $sheet2->setCellValue('B1', 'Motor');
            $sheet2->setCellValue('C1', 'Mobil');
            $sheet2->setCellValue('D1', 'Total');
            $sheet2->setCellValue('E1', 'Trend vs Bulan Lalu');
            
            $row = 2;
            foreach ($detailBulanan as $item) {
                $sheet2->setCellValue('A' . $row, $item['bulan']);
                $sheet2->setCellValue('B' . $row, str_replace('Rp ', '', $item['motorFormatted']));
                $sheet2->setCellValue('C' . $row, str_replace('Rp ', '', $item['mobilFormatted']));
                $sheet2->setCellValue('D' . $row, str_replace('Rp ', '', $item['totalFormatted']));
                $sheet2->setCellValue('E' . $row, ($item['trend'] > 0 ? '+' : '') . $item['trend'] . '%');
                $row++;
            }
            
            foreach (['A', 'B', 'C', 'D', 'E'] as $col) {
                $sheet2->getColumnDimension($col)->setAutoSize(true);
            }
            
            // Sheet 3: Per Area
            $sheet3 = $spreadsheet->createSheet();
            $sheet3->setTitle('Per Area');
            $sheet3->setCellValue('A1', 'Rank');
            $sheet3->setCellValue('B1', 'Area');
            $sheet3->setCellValue('C1', 'Total Pendapatan');
            
            $row = 2;
            foreach ($pendapatanPerArea as $i => $area) {
                $sheet3->setCellValue('A' . $row, $i + 1);
                $sheet3->setCellValue('B' . $row, $area['nama']);
                $sheet3->setCellValue('C' . $row, str_replace('Rp ', '', $area['totalFormatted']));
                $row++;
            }
            
            foreach (['A', 'B', 'C'] as $col) {
                $sheet3->getColumnDimension($col)->setAutoSize(true);
            }
            
            $writer = new Xlsx($spreadsheet);
            $response = response()->stream(function() use ($writer) {
                $writer->save('php://output');
            }, 200, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            ]);
            
            return $response;
        }

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

    public function performaArea()
    {
        try {
            $performaArea = AreaParkir::where('status', 'aktif')
                ->get()
                ->map(function ($area) {
                    $kapasitas = (int)($area->kapasitas ?? 0);
                    
                    $terisi = (int) Transaksi::where('area_id', $area->id)
                        ->where('status', 'parkir')
                        ->count();

                    $txBulanIni = (int) Transaksi::where('area_id', $area->id)
                        ->whereMonth('waktu_keluar', date('m'))
                        ->whereYear('waktu_keluar', date('Y'))
                        ->whereNotNull('tarif_akhir')
                        ->count();

                    $pendapatan = (float)(Transaksi::where('area_id', $area->id)
                        ->whereMonth('waktu_keluar', date('m'))
                        ->whereYear('waktu_keluar', date('Y'))
                        ->whereNotNull('tarif_akhir')
                        ->sum('tarif_akhir') ?? 0);

                    $rataDurasi = (int) round(
                        Transaksi::where('area_id', $area->id)
                            ->whereMonth('waktu_keluar', date('m'))
                            ->whereYear('waktu_keluar', date('Y'))
                            ->whereNotNull('tarif_akhir')
                            ->avg('durasi_menit') ?? 0
                    );

                    return [
                        'nama' => $area->nama_area ?? 'Area',
                        'alamat' => $area->lokasi ?? 'Lokasi belum diisi',
                        'kapasitas' => $kapasitas,
                        'terisi' => $terisi,
                        'tx_bulan_ini' => $txBulanIni,
                        'pendapatan' => $pendapatan,
                        'rata_durasi' => $rataDurasi,
                    ];
                })
                ->values()
                ->toArray();

            return view('owner.performa-area', ['performaArea' => $performaArea]);
        } catch (\Exception $e) {
            \Log::error('Error in performaArea: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
            return view('owner.performa-area', ['performaArea' => []]);
        }
    }
}
