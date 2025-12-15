<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Surat;
use App\Models\JenisSurat;
use App\Models\Ormawa;
use App\Models\Lampiran;
use App\Models\Approval;

class SuratController extends Controller
{
    // =========================================================================
    // 1. INDEX (FILTER STATUS DENGAN DIBATALKAN)
    // =========================================================================
    public function index(Request $request)
    {
        $mhs_id = Auth::guard('mahasiswa')->id();

        // 1. SETUP DEFAULT FILTER: "Proses"
        $defaultFilter = 'Proses';
        $filterStatus = $request->input('filter_status', $defaultFilter);

        // 2. QUERY DASAR
        $query = Surat::with('jenisSurat', 'lampiran')
                        ->where('mhs_id', $mhs_id);

        // 3. LOGIKA FILTER
        if ($filterStatus == 'Proses') {
            // Tampilkan yang sedang berjalan (Menunggu... atau Kembali ke...)
            $query->whereNotIn('status', ['Selesai', 'Ditolak', 'Dibatalkan']);
            
        } elseif ($filterStatus == 'Disetujui') {
            $query->where('status', 'Selesai');
            
        } elseif ($filterStatus == 'Ditolak') {
            $query->where('status', 'Ditolak');
            
        } elseif ($filterStatus == 'Dibatalkan') {
            // FITUR BARU: Filter khusus untuk surat yang dibatalkan
            $query->where('status', 'Dibatalkan');

        } elseif ($filterStatus == 'Semua') {
            // Tampilkan semua data tanpa filter status
        }

        // 4. URUTKAN & PAGINATE
        $surats = $query->orderBy('created_at', 'desc')->paginate(10);

        // Kirim variabel filterStatus agar view tahu tab mana yang aktif
        return view('mahasiswa.status', compact('surats', 'filterStatus'));
    }

    // =========================================================================
    // 2. CREATE (TIDAK BERUBAH)
    // =========================================================================
    public function create()
    {
        $jenisSurats = JenisSurat::all();
        $himas = Ormawa::where('tipe', 'HIMA')->get();
        $bsos = Ormawa::where('tipe', 'BSO')->get();
        return view('mahasiswa.pengajuan', compact('jenisSurats', 'himas', 'bsos'));
    }

    // =========================================================================
    // 3. STORE (TIDAK BERUBAH)
    // =========================================================================
    public function store(Request $request)
    {
        // Validasi awal
        $request->validate([
            'nama_pengaju' => 'required|string|max:255',
            'atas_nama' => 'required|string',
            'ormawa_id' => 'nullable|integer',
            'jenis_id' => 'required|integer',
            'tipe_surat' => 'required|in:Scan,Fisik',
            'perihal' => 'required|string|max:255',
            'lampiran' => 'required|file|mimes:pdf|max:2048',
        ]);

        // Validasi tambahan untuk Surat Rekomendasi
        $jenisIdRekomendasi = 3; 
        if ($request->atas_nama != 'Mahasiswa' && $request->jenis_id == $jenisIdRekomendasi) {
            return back()->withErrors(['jenis_id' => 'Surat Rekomendasi hanya dapat diajukan atas nama Mahasiswa (Pribadi).'])
                         ->withInput();
        }

        $mhs_id = Auth::guard('mahasiswa')->id();
        $ormawa_id = null;
        $atas_nama_input = $request->atas_nama;

        // Tentukan ormawa_id
        if (in_array($atas_nama_input, ['HIMA', 'BSO', 'BEM', 'BLM'])) {
            if ($atas_nama_input == 'BEM') {
                $ormawa = Ormawa::where('tipe', 'BEM')->first();
                $ormawa_id = $ormawa ? $ormawa->ormawa_id : null;
            } elseif ($atas_nama_input == 'BLM'){ 
                 $ormawa = Ormawa::where('tipe', 'BLM')->first();
                 $ormawa_id = $ormawa ? $ormawa->ormawa_id : null;
            } else { 
                $ormawa_id = $request->ormawa_id;
            }
        }

        // Tentukan status awal
        $status = 'Menunggu BEM';
        if ($atas_nama_input == 'BEM' || $atas_nama_input == 'BLM') { 
            $status = 'Menunggu Akademik'; 
        }

        $surat = Surat::create([
            'mhs_id' => $mhs_id,
            'nama_pengaju' => $request->nama_pengaju,
            'atas_nama' => $atas_nama_input,
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

    // =========================================================================
    // 4. DESTROY (TIDAK BERUBAH)
    // =========================================================================
    public function destroy(Surat $surat)
    {
        $mhs_id = Auth::guard('mahasiswa')->id();

        if ($surat->mhs_id != $mhs_id) {
            return redirect()->route('status')->with('error', 'Anda tidak berhak membatalkan surat ini.');
        }

        $surat->status = 'Dibatalkan';
        $surat->save();
        
        return redirect()->route('status')->with('success', 'Pengajuan surat berhasil dibatalkan.');
    }

    // =========================================================================
    // 5. TRACKING (TIDAK BERUBAH)
    // =========================================================================
    public function tracking(Surat $surat)
    {
        $rejection = $surat->status == 'Ditolak' ? $surat->approvals->where('status', 'Rejected')->first() : null;

        if ($surat->status == 'Dibatalkan') {
            return view('mahasiswa.tracking', compact('surat', 'rejection'))
                ->with(['isCanceled' => true]);
        }
        
        $alurVisual = [
            'Menunggu BEM', 
            'Menunggu Akademik', 
            'Menunggu Sekretariat', 
            'Menunggu Dekan', 
            'Menunggu Wakil Dekan', 
            'Kembali ke Sekretariat', 
            'Kembali ke Akademik', 
            'Kembali ke BEM',
            'Selesai'
        ];
    
        $activeIndex = array_search($surat->status, $alurVisual);

        return view('mahasiswa.tracking', compact('surat', 'alurVisual', 'activeIndex', 'rejection'))
               ->with(['isCanceled' => false]);
    }
}