<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Surat;
use App\Models\Approval;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // --- Mengambil Data Penting ---
        $userRole = Auth::user()->role;
        $targetStatus = 'Menunggu ' . $userRole;

        // --- Menyiapkan Data Statistik ---
        $menungguCount = Surat::where('status', $targetStatus)->count();
        $disetujuiCount = Approval::where('role', 'like', '%' . $userRole . '%')->where('status', 'Approved')->count();
        $ditolakCount = Approval::where('role', 'like', '%' . $userRole . '%')->where('status', 'Rejected')->count();

        // Total pengajuan yang pernah ditangani oleh role ini
        $totalPengajuan = $menungguCount + $disetujuiCount + $ditolakCount;

        // --- Menghitung Persentase Kenaikan (misal: 7 hari terakhir vs 7 hari sebelumnya) ---
        $pengajuanMingguIni = Surat::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        $pengajuanMingguLalu = Surat::whereBetween('created_at', [Carbon::now()->subDays(14), Carbon::now()->subDays(7)])->count();

        $persentaseKenaikan = 0;
        if ($pengajuanMingguLalu > 0) {
            $persentaseKenaikan = (($pengajuanMingguIni - $pengajuanMingguLalu) / $pengajuanMingguLalu) * 100;
        } elseif ($pengajuanMingguIni > 0) {
            $persentaseKenaikan = 100; // Jika sebelumnya 0, dan sekarang ada, anggap naik 100%
        }

        // --- Menyiapkan Data untuk Tabel Antrian ---
        $surats = Surat::with('jenisSurat') // Mengambil relasi jenisSurat agar lebih efisien
                        ->where('status', $targetStatus)
                        ->orderBy('created_at', 'asc')
                        ->paginate(10); // Menampilkan 10 surat per halaman

        // --- Mengirim Semua Data ke View ---
        return view('admin.dashboard', [
            'totalPengajuan' => $totalPengajuan,
            'persentaseKenaikan' => round($persentaseKenaikan),
            'menungguCount' => $menungguCount,
            'disetujuiCount' => $disetujuiCount,
            'ditolakCount' => $ditolakCount,
            'surats' => $surats,
            'userRole' => $userRole
        ]);
    }
}