<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Surat;
use App\Models\Approval;

class ApprovalController extends Controller
{
    // Method ini tidak perlu diubah, tetap menampilkan daftar surat masuk jika diakses
    public function index()
    {
        $role = Auth::user()->role;
        $status_target = "Menunggu " . $role;

        $surats = Surat::with('jenisSurat', 'lampiran')
                        ->where('status', $status_target)
                        ->orderBy('created_at', 'asc')
                        ->get();
        return view('admin.surat_masuk', compact('surats', 'role'));
    }

    // Method ini tidak perlu diubah, tetap menampilkan halaman detail/periksa
    public function show(Surat $surat)
    {
        $surat->load('jenisSurat', 'lampiran');
        return view('admin.periksa', compact('surat'));
    }

    // Memproses persetujuan (approve.php?aksi=approve)
    public function approve(Surat $surat)
    {
        $role = Auth::user()->role;
        $next_status = "";

        if ($role == "BEM") $next_status = "Menunggu Akademik";
        elseif ($role == "Akademik") $next_status = "Menunggu Sekretariat";
        elseif ($role == "Sekretariat") $next_status = "Menunggu Dekan";
        elseif ($role == "Dekan") $next_status = "Disetujui";

        if ($next_status != "") {
            $surat->update(['status' => $next_status]);
            Approval::create([
                'surat_id' => $surat->surat_id,
                'role' => $role,
                'status' => 'Approved',
                'approved_at' => now(),
            ]);

            // PERUBAHAN: Arahkan kembali ke dashboard admin dengan pesan sukses
            return redirect()->route('admin.dashboard')
                             ->with('success', "Surat berhasil diteruskan ke $next_status.");
        }

        // Jika terjadi error, kembali ke dashboard admin dengan pesan error
        return redirect()->route('admin.dashboard')->with('error', 'Terjadi kesalahan saat memproses surat.');
    }

    // Memproses penolakan (approve.php?aksi=reject)
    public function reject(Request $request, Surat $surat)
    {
        $request->validate(['catatan' => 'required|string|min:10']);
        $role = Auth::user()->role;

        $surat->update(['status' => 'Ditolak']);
        Approval::create([
            'surat_id' => $surat->surat_id,
            'role' => $role,
            'status' => 'Rejected',
            'catatan' => $request->catatan,
            'approved_at' => now(),
        ]);

        // PERUBAHAN: Arahkan kembali ke dashboard admin dengan pesan sukses
        return redirect()->route('admin.dashboard')
                         ->with('success', 'Surat berhasil ditolak.');
    }

     // Method ini tidak perlu diubah, tetap untuk menampilkan preview file
    public function preview($filepath)
    {
        $path = storage_path('app/public/' . base64_decode($filepath));

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }
}