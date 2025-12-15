<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Surat;
use App\Models\Approval;
use App\Models\Lampiran; 

class ApprovalController extends Controller
{
    /**
     * Redirects index requests to the admin dashboard.
     */
    public function index()
    {
         return redirect()->route('admin.dashboard'); 
    }

    /**
     * Shows the details page for checking a letter.
     */
    public function show(Surat $surat)
    {
        $surat->load('jenisSurat', 'lampiran');

        $role = Auth::user()->role;
        $currentStatus = $surat->status;
        $actionVerb = 'meneruskan'; 
        
        // 1. TENTUKAN APAKAH PENGGUNA INI BERHAK BERTINDAK
        $isActionable = false;
        $requiredRole = str_replace(['Menunggu ', 'Kembali ke '], '', $currentStatus);
        
        // HANYA ROLE YANG DITUJUKAN OLEH STATUS SURAT YANG BOLEH BERTINDAK
        if ($role == $requiredRole) {
            $isActionable = true;
        }

        // Jika surat sudah final, tidak ada action.
        if (in_array($currentStatus, ['Selesai', 'Ditolak', 'Dibatalkan'])) {
             $isActionable = false;
        }


        // 2. LOGIKA UNTUK TEKS KONFIRMASI (nextStepTarget)
        $nextStepTarget = '';

        if ($role == "BEM" && $currentStatus == "Menunggu BEM") $nextStepTarget = "Akademik";
        elseif ($role == "Akademik" && $currentStatus == "Menunggu Akademik") $nextStepTarget = "Sekretariat";
        elseif ($role == "Sekretariat" && $currentStatus == "Menunggu Sekretariat") $nextStepTarget = "Dekan";
        elseif ($role == "Dekan" && $currentStatus == "Menunggu Dekan") $nextStepTarget = "Wakil Dekan 1";
        elseif ($role == "Wakil Dekan 1" && $currentStatus == "Menunggu Wakil Dekan 1") $nextStepTarget = "Sekretariat"; 
        
        // KOREKSI ALUR BALIK (REVISI)
        elseif ($role == "Sekretariat" && $currentStatus == "Kembali ke Sekretariat") $nextStepTarget = "Akademik";
        elseif ($role == "Akademik" && $currentStatus == "Kembali ke Akademik") $nextStepTarget = "BEM";
        elseif ($role == "BEM" && $currentStatus == "Kembali ke BEM") $nextStepTarget = $surat->nama_pengaju; 
        
        // KOREKSI UTAMA: Jika nextStepTarget tidak terisi, JANGAN set ke (tidak valid) di controller.
        // Biarkan kosong agar view bisa menggunakan default text, kecuali jika $isActionable=false.
        // Jika $isActionable=false, tombol tidak akan muncul, sehingga $nextStepTarget tidak terlalu penting.
        // Namun, jika $isActionable=true tapi logicnya salah, kita pakai default aman.
        if ($nextStepTarget == '') {
            $nextStepTarget = "proses berikutnya"; // Fallback aman
        }


        $confirmationTitle = 'Anda yakin?';
        $confirmationText = "Anda akan menyetujui dan $actionVerb surat ini ke $nextStepTarget.";

        if ($role == 'Dekan' && $currentStatus == 'Menunggu Dekan') {
            $confirmationText = "Anda telah menyetujui surat ini, dan akan diserahkan ke $nextStepTarget.";
        } 
        elseif (str_contains($currentStatus, 'Kembali ke') || $role == 'Wakil Dekan 1') {
             // Kondisi ini hanya untuk memastikan TEKS KONFIRMASI mengarah ke 'mengembalikan'
             $confirmationTitle = 'Kembalikan Surat?'; 
             $actionVerb = 'mengembalikan'; 
             $confirmationText = "Surat ini akan dikembalikan ke $nextStepTarget."; 
        }
        
        return view('admin.periksa', compact('surat', 'confirmationTitle', 'confirmationText', 'isActionable'));
    }


