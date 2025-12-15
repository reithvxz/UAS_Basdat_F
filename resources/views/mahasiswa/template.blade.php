@extends('layouts.frontend')
@section('title', 'Template Surat | SIMAS-FTMM')

@section('navbar')
    <nav>
        <div class="logo">SIMAS-FTMM</div>
        <a href="{{ route('dashboard') }}" 
           style="color: white; text-decoration: none; font-weight: 600; padding: 8px 15px; border: 1px solid white; border-radius: 5px; cursor: pointer;">
            Home
        </a>
    </nav>
@endsection

@section('content')
    <div class="container" style="justify-content: flex-start; padding-top: 50px; max-width: 900px; margin: 0 auto;">
        <h1 style="margin-bottom: 40px; width: 100%;">Template Surat</h1>
        <div class="card" style="background: #1a1859; border: 1px solid #2a2970; border-radius: 15px; padding: 20px; width: 100%; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #2a2970;">
                        <th style="padding: 15px; width: 10%; text-align: center;">No</th>
                        <th style="padding: 15px; width: 60%; text-align: left;">Judul Template</th>
                        <th style="padding: 15px; width: 30%; text-align: center;">Link</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($templates as $template)
                    <tr>
                        <td style="padding: 15px; text-align: center;">{{ $loop->iteration }}</td>
                        
                        {{-- PERBAIKAN 1: Mengambil nama dari relasi jika ada, atau dari nama_template --}}
                        <td style="padding: 15px; text-align: left;">{{ $template->jenisSurat->nama_surat ?? $template->nama_template }}</td>
                        
                        <td style="padding: 15px; text-align: center;">
                            {{-- PERBAIKAN 2: Warna Kuning (#ffce00) --}}
                            <a href="{{ $template->file_link }}" target="_blank" 
                               style="background-color: #ffce00; color: #0d0c3b; padding: 8px 15px; text-decoration: none; border-radius: 5px; font-size: 0.9rem; font-weight: 600; display: inline-block;">
                               Buka Template
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="padding: 30px; text-align: center;">Belum ada template yang tersedia.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection