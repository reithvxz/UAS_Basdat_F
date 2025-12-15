@extends('layouts.frontend')
@section('title', 'Status Surat | SIMAS-FTMM')

@push('styles')
<style>
    /* --- STYLE GLOBAL: Mengikuti main.css --- */

    /* Judul Halaman */
    .page-title {
        text-align: center;
        margin-top: 40px;
        margin-bottom: 30px;
        font-size: 36px;
        font-weight: 800;
        color: #ffce00;
        text-shadow: 0 4px 10px rgba(0,0,0,0.3);
    }

    /* --- FILTER SECTION --- */
    .filter-container {
        display: flex;
        justify-content: flex-end;
        max-width: 1200px;
        margin: 0 auto 20px auto;
        padding: 0 15px;
    }

    .filter-select {
        padding: 10px 20px;
        border-radius: 12px;
        border: 2px solid rgba(255, 255, 255, 0.2);
        background-color: rgba(255, 255, 255, 0.1);
        color: #fff;
        font-weight: 600;
        cursor: pointer;
        outline: none;
        transition: all 0.3s ease;
        font-family: 'Poppins', sans-serif;
    }

    .filter-select:hover, .filter-select:focus {
        background-color: rgba(255, 255, 255, 0.2);
        border-color: #ffce00;
        box-shadow: 0 0 15px rgba(255, 206, 0, 0.3);
    }
    
    .filter-select option {
        background-color: #1a1859;
        color: white;
    }

    /* --- TABLE STYLING --- */
    .table-container {
        max-width: 1200px;
        margin: 0 auto;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 20px;
        overflow-x: auto;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.25);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1000px;
    }

    thead {
        background-color: rgba(0, 0, 0, 0.2);
        border-bottom: 2px solid rgba(255, 255, 255, 0.1);
    }

    th {
        padding: 20px;
        text-align: left;
        font-weight: 700;
        color: #ffce00;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 1px;
    }

    td {
        padding: 18px 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        vertical-align: middle;
        font-size: 0.95rem;
        color: #e0e0e0;
    }

    tr:last-child td { border-bottom: none; }
    tr:hover { background-color: rgba(255, 255, 255, 0.05); }

    /* --- BADGES --- */
    .badge {
        padding: 6px 14px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        display: inline-block;
        min-width: 90px;
        text-align: center;
        letter-spacing: 0.5px;
    }
    .badge-yellow { background-color: #ffce00; color: #0d0c3b; box-shadow: 0 0 10px rgba(255, 206, 0, 0.4); }
    .badge-green { background-color: #00c853; color: #fff; box-shadow: 0 0 10px rgba(0, 200, 83, 0.4); }
    .badge-red { background-color: #ff1744; color: #fff; box-shadow: 0 0 10px rgba(255, 23, 68, 0.4); }
    .badge-gray { background-color: #6c757d; color: #fff; opacity: 0.8; }

    /* --- BUTTONS --- */
    .btn-action {
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .btn-action:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.3); }
    
    .btn-preview { background-color: #00d2d3; color: #000; }
    .btn-track { background-color: #ffce00; color: #0d0c3b; }
    .btn-cancel { background-color: #ff4757; color: white; }

    /* --- ALERT --- */
    .alert {
        max-width: 1200px;
        margin: 0 auto 20px auto;
        padding: 15px;
        border-radius: 12px;
        text-align: center;
        font-weight: 500;
    }
    .alert-success { background: rgba(0, 200, 83, 0.2); border: 1px solid #00c853; color: #00c853; }
    .alert-error { background: rgba(255, 23, 68, 0.2); border: 1px solid #ff1744; color: #ff1744; }

    /* ========================================= */
    /* CSS PAGINATION FIX (RAPUH & TENGAH) */
    /* ========================================= */
    
    .pagination-wrapper {
        margin: 40px auto;
        width: 100%;
        display: flex;
        justify-content: center;
    }

    /* Target NAV bawaan Laravel */
    .pagination-wrapper nav {
        display: flex;
        flex-direction: column; /* Susun vertikal: Teks atas, Tombol bawah */
        align-items: center;
        justify-content: center;
        width: 100%;
    }

    /* Sembunyikan bagian mobile (garis-garis kecil) */
    .pagination-wrapper nav > div:first-child {
        display: none !important;
    }

    /* Container Utama Desktop */
    .pagination-wrapper nav > div:last-child {
        display: flex;
        flex-direction: column; /* Paksa kolom agar teks di atas tombol */
        align-items: center;
        gap: 15px;
        width: 100%;
        background: transparent !important;
        box-shadow: none !important;
    }

    /* 1. Styling Teks 'Showing 1 to 10...' */
    .pagination-wrapper nav p {
        color: #ccc !important;
        margin: 0 !important;
        font-size: 0.9rem;
        font-weight: 500;
        text-align: center;
    }
    
    /* Container yang memegang teks */
    .pagination-wrapper nav > div:last-child > div:first-child {
         display: block !important; /* Pastikan teks mengambil baris sendiri */
         margin-bottom: 5px;
    }

    /* 2. Styling Container TOMBOL (Agar Mendatar) */
    .pagination-wrapper nav > div:last-child > div:last-child {
        display: flex !important;
        flex-direction: row !important; /* MENDATAR (HORIZONTAL) */
        justify-content: center !important;
        gap: 8px;
        flex-wrap: wrap;
    }
    
    /* Hapus shadow/border pada wrapper span bawaan Laravel */
    .pagination-wrapper nav > div:last-child > div:last-child > span {
        display: flex !important;
        gap: 8px;
        box-shadow: none !important;
        border-radius: 0 !important;
    }

    /* Style Dasar Tombol (Angka & Panah) */
    .pagination-wrapper nav a, 
    .pagination-wrapper nav span[aria-current="page"] span, 
    .pagination-wrapper nav span[aria-disabled="true"] span {
        display: inline-flex !important;
        justify-content: center;
        align-items: center;
        min-width: 40px;
        height: 40px;
        padding: 0 12px;
        border-radius: 10px !important;
        font-size: 0.95rem;
        font-weight: 700;
        text-decoration: none !important;
        
        /* Glass Style */
        border: 1px solid rgba(255,255,255,0.2) !important;
        background-color: rgba(255,255,255,0.05) !important;
        color: white !important;
        
        transition: all 0.3s ease;
        margin: 0 !important;
    }

    /* Hover State */
    .pagination-wrapper nav a:hover {
        background-color: rgba(255, 206, 0, 0.2) !important;
        border-color: #ffce00 !important;
        color: #ffce00 !important;
        transform: translateY(-3px);
    }

    /* Active State (Halaman Saat Ini) */
    .pagination-wrapper nav span[aria-current="page"] span {
        background-color: #ffce00 !important;
        color: #0d0c3b !important;
        border-color: #ffce00 !important;
        box-shadow: 0 0 15px rgba(255, 206, 0, 0.5);
    }

    /* Disabled State (Panah Mati) */
    .pagination-wrapper nav span[aria-disabled="true"] span {
        opacity: 0.4;
        cursor: not-allowed;
    }

    /* Fix SVG (Panah Raksasa jadi Kecil) */
    .pagination-wrapper svg {
        width: 16px !important;
        height: 16px !important;
        fill: currentColor !important;
    }

</style>
@endpush

@section('navbar')
    <nav style="padding: 20px 50px;">
        <div class="logo" style="font-weight: 900; font-size: 1.5rem; color: #ffce00;">SIMAS-FTMM</div>
        <a href="{{ route('dashboard') }}" class="btn-home" style="border: 2px solid rgba(255,255,255,0.3); padding: 8px 25px; border-radius: 30px; color: white; text-decoration: none; font-weight: 600; transition:0.3s;">Home</a>
    </nav>
@endsection

@section('content')

    <h1 class="page-title">Status Pengajuan Surat</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
         <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <div class="filter-container">
        <form method="GET" action="{{ route('status') }}" id="filterForm">
            <select name="filter_status" class="filter-select" onchange="document.getElementById('filterForm').submit()">
                <option value="Proses" {{ $filterStatus == 'Proses' ? 'selected' : '' }}>⏳ Sedang Diproses</option>
                <option value="Disetujui" {{ $filterStatus == 'Disetujui' ? 'selected' : '' }}>✅ Disetujui</option>
                <option value="Ditolak" {{ $filterStatus == 'Ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
                <option value="Dibatalkan" {{ $filterStatus == 'Dibatalkan' ? 'selected' : '' }}>🚫 Dibatalkan</option>
                <option value="Semua" {{ $filterStatus == 'Semua' ? 'selected' : '' }}>📂 Semua Surat</option>
            </select>
        </form>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width: 60px; text-align: center;">No</th>
                    <th>Jenis Surat</th>
                    <th style="width: 30%;">Perihal</th>
                    <th>Atas Nama</th>
                    <th>Tanggal</th>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: center;">Aksi</th>
                    <th style="text-align: center;">Opsi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($surats as $index => $surat)
                <tr>
                    <td style="text-align: center; font-weight: 600;">{{ $surats->firstItem() + $index }}</td>
                    <td style="white-space: nowrap;">{{ $surat->jenisSurat->nama_surat ?? '-' }}</td>
                    <td>{{ $surat->perihal }}</td>
                    <td style="white-space: nowrap;">
                        {{ ($surat->atas_nama == 'BEM' || $surat->atas_nama == 'BLM') ? $surat->atas_nama.' FTMM' : $surat->atas_nama }}
                    </td>
                    <td style="white-space: nowrap;">{{ $surat->created_at->format('d M Y') }}</td>
                    
                    <td style="text-align: center;">
                        @php
                            $badgeClass = 'badge-yellow'; 
                            $displayText = str_replace(['Menunggu ', 'Kembali ke '], '', $surat->status); 

                            if ($surat->status == 'Selesai') {
                                $badgeClass = 'badge-green';
                                $displayText = 'Selesai';
                            } elseif ($surat->status == 'Ditolak') {
                                $badgeClass = 'badge-red';
                                $displayText = 'Ditolak';
                            } elseif ($surat->status == 'Dibatalkan') {
                                $badgeClass = 'badge-gray';
                                $displayText = 'Dibatalkan';
                            }
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $displayText }}</span>
                    </td>

                    <td style="text-align: center; white-space: nowrap;">
                        @php
                            $latestLampiran = $surat->lampiran()->orderBy('id', 'desc')->first();
                        @endphp
                        @if($latestLampiran)
                            <a href="{{ route('file.preview', ['filepath' => base64_encode($latestLampiran->file_path)]) }}" target="_blank" class="btn-action btn-preview">Preview</a>
                        @endif
                        <a href="{{ route('surat.tracking', $surat->surat_id) }}" class="btn-action btn-track">Lacak</a>
                    </td>

                    <td style="text-align: center;">
                        @if(!in_array($surat->status, ['Selesai', 'Ditolak', 'Dibatalkan']))
                            <form id="batal-form-{{ $surat->surat_id }}" action="{{ route('surat.batal', $surat->surat_id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn-action btn-cancel batal-button" data-id="{{ $surat->surat_id }}">Batalkan</button>
                            </form>
                        @else
                            <span style="color: rgba(255,255,255,0.3); font-size: 1.2rem;">&minus;</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 50px; color: #ccc; font-style: italic;">
                        Tidak ada surat dengan status: <strong>{{ $filterStatus }}</strong>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper">
        {{ $surats->withQueryString()->links() }}
    </div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const batalButtons = document.querySelectorAll('.batal-button');

    batalButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const suratId = this.dataset.id;
            
            Swal.fire({
                title: 'Batalkan Pengajuan?',
                text: "Surat yang dibatalkan tidak dapat diproses lagi.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff1744',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Tidak',
                background: '#1a1859', // Tema gelap
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('batal-form-' + suratId).submit();
                }
            })
        });
    });
});
</script>
@endpush