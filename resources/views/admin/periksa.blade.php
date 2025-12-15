@extends('layouts.frontend')
@section('title', 'Periksa Surat | SIMAS-FTMM')

@section('navbar')
    <nav>
        <div class="logo">SIMAS-FTMM (Admin)</div>
        <a href="{{ route('admin.dashboard') }}" class="home-btn">Kembali ke Dashboard</a>
    </nav>
@endsection

@section('content')
<div class="container" style="justify-content: flex-start; padding-top: 50px; max-width: 900px; margin: 0 auto;">
    <h1 style="margin-bottom: 20px; width: 100%;">Detail Surat</h1>

    {{-- Card Detail Surat --}}
    <div class="card" style="animation: none; opacity: 1; transform: none; padding: 30px; text-align: left; cursor: default; width: 100%; margin-bottom: 40px;">
        <h3 style="font-size: 1.2rem; margin-bottom: 15px; color: #ffce00;">Informasi Pengajuan</h3>
        <p style="margin-bottom: 8px;"><strong>Jenis Surat:</strong> {{ $surat->jenisSurat->nama_surat ?? 'N/A' }}</p>
        <p style="margin-bottom: 8px;"><strong>Perihal:</strong> {{ $surat->perihal }}</p>
        <p style="margin-bottom: 8px;"><strong>Pemohon:</strong> {{ $surat->nama_pengaju }}</p>
        <p style="margin-bottom: 0;"><strong>Status Saat Ini:</strong> 
            <span class="status {{ $surat->status == 'Selesai' ? 'approved' : ($surat->status == 'Ditolak' ? 'rejected' : 'pending') }}">
                {{ $surat->status }}
            </span>
        </p>
    </div>

    {{-- Header Lampiran --}}
    <h3 style="align-self: flex-start; margin-bottom: 20px; font-size: 22px;">Lampiran</h3>
    
    @php
        $allLampirans = \App\Models\Lampiran::where('surat_id', $surat->surat_id)->orderBy('uploaded_at', 'asc')->get();
        $originalLampiran = $allLampirans->first();
    @endphp

    {{-- Tampilkan Preview PDF --}}
    @foreach($allLampirans->reverse() as $lampiran)
        <div style="width: 100%; border-radius: 15px; overflow: hidden; border: 3px solid rgba(255,255,255,0.2); margin-bottom: 20px;">
            <div style="background: #0d0c3b; color: #ffce00; padding: 10px 15px; font-weight: 600;">
                @if(str_contains($lampiran->nama_file, '_SIGNED_')) <span style="color: #00c853;">[Final Bertanda Tangan]</span> @endif
                Lampiran ({{ basename($lampiran->nama_file) }})
            </div>
            <embed src="{{ route('file.preview', ['filepath' => base64_encode($lampiran->file_path)]) }}" type="application/pdf" width="100%" height="700px" />
        </div>
    @endforeach

    @if($allLampirans->isEmpty())
        <p style="color:#ccc; margin-bottom: 40px;">Tidak ada lampiran.</p>
    @endif

    {{-- Tombol Download (DIPINDAH KE BAWAH PREVIEW & TEKS DIHAPUS) --}}
    @if(Auth::user()->role == 'Dekan' && $surat->status == 'Menunggu Dekan' && $originalLampiran)
        <div style="margin-bottom: 40px; text-align: center;">
             {{-- Teks "Perlu mengunduh..." SUDAH DIHAPUS --}}
             <a href="{{ route('file.preview', ['filepath' => base64_encode($originalLampiran->file_path)]) }}"
                download="{{ basename($originalLampiran->nama_file) }}"
                class="btn"
                style="background-color: #17a2b8; color: white; text-decoration: none; padding: 10px 20px; display: inline-flex; align-items: center; border-radius: 5px;">
                ⬇️ Unduh Lampiran Asli Pengaju
             </a>
        </div>
    @endif

    {{-- AREA AKSI --}}
    <div class="card" style="width: 100%; padding: 30px; background: #1a1859; border: 1px solid #2a2970; margin-top: 20px;">
        <h3 style="color: white; margin-bottom: 20px; text-align: center;">Tindakan</h3>
        
        @php
            $role = Auth::user()->role;
            
            // Logika untuk menyembunyikan tombol tolak (dari koreksi sebelumnya, TAPI HANYA DIGUNAKAN UNTUK MENENTUKAN LEBAR TOMBOL APPROVE)
            $noRejectStatuses = [
                'Menunggu Wakil Dekan 1', 
                'Kembali ke Sekretariat', 
                'Kembali ke Akademik', 
                'Kembali ke BEM',
            ];
            $canReject = !in_array($surat->status, $noRejectStatuses);
            
            if ($role == 'Dekan' && $surat->status != 'Menunggu Dekan') {
                $canReject = false; 
            }
        @endphp

        {{-- KOREKSI UTAMA: HANYA TAMPILKAN FORM JIKA $isActionable DARI CONTROLLER ADALAH TRUE --}}
        @if(isset($isActionable) && $isActionable)

            <form id="approve-form" action="{{ route('surat.approve', $surat->surat_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                {{-- Input File Khusus Dekan --}}
                @if($role == 'Dekan' && $surat->status == 'Menunggu Dekan')
                    <div style="margin-bottom: 25px; padding: 20px; background: rgba(0, 200, 83, 0.1); border: 1px dashed #00c853; border-radius: 10px;">
                        <label for="lampiran_dekan" style="color: #ffce00; display: block; margin-bottom: 10px; font-weight:bold; font-size: 1rem;">
                            Upload File Surat Final (Bertanda Tangan) <span style="color:#d50000;">*</span>
                        </label>
                        <input type="file" name="lampiran_dekan" id="lampiran_dekan" accept="application/pdf" required 
                               style="width: 100%; padding: 10px; background: #0d0c3b; border: 1px solid #2a2970; border-radius: 5px; color: white;">
                         <small style="color: #aaa; display: block; margin-top: 5px;">Format: PDF. Maksimal 2MB.</small>
                         @error('lampiran_dekan')
                             <div style="color: #ffb8b8; font-size: 0.9rem; margin-top: 5px;">{{ $message }}</div>
                         @enderror
                    </div>
                @endif

                {{-- Container Tombol --}}
                <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                    {{-- Tombol Approve --}}
                    <button type="button"
                            id="approve-button"
                            class="btn"
                            style="background-color: #00c853; min-width: {{ $canReject ? '200px' : '400px' }}; font-weight: bold; flex: 1; padding: 12px;"
                            data-title="{{ $confirmationTitle ?? 'Anda yakin?' }}"
                            data-text="{{ $confirmationText ?? 'Anda akan menyetujui dan meneruskan surat ini.' }}">
                        Approve & Teruskan
                    </button>

                    {{-- Tombol Tolak (Hanya muncul jika $canReject TRUE) --}}
                    @if ($canReject)
                        <button type="button" id="reject-button" class="btn" 
                                style="background-color: #d50000; min-width: 200px; font-weight: bold; border: 1px solid #ff5252; color: white; flex: 1; padding: 12px;">
                            Tolak Surat
                        </button>
                    @endif
                </div>
            </form>

            {{-- Form Reject Tersembunyi (Hanya di render jika $canReject TRUE) --}}
            @if ($canReject)
                <form id="reject-form" action="{{ route('surat.reject', $surat->surat_id) }}" method="POST" style="display:none;">
                    @csrf
                    <input type="hidden" name="catatan" id="catatan-input">
                </form>
            @endif
        
        @elseif($surat->status == 'Selesai' || $surat->status == 'Ditolak' || $surat->status == 'Dibatalkan')
             {{-- PESAN JIKA STATUS SUDAH FINAL (diambil dari kode status akhir Anda) --}}
            <div style="padding: 20px; background: rgba(255, 255, 255, 0.05); border-radius: 10px; text-align: center; width: 100%; color: white;">
                @if($surat->status == 'Selesai')
                    <div style="font-size: 40px; margin-bottom: 10px;">✅</div>
                    <h3 style="color: #00c853; margin-bottom: 5px;">Surat Selesai</h3>
                    <p>Proses persetujuan telah selesai.</p>
                @elseif($surat->status == 'Dibatalkan')
                    <div style="font-size: 40px; margin-bottom: 10px;">🚫</div>
                    <h3 style="color: #aaa; margin-bottom: 5px;">Surat Dibatalkan</h3>
                    <p>Surat ini telah dibatalkan oleh pemohon.</p>
                @else
                    <div style="font-size: 40px; margin-bottom: 10px;">❌</div>
                    <h3 style="color: #d50000; margin-bottom: 5px;">Surat Ditolak</h3>
                    <p>Surat ini tidak dapat diproses lebih lanjut.</p>
                @endif
            </div>
        @else
            {{-- PESAN JIKA STATUS PENDING TAPI BUKAN ROLE YANG TEPAT (HANYA MODE LIHAT) --}}
            <div style="padding: 20px; background: rgba(255, 255, 255, 0.05); border-radius: 10px; text-align: center; width: 100%; color: white;">
                <h3 style="color: #ffce00; margin-bottom: 5px;">Hanya Mode Lihat</h3>
                <p>Anda tidak berhak mengambil tindakan pada surat ini karena status saat ini adalah **{{ $surat->status }}**.</p>
            </div>
        @endif
        
    </div>
