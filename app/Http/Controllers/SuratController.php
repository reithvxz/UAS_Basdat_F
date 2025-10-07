<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Pastikan ini ada untuk hapus file
use App\Models\Surat;
use App\Models\JenisSurat;
use App\Models\Ormawa;
use App\Models\Lampiran;

class SuratController extends Controller
{
    // Menampilkan daftar status surat mahasiswa
    public function index()
    {
        $mhs_id = Auth::guard('mahasiswa')->id();
        $surats = Surat::with('jenisSurat', 'lampiran')
                        ->where('mhs_id', $mhs_id)
                        ->orderBy('created_at', 'desc')
                        ->get();
        return view('mahasiswa.status', compact('surats'));
    }

    // Menampilkan form pengajuan
    public function create()
    {
        $jenisSurats = JenisSurat::all();
        $himas = Ormawa::where('tipe', 'HIMA')->get();
        $bsos = Ormawa::where('tipe', 'BSO')->get();
        return view('mahasiswa.pengajuan', compact('jenisSurats', 'himas', 'bsos'));
    }

    // Memproses form pengajuan
    public function store(Request $request)
    {
        $request->validate([
            'nama_pengaju' => 'required|string|max:255',
            'atas_nama' => 'required|string',
            'ormawa_id' => 'nullable|integer',
            'jenis_id' => 'required|integer',
            'tipe_surat' => 'required|in:Scan,Fisik',
            'perihal' => 'required|string|max:255',
            'lampiran' => 'required|file|mimes:pdf|max:2048',
        ]);

        $mhs_id = Auth::guard('mahasiswa')->id();
        $ormawa_id = null;

        if ($request->atas_nama == 'HIMA' || $request->atas_nama == 'BSO' || $request->atas_nama == 'BEM') {
            if($request->atas_nama == 'BEM') {
                $bem = Ormawa::where('tipe', 'BEM')->first();
                $ormawa_id = $bem ? $bem->ormawa_id : null;
            } else {
                $ormawa_id = $request->ormawa_id;
            }
        }
        
        // --- PERBAIKAN LOGIKA ADA DI SINI ---
        // Tentukan status awal berdasarkan 'atas_nama'
        $status = 'Menunggu BEM'; // Status default untuk Mahasiswa, HIMA, BSO
        if ($request->atas_nama == 'BEM') {
            $status = 'Menunggu Akademik'; // Jika atas nama BEM, langsung ke Akademik
        }

        $surat = Surat::create([
            'mhs_id' => $mhs_id,
            'nama_pengaju' => $request->nama_pengaju,
            'atas_nama' => $request->atas_nama,
            'ormawa_id' => $ormawa_id,
            'jenis_surat_id' => $request->jenis_id,
            'tipe_surat' => $request->tipe_surat,
            'perihal' => $request->perihal,
            'status' => $status,
        ]);

        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            $filename = time() . "_" . $file->getClientOriginalName();
            $path = $file->storeAs('uploads', $filename, 'public');

            Lampiran::create([
                'surat_id' => $surat->surat_id,
                'nama_file' => $filename,
                'file_path' => $path,
            ]);
        }

        return redirect()->route('status')->with('success', 'Surat berhasil diajukan!');
    }

    // Menghapus/membatalkan surat
    public function destroy(Surat $surat)
    {
        $mhs_id = Auth::guard('mahasiswa')->id();

        if ($surat->mhs_id != $mhs_id) {
            return redirect()->route('status')->with('error', 'Anda tidak berhak membatalkan surat ini.');
        }

        $surat->approvals()->delete();
        
        if ($surat->lampiran) {
            Storage::disk('public')->delete($surat->lampiran->file_path);
            $surat->lampiran->delete();
        }

        $surat->delete();

        return redirect()->route('status')->with('success', 'Pengajuan surat berhasil dibatalkan.');
    }

    // Menampilkan tracking surat dengan logika yang lebih detail
    public function tracking(Surat $surat)
    {
        // Definisikan semua kemungkinan status dalam alur proses
        $alur = ['Menunggu BEM', 'Menunggu Akademik', 'Menunggu Sekretariat', 'Menunggu Dekan'];
    
        // Cari di langkah keberapa surat ini sekarang (hasilnya berupa angka, misal: 0, 1, 2, dst.)
        $activeIndex = array_search($surat->status, $alur);

        // Dapatkan data penolakan jika ada
        $rejection = $surat->status == 'Ditolak' ? $surat->approvals->where('status', 'Rejected')->first() : null;

        // Kirim data surat, alur, posisi saat ini, dan data penolakan ke view
        return view('mahasiswa.tracking', compact('surat', 'alur', 'activeIndex', 'rejection'));
    }
}