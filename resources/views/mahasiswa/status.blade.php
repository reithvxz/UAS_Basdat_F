@extends('layouts.frontend')
@section('title', 'Status Surat | SIMAS-FTMM')

@section('navbar')
    <nav>
        <div class="logo">SIMAS-FTMM</div>
        <a href="{{ route('dashboard') }}" class="home-btn">Home</a>
    </nav>
@endsection

@section('content')
    <div class="container" style="justify-content: flex-start; padding-top: 50px; max-width: 1200px; margin: 0 auto;">
        <h1 style="margin-bottom: 40px; width: 100%;">Status Pengajuan Surat</h1>

        @if(session('success'))
            <div style="background: rgba(212, 237, 218, 0.9); color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; width: 100%; text-align: center;">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
             <div style="background: rgba(248, 215, 218, 0.9); color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; width: 100%; text-align: center;">
                {{ session('error') }}
            </div>
        @endif

        <table style="width: 100%;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Jenis Surat</th>
                    <th>Perihal</th>
                    <th>Tanggal Ajuan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                    <th>Opsi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($surats as $surat)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $surat->jenisSurat->nama_surat ?? 'N/A' }}</td>
                    <td style="text-align: left;">{{ $surat->perihal }}</td>
                    <td>{{ $surat->created_at->format('d M Y') }}</td>
                    <td>
                        @if($surat->status == 'Disetujui')
                            <span class="status approved">Disetujui</span>
                        @elseif($surat->status == 'Ditolak')
                            <span class="status rejected">Ditolak</span>
                        @else
                            <span class="status pending">{{ str_replace('_', ' ', $surat->status) }}</span>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; justify-content: center; align-items: center; gap: 8px;">
                            @if($surat->lampiran)
                            <a href="{{ route('file.preview', ['filepath' => base64_encode($surat->lampiran->file_path)]) }}" target="_blank" class="btn-progress" style="background-color: #17a2b8; color: white; text-decoration: none;">📄 Preview</a>
                            @endif
                            <a href="{{ route('surat.tracking', $surat->surat_id) }}" class="btn-progress">📍 Lacak</a>
                        </div>
                    </td>
                    <td>
                        @if(!in_array($surat->status, ['Disetujui', 'Ditolak']))
                            <form id="batal-form-{{ $surat->surat_id }}" action="{{ route('surat.batal', $surat->surat_id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn-progress batal-button" data-id="{{ $surat->surat_id }}" style="background-color: #d50000; color: white; cursor:pointer;">Batalkan</button>
                            </form>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 30px;">Anda belum memiliki pengajuan surat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const batalButtons = document.querySelectorAll('.batal-button');

    batalButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const suratId = this.dataset.id;
            
            Swal.fire({
                title: 'Anda yakin?',
                text: "Pengajuan surat ini akan dibatalkan secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d50000',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Tidak',
                background: '#1a1859',
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