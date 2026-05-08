<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\AreaParkir;
use App\Models\Transaksi;
use App\Models\LogAktivitas;
use App\Services\TransaksiService;
use App\Services\TarifService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PetugasTransaksiController extends Controller
{
    private TransaksiService $transaksiService;
    private TarifService $tarifService;

    public function __construct(TransaksiService $transaksiService, TarifService $tarifService)
    {
        $this->transaksiService = $transaksiService;
        $this->tarifService = $tarifService;
    }

    /**
     * Display list of transactions (parkir view)
     */
    public function index()
    {
        $kendaraanList = Kendaraan::all();
        $areaList = AreaParkir::where('status', 'aktif')->get();
        
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
     * Store new transaction (kendaraan masuk) - dengan validasi komprehensif
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

            // Record entry with comprehensive validation
            $transaksi = $this->transaksiService->recordEntry(
                $validated['kendaraan_id'],
                $validated['area_id']
            );
            
            // Update area occupancy status
            $area->updateStatusBasedOnOccupancy();

            // Log activity
            $user = Auth::user();
            LogAktivitas::create([
                'user_id' => $user->id,
                'aktivitas' => "Kendaraan {$kendaraan->plat_nomor} ({$kendaraan->jenis}) masuk parkir",
                'lokasi' => $area->nama_area ?? 'Unknown',
            ]);

            return redirect()->route('petugas.transaksi.index')
                ->with('success', "Kendaraan {$kendaraan->plat_nomor} berhasil dicatat masuk ke {$area->nama_area} ({$area->getOccupancyCount()}/{$area->kapasitas})");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Mark kendaraan keluar (exit) dengan perhitungan tarif dari database
     */
    public function keluar($id)
    {
        try {
            $transaksi = Transaksi::with(['kendaraan', 'area'])->findOrFail($id);

            if ($transaksi->status !== 'parkir') {
                return redirect()->route('petugas.transaksi.index')
                    ->with('error', 'Transaksi ini sudah selesai.');
            }

            // Record exit dengan perhitungan tarif dari database
            $transaksi = $this->transaksiService->recordExit($id, $this->tarifService);
            
            // Update area occupancy status
            $transaksi->area->updateStatusBasedOnOccupancy();

            // Log activity dengan informasi tarif
            $user = Auth::user();
            LogAktivitas::create([
                'user_id' => $user->id,
                'aktivitas' => "Kendaraan {$transaksi->kendaraan->plat_nomor} keluar parkir ({$transaksi->durasi_menit} menit) - Rp " . number_format($transaksi->tarif_akhir, 0, ',', '.'),
                'lokasi' => $transaksi->area->nama_area ?? 'Unknown',
            ]);

            return redirect()->route('petugas.transaksi.struk', ['id' => $id, 'autoprint' => '1']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * View receipt (struk) dengan format yang lebih baik
     */
    public function struk($id)
    {
        try {
            $transaksi = Transaksi::with(['kendaraan', 'area'])->findOrFail($id);

            // Get tariff information if available
            $tarif = $this->tarifService->getTariffByType($transaksi->kendaraan->jenis);

            return view('petugas.struk', compact('transaksi', 'tarif'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Struk tidak ditemukan.');
        }
    }

    /**
     * Get estimated tariff for a vehicle type
     * (untuk keperluan informasi/preview)
     */
    public function getEstimasiTarif($jenisKendaraan, $durasiMenit)
    {
        try {
            $estimasi = $this->tarifService->estimateTariff($jenisKendaraan, $durasiMenit);
            return response()->json(['success' => true, 'data' => $estimasi]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
}
