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

    <div class="card" style="animation: none; opacity: 1; transform: none; padding: 30px; text-align: left; cursor: default; width: 100%; margin-bottom: 40px;">
        <h3 style="font-size: 1.2rem; margin-bottom: 15px; color: #ffce00;">Informasi Pengajuan</h3>
        <p style="margin-bottom: 8px;"><strong>Jenis Surat:</strong> {{ $surat->jenisSurat->nama_surat ?? 'N/A' }}</p>
        <p style="margin-bottom: 8px;"><strong>Perihal:</strong> {{ $surat->perihal }}</p>
        <p style="margin-bottom: 8px;"><strong>Pemohon:</strong> {{ $surat->nama_pengaju }}</p>
        <p style="margin-bottom: 0;"><strong>Status Saat Ini:</strong> <span class="status pending">{{ $surat->status }}</span></p>
    </div>

    @if($surat->lampiran)
        <h3 style="align-self: flex-start; margin-bottom: 20px; font-size: 22px;">Lampiran</h3>
        <div style="width: 100%; border-radius: 15px; overflow: hidden; border: 3px solid rgba(255,255,255,0.2);">
            <embed src="{{ route('file.preview', ['filepath' => base64_encode($surat->lampiran->file_path)]) }}" type="application/pdf" width="100%" height="700px" />
        </div>
    @endif

    <div class="btn-row" style="margin-top: 40px; justify-content: center; width: 100%;">
        <form id="approve-form" action="{{ route('surat.approve', $surat->surat_id) }}" method="POST" style="display:none;">
            @csrf
        </form>
        
        <form id="reject-form" action="{{ route('surat.reject', $surat->surat_id) }}" method="POST" style="display:none;">
            @csrf
            <input type="hidden" name="catatan" id="catatan-input">
        </form>

        <button type="button" id="approve-button" class="btn" style="background-color: #00c853; min-width: 200px;">✔️ Approve & Teruskan</button>
        
        <button type="button" id="reject-button" class="btn" style="background-color: #d50000; min-width: 200px;">❌ Tolak Surat</button>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Script untuk tombol APPROVE
document.getElementById('approve-button').addEventListener('click', function(e) {
    e.preventDefault();
    Swal.fire({
        title: 'Anda yakin?',
        text: "Anda akan menyetujui dan meneruskan surat ini.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#00c853',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Setujui!',
        cancelButtonText: 'Batal',
        background: '#1a1859',
        color: '#fff'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('approve-form').submit();
        }
    })
});

// SCRIPT BARU untuk tombol REJECT
document.getElementById('reject-button').addEventListener('click', function(e) {
    e.preventDefault();
    Swal.fire({
        title: 'Alasan Penolakan',
        input: 'textarea',
        inputPlaceholder: 'Tuliskan alasan penolakan surat di sini (wajib diisi)...',
        inputAttributes: {
            'aria-label': 'Tuliskan alasan penolakan'
        },
        showCancelButton: true,
        confirmButtonColor: '#d50000',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Kirim Penolakan',
        cancelButtonText: 'Batal',
        background: '#1a1859',
        color: '#fff',
        inputValidator: (value) => {
            if (!value || value.length < 10) {
                return 'Anda harus menulis alasan penolakan minimal 10 karakter!'
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Masukkan alasan dari pop-up ke dalam form yang tersembunyi
            document.getElementById('catatan-input').value = result.value;
            // Kirim form penolakan
            document.getElementById('reject-form').submit();
        }
    })
});
</script>
@endpush