    /**
     * Processes letter approval based on the new workflow.
     */
    public function approve(Request $request, Surat $surat)
    {
        $role = Auth::user()->role;
        $currentStatus = $surat->status;
        $nextStatus = ''; 
        
        // GUARD Tambahan di Method Approve: Cegah Admin yang salah memproses
        $requiredRole = str_replace(['Menunggu ', 'Kembali ke '], '', $currentStatus);
        if ($role != $requiredRole) {
            return redirect()->route('admin.dashboard')->with('error', 'Otorisasi ditolak. Anda tidak berhak memproses surat ini.');
        }


        // --- VALIDASI WAJIB UPLOAD UNTUK DEKAN ---
        if ($role == 'Dekan' && $currentStatus == 'Menunggu Dekan') {
             $request->validate([
                 'lampiran_dekan' => 'required|file|mimes:pdf|max:2048' 
             ], [
                 'lampiran_dekan.required' => 'Anda wajib mengunggah file surat yang sudah ditandatangani.',
             ]);
        }
        // --- AKHIR VALIDASI ---

        // PERBAIKAN UTAMA: Mengisi logika switch dan MENSTANDARISASI STATUS WAKIL DEKAN
        switch ($role) {
            case 'BEM':
                if ($currentStatus == 'Menunggu BEM') $nextStatus = 'Menunggu Akademik';
                elseif ($currentStatus == 'Kembali ke BEM') $nextStatus = 'Selesai';
                break;
            case 'Akademik':
                if ($currentStatus == 'Menunggu Akademik') $nextStatus = 'Menunggu Sekretariat';
                elseif ($currentStatus == 'Kembali ke Akademik') $nextStatus = 'Kembali ke BEM';
                break;
            case 'Sekretariat':
                if ($currentStatus == 'Menunggu Sekretariat') $nextStatus = 'Menunggu Dekan';
                elseif ($currentStatus == 'Kembali ke Sekretariat') $nextStatus = 'Kembali ke Akademik';
                break;
            case 'Dekan':
                if ($currentStatus == 'Menunggu Dekan') $nextStatus = 'Menunggu Wakil Dekan 1'; 
                break;
            case 'Wakil Dekan 1':
                if ($currentStatus == 'Menunggu Wakil Dekan 1') $nextStatus = 'Kembali ke Sekretariat';
                break;
        }

        if ($nextStatus != "") {
            // Logika upload file (tetap sama)
            if ($role == 'Dekan' && $currentStatus == 'Menunggu Dekan' && $request->hasFile('lampiran_dekan')) {
                $file = $request->file('lampiran_dekan');
                $filename = time() . "_SIGNED_" . $file->getClientOriginalName();
                $path = $file->storeAs('uploads/signed', $filename, 'public'); 
                Lampiran::create([
                    'surat_id' => $surat->surat_id,
                    'nama_file' => $filename,
                    'file_path' => $path,
                ]);
            }

            $surat->update(['status' => $nextStatus]);
            Approval::create([
                'surat_id' => $surat->surat_id,
                'role' => $role,
                'status' => 'Approved',
                'approved_at' => now(),
            ]);
            return redirect()->route('admin.dashboard')->with('success', "Surat berhasil diproses ke status: $nextStatus.");
        }

        return redirect()->route('admin.dashboard')->with('error', 'Aksi approve tidak valid.');
    }

    public function reject(Request $request, Surat $surat)
    {
        $role = Auth::user()->role;
        $currentStatus = $surat->status;
        
        // GUARD Tambahan di Method Reject
        $requiredRole = str_replace(['Menunggu ', 'Kembali ke '], '', $currentStatus);
        if ($role != $requiredRole) {
            return redirect()->route('admin.dashboard')->with('error', 'Otorisasi ditolak. Anda tidak berhak menolak surat ini.');
        }
        
        // Tambahkan guard agar tidak bisa reject surat yang sudah melewati Dekan
        $noRejectStatuses = [
            'Menunggu Wakil Dekan 1', 
            'Kembali ke Sekretariat', 
            'Kembali ke Akademik', 
            'Kembali ke BEM',
        ];
        if (in_array($currentStatus, $noRejectStatuses)) {
             return redirect()->route('admin.dashboard')->with('error', 'Penolakan tidak diizinkan pada tahap ini.');
        }


        $request->validate(['catatan' => 'required|string|min:10']);
        
        $surat->update(['status' => 'Ditolak']); 
        Approval::create([
            'surat_id' => $surat->surat_id,
            'role' => $role,
            'status' => 'Rejected',
            'catatan' => $request->catatan,
            'approved_at' => now(),
        ]);
        return redirect()->route('admin.dashboard')->with('success', 'Surat berhasil ditolak.');
    }

    /**
     * Menampilkan preview file lampiran.
     */
    public function preview($filepath)
    {
        $decodedPath = base64_decode($filepath);
        if (strpos($decodedPath, 'uploads/') !== 0 && strpos($decodedPath, 'uploads/signed/') !== 0) { // Cek kedua folder
             abort(403, 'Akses ditolak.');
        }
        $path = storage_path('app/public/' . $decodedPath);

        if (!file_exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }
        return response()->file($path);
    }
}