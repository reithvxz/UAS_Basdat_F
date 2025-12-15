<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Surat;
use App\Models\JenisSurat;
use App\Models\Approval;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $userRole = Auth::user()->role;
        
        // =========================================================================
        // 1. SETUP DEFAULT VIEW & FILTER STATUS
        // =========================================================================
        $defaultFilterStatus = 'Pending'; 

        // Ambil filter status dari request, default ke 'Pending' jika tidak ada
        $filterStatus = $request->input('filter_status', $defaultFilterStatus);
        
        // Jika user memilih "Semua Status", set null agar query mengambil semua
        if ($filterStatus == 'Semua Status') {
            $filterStatus = null; 
        }
        
        // =========================================================================
        // 2. PERIODE ANALISIS (FILTER WAKTU)
        // =========================================================================
        $periode = $request->input('periode_analisis', 'Tahun Ini'); 
        
        $startDate = null;
        $endDate = Carbon::now();
        $groupBy = 'bulan';

        if ($periode == '7 Hari Terakhir') {
            $startDate = Carbon::now()->subDays(7);
            $groupBy = 'tanggal';
        } elseif ($periode == 'Bulan Ini') {
            $startDate = Carbon::now()->startOfMonth();
            $groupBy = 'tanggal';
        } else {
            // Default: Tahun Ini
            $startDate = Carbon::now()->startOfYear();
            $groupBy = 'bulan';
        }

        // =========================================================================
        // 3. QUERY UTAMA (TABEL ANTRIAN)
        // =========================================================================
        $query = Surat::with('jenisSurat', 'mahasiswa')
                        // Jangan tampilkan surat yang dibatalkan mahasiswa
                        ->where('status', '!=', 'Dibatalkan')
                        // Filter waktu sesuai periode yang dipilih
                        ->whereBetween('created_at', [$startDate, $endDate]); 

        // --- FILTER STATUS & LOGIKA SORTING PRIORITAS ---
        if ($filterStatus) { 
            if ($filterStatus == 'Pending') {
                // A. Filter surat yang sedang berada di meja User ini
                $query->whereIn('status', ['Menunggu ' . $userRole, 'Kembali ke ' . $userRole]);
                
                // LOGIKA PRIORITAS: 'Kembali ke...' (0) lebih dulu daripada 'Menunggu...' (1)
                $statusKembali = 'Kembali ke ' . $userRole;
                $query->orderByRaw("CASE WHEN status = ? THEN 0 ELSE 1 END ASC", [$statusKembali]);
                
                // Prioritas Waktu: FIFO (First In First Out) - Yang paling lama menunggu di atas
                $query->orderBy('updated_at', 'asc'); 

            } elseif ($filterStatus == 'Approved') {
                // B. Filter History Approved
                $query->whereHas('approvals', function ($q) use ($userRole) {
                    $q->where('role', $userRole)->where('status', 'Approved');
                })
                ->where('status', '!=', 'Ditolak'); // Pastikan status akhirnya bukan Ditolak
                
                // History urutkan dari yang Ter-Baru (LIFO)
                $query->orderBy('created_at', 'desc');

            } elseif ($filterStatus == 'Rejected') {
                // C. Filter History Rejected
                $query->whereHas('approvals', function ($q) use ($userRole) {
                    $q->where('role', $userRole)->where('status', 'Rejected');
                });
                
                // History urutkan dari yang Ter-Baru (LIFO)
                $query->orderBy('created_at', 'desc');
            }
        } else {
            // D. Jika "Semua Status", urutkan dari yang Ter-Baru
            $query->orderBy('created_at', 'desc');
        }
        
        // Filter Jenis Surat (Dropdown)
        if ($request->has('filter_jenis') && $request->filter_jenis != 'Semua Jenis') {
            $query->whereHas('jenisSurat', function($q) use ($request) {
                $q->where('nama_surat', $request->filter_jenis);
            });
        }

        // Eksekusi Pagination
        $surats = $query->paginate(10);
        
        // =========================================================================
        // 4. LOGIKA URGENCY WARNING (TANDA PENTING DI TABEL)
        // =========================================================================
        $pendingStatusCheck = ['Menunggu ' . $userRole, 'Kembali ke ' . $userRole];

        // Hanya hitung urgency jika sedang melihat tab Pending
        if ($filterStatus == 'Pending') {
            $currentTime = Carbon::now();
            
            foreach ($surats as $surat) {
                // Pastikan surat memang status pending user ini
                if (in_array($surat->status, $pendingStatusCheck)) {
                    
                    // Gunakan updated_at untuk menghitung berapa lama surat "diam" di status ini
                    $dateToCheck = $surat->updated_at; 
                    $daysDiff = $dateToCheck->diffInDays($currentTime);

                    $surat->urgency_warning = '';
                    $surat->urgency_class = '';
                    
                    if ($daysDiff >= 7) {
                        $surat->urgency_warning = "Surat ini sudah lebih dari 1 MINGGU di antrian, segera proses!";
                        $surat->urgency_class = 'danger'; // Merah
                    } elseif ($daysDiff >= 3) {
                        $surat->urgency_warning = "Segera proses surat ini, sudah lebih dari 3 HARI di antrian.";
                        $surat->urgency_class = 'warning'; // Kuning
                    }
                }
            }
        }

        // =========================================================================
        // 5. DATA PENDUKUNG STATISTIK (KARTU ATAS)
        // =========================================================================
        $jenisSuratList = JenisSurat::all();

        // Hitung Jumlah Pending (Personal - Yang harus dikerjakan user)
        $menungguCount = Surat::whereIn('status', ['Menunggu ' . $userRole, 'Kembali ke ' . $userRole])
                                ->whereBetween('created_at', [$startDate, $endDate])
                                ->count();
        
        // Hitung Jumlah Disetujui (Personal - History user)
        $disetujuiCount = Surat::whereHas('approvals', function ($q) use ($userRole) {
                                    $q->where('role', $userRole)->where('status', 'Approved');
                                })
                               ->whereBetween('created_at', [$startDate, $endDate])
                               ->count();

        // Hitung Jumlah Ditolak (Personal - History user)
        $ditolakCount = Surat::whereHas('approvals', function ($q) use ($userRole) {
                                    $q->where('role', $userRole)->where('status', 'Rejected');
                                })
                             ->whereBetween('created_at', [$startDate, $endDate])
                             ->count();
        
        // Hitung Total Ditangani (GLOBAL - Menghitung semua surat masuk sistem)
        $totalPengajuan = Surat::where('status', '!=', 'Dibatalkan')
                               ->whereBetween('created_at', [$startDate, $endDate])
                               ->count();

        // =========================================================================
        // 6. QUERY GRAFIK & PERHITUNGAN TOTAL (UNTUK DASHBOARD)
        // =========================================================================

        // --- A. GRAPH 1: TREN SURAT MASUK ---
        $trenQuery = Surat::select(
                DB::raw($groupBy == 'bulan' ? 'MONTHNAME(created_at) as label' : 'DATE_FORMAT(created_at, "%d %b") as label'), 
                DB::raw('count(*) as total')
            )
            ->where('status', '!=', 'Dibatalkan')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('label')
            ->orderBy('created_at', 'asc')
            ->pluck('total', 'label');
        
        // >>> 1. HITUNG TOTAL TREN (Sum semua bar)
        $totalTren = $trenQuery->sum();

        // --- B. GRAPH 2: STATUS SUMMARY (RASIO) ---
        $totalSelesaiGlobal = Surat::where('status', 'Selesai')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
            
        $totalDitolakGlobal = Surat::where('status', 'Ditolak')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $totalProsesGlobal = Surat::whereNotIn('status', ['Selesai', 'Ditolak', 'Dibatalkan'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $statusSummary = [
            'Selesai' => $totalSelesaiGlobal,
            'Ditolak' => $totalDitolakGlobal,
            'Proses'  => $totalProsesGlobal 
        ];
        
        // >>> 2. HITUNG TOTAL KEPUTUSAN (Total Pie Chart)
        $totalKeputusan = $totalSelesaiGlobal + $totalDitolakGlobal + $totalProsesGlobal;
        
        // --- C. GRAPH 3: TOTAL PER JENIS SURAT ---
        $jenisQuery = Surat::select('jenis_surats.nama_surat', DB::raw('count(*) as total'))
            ->join('jenis_surats', 'surats.jenis_surat_id', '=', 'jenis_surats.jenis_id')
            ->where('surats.status', '!=', 'Dibatalkan')
            ->whereBetween('surats.created_at', [$startDate, $endDate])
            ->groupBy('jenis_surats.nama_surat')
            ->pluck('total', 'jenis_surats.nama_surat'); 
            
        // >>> 3. HITUNG TOTAL JENIS (Sum semua jenis)
        $totalJenis = $jenisQuery->sum();

        // --- D. GRAPH 4: DURASI LAYANAN ---
        $durasiQuery = DB::table('surats')
            ->join('jenis_surats', 'surats.jenis_surat_id', '=', 'jenis_surats.jenis_id')
            ->select('jenis_surats.nama_surat', DB::raw('AVG(TIMESTAMPDIFF(HOUR, surats.created_at, surats.updated_at)) as rata_jam'))
            ->where('surats.status', 'Selesai') 
            ->whereBetween('surats.created_at', [$startDate, $endDate])
            ->groupBy('jenis_surats.nama_surat')
            ->pluck('rata_jam', 'jenis_surats.nama_surat'); 
        
        // >>> 4. HITUNG RATA-RATA GLOBAL (Avg dari semua durasi)
        $avgDurasi = $durasiQuery->isNotEmpty() ? round($durasiQuery->avg(), 1) : 0;

        // =========================================================================
        // 7. RETURN VIEW
        // =========================================================================
        
        return view('admin.dashboard', compact(
            'surats', 
            'userRole', 
            'jenisSuratList',
            'menungguCount', 
            'disetujuiCount', 
            'ditolakCount', 
            'totalPengajuan',
            'trenQuery', 
            'jenisQuery', 
            'statusSummary', 
            'durasiQuery', 
            'periode',
            'filterStatus',
            // Variabel Baru (Total/Avg) untuk ditampilkan di header grafik:
            'totalTren', 'totalKeputusan', 'totalJenis', 'avgDurasi'
        ));
    }
}