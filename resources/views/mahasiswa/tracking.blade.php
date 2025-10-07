@extends('layouts.frontend')
@section('title', 'Lacak Surat | SIMAS-FTMM')

@section('navbar')
    <nav>
        <div class="logo">SIMAS-FTMM</div>
        <a href="{{ route('status') }}" class="home-btn">Kembali ke Status</a>
    </nav>
@endsection

@section('content')
<div class="container" style="justify-content: flex-start; padding-top: 50px; max-width: 1000px; margin: 0 auto;">
    <h1 style="margin-bottom: 20px; width: 100%;">Lacak Progress Surat</h1>
    <p class="subtitle" style="font-size: 1.2rem; margin-bottom: 50px;">Perihal: {{ $surat->perihal }}</p>

    <div class="modal-content" style="background: rgba(255,255,255,0.1); color: white; transform: none; opacity: 1; width: 100%;">
        <h2 style="text-align: center;">Alur Persetujuan</h2>

        @php
            $isFinal = in_array($surat->status, ['Disetujui', 'Ditolak']);
        @endphp

        <div class="progress-tracker">

            @foreach ($alur as $index => $step)
                @php
                    $stepClass = '';
                    if ($activeIndex !== false && $index <= $activeIndex) {
                        $stepClass = 'active-path';
                    }
                    if ($activeIndex !== false && $index == $activeIndex) {
                        $stepClass .= ' current';
                    }
                @endphp

                <div class="tracker-step {{ $stepClass }}">
                    <div class="circle">{{ $loop->iteration }}</div>
                    <div class="label">{{ str_replace('Menunggu ', '', $step) }}</div>
                </div>
            @endforeach
        </div>

        @if ($isFinal)
            <div style="display: flex; justify-content: center; width: 100%; margin-top: 20px;">
                @if ($surat->status == 'Disetujui')
                    <div class="tracker-step approved-step no-line">
                        <div class="circle" style="margin: 0 auto 10px;">✓</div>
                        <div class="label">Surat Telah Disetujui</div>
                    </div>
                @elseif ($rejection)
                    <div class="tracker-rejection">
                        <div class="tracker-step no-line">
                            <div class="circle">!</div>
                            <div class="label">Surat Ditolak</div>
                        </div>
                        <p style="color:#ffb8b8; margin-top:15px; font-weight:600;">
                          <strong>Alasan Penolakan oleh {{ $rejection->role }}:</strong><br>
                          "{{ $rejection->catatan }}"
                        </p>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection