@extends('layouts.app')

@section('content')
<h2>Surat Masuk untuk {{ $role }}</h2>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@forelse ($surats as $surat)
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">{{ $surat->jenisSurat->nama_surat }}</h5>
            <p class="card-text">Perihal: {{ $surat->perihal }}</p>
            <p class="card-text"><small class="text-muted">Diajukan: {{ $surat->created_at->format('d M Y H:i') }}</small></p>
            @if($surat->lampiran)
                <a href="{{ route('file.preview', ['filepath' => base64_encode($surat->lampiran->file_path)]) }}" target="_blank" class="btn btn-sm btn-info">📄 Preview File</a>
            @endif
            <a href="{{ route('surat.periksa', $surat->surat_id) }}" class="btn btn-sm btn-primary">🔍 Periksa</a>
        </div>
    </div>
@empty
    <p>Tidak ada surat yang perlu diperiksa.</p>
@endforelse
@endsection