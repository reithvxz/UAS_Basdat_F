@extends('layouts.frontend')
@section('title', 'Lacak Surat | SIMAS-FTMM')

@section('navbar')
    <nav>
        <div class="logo">SIMAS-FTMM</div>
        {{-- TOMBOL KEMBALI KE STATUS (GAYA KOTAK BORDER PUTIH) --}}
        <a href="{{ route('status') }}" 
           style="color: white; text-decoration: none; font-weight: 600; padding: 8px 15px; border: 1px solid white; border-radius: 5px; cursor: pointer;">
            Kembali ke Status
        </a>
    </nav>
@endsection

@section('content')
<div class="container" style="justify-content: flex-start; padding-top: 50px; max-width: 1400px; width: 95%; margin: 0 auto;"> 
    <h1 style="margin-bottom: 20px; width: 100%; text-align: center;">Lacak Progress Surat</h1>
    <p class="subtitle" style="font-size: 1.2rem; margin-bottom: 50px; text-align: center; color: #ffce00;">
        Perihal: {{ $surat->perihal }}
    </p>

    <div class="card" style="background: #1a1859; border: 1px solid #2a2970; border-radius: 15px; padding: 40px; width: 100%; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
        
        {{-- KONDISI KHUSUS: SURAT DIBATALKAN --}}
        @if(isset($isCanceled) && $isCanceled)
            <div style="text-align: center; padding: 40px; background: #3c3c3c; color: #fff; border-radius: 8px; border: 1px solid #555;">
                <h2 style="color: #ccc;">🚫 STATUS: DIBATALKAN</h2>
                <p style="color: #ccc;">Surat ini telah dibatalkan oleh pemohon pada tanggal {{ $surat->updated_at->format('d M Y H:i') }}.</p>
            </div>
        
        @else {{-- KASUS NORMAL (PROSES, DITOLAK, SELESAI) --}}
            
            <h2 style="text-align: center; margin-bottom: 40px; color: white;">Alur Persetujuan</h2>

            @php
                $isRejected = ($surat->status == 'Ditolak');
                $isSelesai  = ($surat->status == 'Selesai');
                
                // 1. TENTUKAN WARNA TEMA
                if ($isSelesai) {
                    $themeColor = '#00c853'; // HIJAU (Selesai)
                } elseif ($isRejected) {
                    $themeColor = '#d50000'; // MERAH (Ditolak)
                } else {
                    $themeColor = '#ffce00'; // KUNING (Proses)
                }

                // 2. FILTER STEP (Hapus 'Selesai' dari visualisasi)
                $displaySteps = array_values(array_filter($alurVisual, function($value) {
                    return $value !== 'Selesai';
                }));

                // 3. TENTUKAN BATAS INDEX VISUALISASI
                $limitIndex = -1; // Default

                if ($isSelesai) {
                    // Jika selesai, semua langkah aktif
                    $limitIndex = count($displaySteps) - 1; 
                } 
                elseif ($isRejected && isset($rejection)) {
                    // Jika ditolak, cari index step milik Role Penolak
                    foreach($displaySteps as $key => $val) {
                        // Cek apakah step saat ini (val) mengandung nama role penolak
                        if (str_contains($val, $rejection->role)) {
                            $limitIndex = $key;
                            break;
                        }
                    }
                    // Jika tidak ketemu (misal ditolak oleh Dekan, tapi alur visualnya hanya sampai 'Menunggu Dekan'), fallback ke activeIndex
                    if($limitIndex == -1) $limitIndex = $activeIndex;
                } 
                else {
                    // Jika proses normal, ikuti activeIndex dari controller
                    $limitIndex = $activeIndex;
                }
            @endphp

            <div style="overflow-x: auto; padding-bottom: 20px;"> 
                <div style="display: flex; justify-content: space-between; align-items: flex-start; min-width: 1000px; padding: 0 20px; position: relative;">
                    
                    @foreach ($displaySteps as $index => $step)
                        @php
                            $isActive = ($index <= $limitIndex);
                            $stepName = str_replace(['Menunggu ', 'Kembali ke '], '', $step);
                        @endphp

                        {{-- ITEM STEP --}}
                        <div style="flex: 1; text-align: center; position: relative; min-width: 150px;">
                            
                            {{-- GARIS PENGHUBUNG --}}
                            @if(!$loop->last)
                                <div style="
                                    position: absolute; 
                                    top: 25px; 
                                    left: 50%; 
                                    width: 100%; 
                                    height: 4px; 
                                    background: {{ ($isActive && ($index + 1 <= $limitIndex)) ? $themeColor : '#2a2970' }}; 
                                    z-index: 0;
                                "></div>
                            @endif

                            {{-- LINGKARAN --}}
                            <div style="
                                width: 50px; 
                                height: 50px; 
                                margin: 0 auto; 
                                border-radius: 50%; 
                                background: {{ $isActive ? $themeColor : '#0d0c3b' }}; 
                                color: {{ $isActive ? ($isRejected ? 'white' : '#0d0c3b') : 'white' }};
                                border: 3px solid {{ $isActive ? $themeColor : '#2a2970' }};
                                font-weight: bold; 
                                font-size: 1.2rem; 
                                line-height: 45px; 
                                position: relative; 
                                z-index: 1;
                                display: flex; justify-content: center; align-items: center;
                                box-shadow: {{ $isActive ? '0 0 15px ' . $themeColor . '80' : 'none' }};
                            ">
                                {{-- LOGIKA ISI LINGKARAN --}}
                                @if($isSelesai)
                                    {{ $loop->iteration }}
                                @elseif($isRejected && $index == $limitIndex)
                                    !
                                @else
                                    {{ $loop->iteration }}
                                @endif
                            </div>

                            {{-- LABEL TEXT --}}
                            <div style="margin-top: 15px; font-weight: 600; color: {{ $isActive ? $themeColor : '#aaa' }}; font-size: 1rem;">
                                {{ $stepName }}
                            </div>
                            
                            {{-- LABEL KECIL DI BAWAH --}}
                            @if($isActive && $index == $limitIndex)
                                <div style="font-size: 0.8rem; color: #fff; margin-top: 5px; background: rgba(255,255,255,0.1); display: inline-block; padding: 2px 8px; border-radius: 4px;">
                                    @if($isRejected)
                                        <span style="color: #ffb8b8;">Ditolak Disini</span>
                                    @elseif($isSelesai)
                                        Selesai
                                    @else
                                        Sedang Proses
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- STATUS FINAL (BAWAH) --}}
            <div style="display: flex; justify-content: center; width: 100%; margin-top: 40px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 30px;">
                
                @if ($isSelesai)
                    <div style="text-align: center;">
                        {{-- Ikon Centang Besar Hijau --}}
                        <div style="width: 70px; height: 70px; background: #00c853; color: white; border-radius: 50%; font-size: 35px; line-height: 70px; margin: 0 auto 15px; box-shadow: 0 0 20px rgba(0, 200, 83, 0.6);">
                            ✓
                        </div>
                        <h3 style="color: #00c853;">Surat Telah Selesai Diproses</h3>
                        <p style="color: #ccc;">Silakan unduh surat final di halaman status.</p>
                    </div>

                @elseif ($isRejected && $rejection)
                    <div style="text-align: center; background: rgba(213, 0, 0, 0.1); padding: 20px; border-radius: 10px; border: 1px solid #d50000; max-width: 600px;">
                        <div style="width: 60px; height: 60px; background: #d50000; color: white; border-radius: 50%; font-size: 30px; line-height: 60px; margin: 0 auto 15px;">
                            !
                        </div>
                        <h3 style="color: #d50000; margin-bottom: 10px;">Pengajuan Ditolak</h3>
                        
                        <div style="text-align: center; background: rgba(0,0,0,0.2); padding: 15px; border-radius: 5px;">
                            <p style="color: #ffb8b8; margin: 0;">
                                <strong>Ditolak oleh:</strong> {{ $rejection->role }}<br>
                                <strong>Alasan:</strong> <br>
                                "{{ $rejection->catatan }}"
                            </p>
                        </div>
                    </div>
                @endif
            </div>

        @endif {{-- AKHIR KASUS DIBATALKAN --}}

    </div>
</div>
@endsection

@push('scripts')
{{-- Tidak ada perubahan script Chart.js di sini --}}
@endpush