</div>
@endsection

@push('scripts')
<script>
const approveBtn = document.getElementById('approve-button');
if(approveBtn){
    approveBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const button = e.target.closest('button');
        Swal.fire({
            title: button.getAttribute('data-title'),
            text: button.getAttribute('data-text'),
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#00c853',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Lanjutkan!',
            background: '#1a1859', color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                const dekanFile = document.getElementById('lampiran_dekan');
                if (dekanFile && dekanFile.required && !dekanFile.files.length) {
                     Swal.fire({ title: 'File Wajib Diupload!', text: 'Anda harus mengunggah surat yang sudah ditandatangani.', icon: 'error', background: '#1a1859', color: '#fff' });
                } else {
                     document.getElementById('approve-form').submit();
                }
            }
        });
    });
}

const rejectBtn = document.getElementById('reject-button');
if(rejectBtn){
    rejectBtn.addEventListener('click', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Alasan Penolakan',
            input: 'textarea',
            inputPlaceholder: 'Tuliskan alasan...',
            showCancelButton: true,
            confirmButtonColor: '#d50000',
            confirmButtonText: 'Kirim Penolakan',
            background: '#1a1859', color: '#fff',
            inputValidator: (value) => { if (!value || value.length < 5) return 'Wajib diisi minimal 5 karakter!' }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('catatan-input').value = result.value;
                document.getElementById('reject-form').submit();
            }
        });
    });
}
</script>
@endpush