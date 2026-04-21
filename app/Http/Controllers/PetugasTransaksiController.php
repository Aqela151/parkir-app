<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\AreaParkir;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PetugasTransaksiController extends Controller
{
    /**
     * Display list of transactions (parkir view)
     */
    public function index()
    {
        $kendaraanList = Kendaraan::all();
        $areaList = AreaParkir::all();
        $kendaraanParkir = Transaksi::where('status', 'parkir')
            ->with(['kendaraan', 'area'])
            ->orderBy('waktu_masuk', 'desc')
            ->get();

        return view('petugas.transaksi', compact('kendaraanList', 'areaList', 'kendaraanParkir'));
    }

    /**
     * Display transaction history with filters and pagination
     */
    public function riwayat(Request $request)
    {
        $query = Transaksi::with(['kendaraan', 'area']);

        // Filter by search (plat nomor)
        if ($request->filled('search')) {
            $query->whereHas('kendaraan', function($q) use ($request) {
                $q->where('plat_nomor', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by jenis kendaraan
        if ($request->filled('jenis')) {
            $query->whereHas('kendaraan', function($q) use ($request) {
                $q->where('jenis', $request->jenis);
            });
        }

        $transaksis = $query->orderBy('waktu_masuk', 'desc')->paginate(15);

        return view('petugas.riwayat-transaksi', compact('transaksis'));
    }

    /**
     * Store new transaction (kendaraan masuk)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kendaraan_id' => 'required|exists:kendaraans,id',
            'area_id' => 'required|exists:area_parkirs,id',
        ]);

        try {
            $kendaraan = Kendaraan::findOrFail($validated['kendaraan_id']);
            $area = AreaParkir::findOrFail($validated['area_id']);

            Transaksi::create([
                'kendaraan_id' => $validated['kendaraan_id'],
                'area_id' => $validated['area_id'],
                'waktu_masuk' => Carbon::now('Asia/Jakarta'),
                'status' => 'parkir',
                'tarif_sementara' => 0,
            ]);

            return redirect()->route('petugas.transaksi.index')
                ->with('success', "Kendaraan {$kendaraan->plat_nomor} berhasil dicatat masuk ke {$area->nama_area}");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mencatat transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Mark kendaraan keluar (exit)
     */
    public function keluar($id)
    {
        try {
            $transaksi = Transaksi::with(['kendaraan', 'area'])->findOrFail($id);

            if ($transaksi->status !== 'parkir') {
                return redirect()->route('petugas.transaksi.index')
                    ->with('error', 'Transaksi ini sudah selesai.');
            }

            $waktuKeluar = Carbon::now('Asia/Jakarta');
            $durasiMenit = $transaksi->waktu_masuk->diffInMinutes($waktuKeluar);

            $tarifAkhir = max(5000, ceil($durasiMenit / 60) * 5000);

            $transaksi->update([
                'waktu_keluar' => $waktuKeluar,
                'durasi_menit' => $durasiMenit,
                'tarif_akhir' => $tarifAkhir,
                'status' => 'selesai',
            ]);

            return redirect()->route('petugas.transaksi.index')
                ->with('success', "Kendaraan {$transaksi->kendaraan->plat_nomor} berhasil keluar.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memproses keluar: ' . $e->getMessage());
        }
    }

    /**
     * View receipt (struk)
     */
    public function struk($id)
    {
        try {
            $transaksi = Transaksi::with(['kendaraan', 'area'])->findOrFail($id);

            return view('petugas.struk', compact('transaksi'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Struk tidak ditemukan.');
        }
    }
}
