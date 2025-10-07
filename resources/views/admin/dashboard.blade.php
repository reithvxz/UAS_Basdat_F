@extends('layouts.frontend')
@section('title', 'Dashboard Admin | SIMAS-FTMM')

@section('navbar')
    <nav>
        <div class="logo">SIMAS-FTMM (Admin)</div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a href="{{ route('logout') }}" class="logout" onclick="event.preventDefault(); this.closest('form').submit();">
                Logout
            </a>
        </form>
    </nav>
@endsection

@section('content')
<div class="container" style="justify-content: flex-start; padding-top: 50px; max-width: 1200px; margin: 0 auto;">
    <h1 class="welcome" style="font-size: 24px; margin-bottom: 40px; width:100%; text-align:left;">Dashboard Role: <b>{{ $userRole }}</b></h1>

    <div class="menu-grid" style="max-width: 1200px; grid-template-columns: repeat(4, 1fr); gap: 25px; margin-bottom: 50px;">
        <div class="card" style="animation: none; opacity: 1; transform: none; padding: 25px; text-align: left; cursor: default;">
            <h3 style="font-size: 1rem; margin-bottom: 8px;">Total Ditangani</h3>
            <p style="font-size: 2.25rem; font-weight: 700; line-height: 1;">{{ $totalPengajuan }}
                @if($persentaseKenaikan >= 0)
                    <span style="font-size: 0.9rem; color: #a7ffc5;">(+{{ $persentaseKenaikan }}%)</span>
                @else
                    <span style="font-size: 0.9rem; color: #ffb8b8;">({{ $persentaseKenaikan }}%)</span>
                @endif
            </p>
        </div>
        <div class="card" style="animation: none; opacity: 1; transform: none; padding: 25px; text-align: left; cursor: default;">
            <h3 style="font-size: 1rem; margin-bottom: 8px;">Menunggu Persetujuan</h3>
            <p style="font-size: 2.25rem; font-weight: 700; line-height: 1; color: #ffce00;">{{ $menungguCount }}</p>
        </div>
        <div class="card" style="animation: none; opacity: 1; transform: none; padding: 25px; text-align: left; cursor: default;">
            <h3 style="font-size: 1rem; margin-bottom: 8px;">Sudah Disetujui</h3>
            <p style="font-size: 2.25rem; font-weight: 700; line-height: 1; color: #00c853;">{{ $disetujuiCount }}</p>
        </div>
        <div class="card" style="animation: none; opacity: 1; transform: none; padding: 25px; text-align: left; cursor: default;">
            <h3 style="font-size: 1rem; margin-bottom: 8px;">Sudah Ditolak</h3>
            <p style="font-size: 2.25rem; font-weight: 700; line-height: 1; color: #d50000;">{{ $ditolakCount }}</p>
        </div>
    </div>

    <h3 style="align-self: flex-start; margin-bottom: 20px; font-size: 22px;">Antrian Pengajuan</h3>
    <table style="width: 100%;">
        <thead>
            <tr>
                <th>No.</th>
                <th>Perihal</th>
                <th>Jenis</th>
                <th>Tipe</th>
                <th>Tanggal Masuk</th>
                <th>Pemohon</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($surats as $surat)
            <tr>
                <th>{{ $surats->firstItem() + $loop->index }}</th>
                <td style="text-align: left;">{{ $surat->perihal }}</td>
                <td>{{ $surat->jenisSurat->nama_surat ?? 'N/A' }}</td>
                <td><span class="status" style="background: #aaa; color: #333; font-weight: 500;">{{ $surat->tipe_surat }}</span></td>
                <td>{{ $surat->created_at->format('d M Y') }}</td>
                <td>{{ $surat->nama_pengaju }}</td>
                <td>
                    <a href="{{ route('surat.periksa', $surat->surat_id) }}" class="btn-progress">🔍 Periksa</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-4" style="padding: 30px;">Tidak ada surat yang menunggu persetujuan Anda saat ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
     <div class="d-flex justify-content-center mt-3" style="width: 100%;">
        {{ $surats->links() }}
    </div>
</div>
@endsection