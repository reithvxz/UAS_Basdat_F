@extends('layouts.frontend')
@section('title', 'Template Surat | SIMAS-FTMM')

@section('navbar')
    <nav>
        <div class="logo">SIMAS-FTMM</div>
        <a href="{{ route('dashboard') }}" class="home-btn">Home</a>
    </nav>
@endsection

@section('content')
    <div class="container" style="justify-content: flex-start; padding-top: 50px; max-width: 900px; margin: 0 auto;">
        <h1 style="margin-bottom: 40px; width: 100%;">Template Surat</h1>
        <table style="width: 100%;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul Template</th>
                    <th>Link</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($templates as $template)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td style="text-align: left;">{{ $template->nama_template }}</td>
                    <td><a href="{{ $template->file_link }}" class="template-link" target="_blank">Buka Template</a></td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="padding: 30px;">Belum ada template yang tersedia.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection