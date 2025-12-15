@extends('layouts.frontend')
@section('title', 'Dashboard Admin | SIMAS-FTMM')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    /* --- CSS PAGINATION & NAVIGASI --- */
    nav[role="navigation"] { background: transparent !important; box-shadow: none !important; padding: 20px 0; text-align: center; }
    nav[role="navigation"] .hidden { display: none !important; }
    nav[role="navigation"] p.text-sm { display: none !important; }
    nav[role="navigation"] > div:nth-child(2) { display: inline-flex; border-radius: 5px; background: #0d0c3b !important; padding: 5px; border: 1px solid #2a2970; }
    nav[role="navigation"] a, nav[role="navigation"] span[aria-current="page"] { padding: 5px 12px; margin: 0 2px; border-radius: 4px; color: white !important; font-size: 0.9rem; background: transparent; border: none; text-decoration: none; }
    nav[role="navigation"] a:hover { background: #2a2970 !important; }
    nav[role="navigation"] span[aria-current="page"] { background: #00c853 !important; font-weight: bold; }
    nav[role="navigation"] svg { width: 15px; height: 15px; }
    
    /* --- CSS TOMBOL AKSI --- */
    .btn-aksi { display: inline-flex; align-items: center; justify-content: center; padding: 6px 15px; border-radius: 5px; text-decoration: none; font-size: 0.85rem; font-weight: 600; transition: all 0.3s; border: none; min-width: 90px; text-transform: uppercase; letter-spacing: 0.5px; }
    .btn-lihat { background-color: #17a2b8; color: white; }
    .btn-lihat:hover { background-color: #138496; color: white; }
    .btn-periksa { background-color: #ffce00; color: #000; box-shadow: 0 0 8px rgba(255, 206, 0, 0.4); }
    .btn-periksa:hover { background-color: #e0b500; color: #000; transform: translateY(-2px); }

    /* --- CSS BADGE TOTAL (HEADER GRAFIK) --- */
    .chart-badge {
        font-size: 0.75rem; 
        padding: 6px 12px; 
        border-radius: 6px; 
        font-weight: 800; 
        color: white; 
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        white-space: nowrap; /* Mencegah teks turun baris */
        margin-left: 10px;   /* Jarak aman di sebelah kiri badge */
    }
</style>
@endpush

@section('navbar')
    <nav>
        <div class="logo">SIMAS-FTMM (Admin)</div>
        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <a href="{{ route('logout') }}" class="logout" 
               onclick="event.preventDefault(); this.closest('form').submit();"
               style="color: white; text-decoration: none; font-weight: 600; padding: 8px 15px; border: 1px solid white; border-radius: 5px; cursor: pointer;">
                Logout
            </a>
        </form>
    </nav>
@endsection

@section('content')
<div class="container" style="padding-top: 50px; max-width: 1400px; width: 95%; margin: 0 auto;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1 class="welcome" style="margin: 0;">Dashboard Role: <b>{{ $userRole }}</b></h1>
    </div>

    {{-- STATISTIK UTAMA --}}
    <div class="menu-grid" style="max-width: 1400px; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 40px;">
        <div class="card" style="padding: 20px; text-align: left; cursor: default;">
            <h3 style="font-size: 0.9rem; margin-bottom: 5px; color: #aaa;">Total Ditangani</h3>
            <p style="font-size: 2rem; font-weight: 700; line-height: 1; margin:0;">{{ $totalPengajuan }}</p>
        </div>
        <div class="card" style="padding: 20px; text-align: left; cursor: default;">
            <h3 style="font-size: 0.9rem; margin-bottom: 5px; color: #ffce00;">Menunggu</h3>
            <p style="font-size: 2rem; font-weight: 700; line-height: 1; margin:0; color: #ffce00;">{{ $menungguCount }}</p>
        </div>
        <div class="card" style="padding: 20px; text-align: left; cursor: default;">
            <h3 style="font-size: 0.9rem; margin-bottom: 5px; color: #00c853;">Disetujui</h3>
            <p style="font-size: 2rem; font-weight: 700; line-height: 1; margin:0; color: #00c853;">{{ $disetujuiCount }}</p>
        </div>
        <div class="card" style="padding: 20px; text-align: left; cursor: default;">
            <h3 style="font-size: 0.9rem; margin-bottom: 5px; color: #d50000;">Ditolak</h3>
            <p style="font-size: 2rem; font-weight: 700; line-height: 1; margin:0; color: #d50000;">{{ $ditolakCount }}</p>
        </div>
    </div>

    <h3 style="margin-bottom: 20px; font-size: 22px;">Antrian Pengajuan</h3>

    {{-- FILTER TABEL --}}
    <div class="card" style="padding: 20px; margin-bottom: 20px; background: #1a1859; border: 1px solid #2a2970;">
        <form method="GET" action="{{ route('admin.dashboard') }}" style="display: flex; gap: 15px;">
            <input type="hidden" name="periode_analisis" value="{{ $periode }}">

            <div style="flex: 1;">
                <label style="color: #ffce00; font-size: 0.9rem; margin-bottom: 8px; display: block;">Jenis Surat</label>
                <select name="filter_jenis" class="form-control" style="background: #0d0c3b; color: white; border: 1px solid #2a2970; height: 45px; width: 100%; padding: 0 10px;">
                    <option value="Semua Jenis">Semua Jenis</option>
                    @foreach($jenisSuratList as $jenis)
                        <option value="{{ $jenis->nama_surat }}" {{ request('filter_jenis') == $jenis->nama_surat ? 'selected' : '' }}>
                            {{ $jenis->nama_surat }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="flex: 1;">
                <label style="color: #ffce00; font-size: 0.9rem; margin-bottom: 8px; display: block;">Status</label>
                <select name="filter_status" class="form-control" style="background: #0d0c3b; color: white; border: 1px solid #2a2970; height: 45px; width: 100%; padding: 0 10px;">
                    <option value="Semua Status" {{ $filterStatus === null ? 'selected' : '' }}>Semua Status</option>
                    <option value="Pending" {{ $filterStatus == 'Pending' ? 'selected' : '' }}>Pending (Menunggu Saya)</option>
                    <option value="Approved" {{ $filterStatus == 'Approved' ? 'selected' : '' }}>Approved</option>
                    <option value="Rejected" {{ $filterStatus == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <div>
                <label style="visibility: hidden; font-size: 0.9rem; margin-bottom: 8px; display: block;">Spacer</label>
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn" style="background-color: #00c853; height: 45px; display: flex; align-items: center; justify-content: center; padding: 0 25px; font-weight: 600; min-width: 100px; border: none; color: white; cursor: pointer;">Filter</button>
                    <a href="{{ route('admin.dashboard') }}" class="btn" style="background-color: #6c757d; height: 45px; display: flex; align-items: center; justify-content: center; padding: 0 25px; text-decoration:none; font-weight: 600; color: white; min-width: 100px; border: none; cursor: pointer;">Reset</a>
                </div>
            </div>
        </form>
    </div>

    {{-- TABEL DATA --}}
    <div style="width: 100%; overflow-x: auto; margin-bottom: 20px;">
        <table style="width: 100%; min-width: 1100px; border-collapse: collapse;"> 
            <thead>
                <tr>
                    <th style="white-space: nowrap; padding: 15px;">No.</th>
                    <th style="padding: 15px; width: 30%;">Perihal</th>
                    <th style="white-space: nowrap; padding: 15px;">Jenis</th>
                    <th style="white-space: nowrap; padding: 15px;">Tipe</th>
                    <th style="white-space: nowrap; padding: 15px;">Tanggal</th>
                    <th style="white-space: nowrap; padding: 15px;">Status</th>
                    <th style="white-space: nowrap; padding: 15px;">Pemohon</th>
                    <th style="white-space: nowrap; padding: 15px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($surats as $surat)
                
                @if(isset($surat->urgency_warning) && $surat->urgency_warning)
                    <tr style="background-color: {{ $surat->urgency_class == 'danger' ? '#d5000030' : '#ffce0030' }}; border: 1px solid {{ $surat->urgency_class == 'danger' ? '#d50000' : '#ffce00' }};">
                        <td colspan="8" style="padding: 8px 15px; text-align: left; font-weight: 600;">
                            <span style="color: {{ $surat->urgency_class == 'danger' ? '#d50000' : '#ffce00' }};">
                                ⚠️ {!! $surat->urgency_warning !!}
                            </span>
                        </td>
                    </tr>
                @endif

                <tr>
                    <td style="padding: 15px;">{{ $surats->firstItem() + $loop->index }}</td>
                    <td style="text-align: left; padding: 15px;">{{ $surat->perihal }}</td>
                    <td style="white-space: nowrap; padding: 15px;">{{ $surat->jenisSurat->nama_surat ?? 'N/A' }}</td>
                    <td style="white-space: nowrap; padding: 15px;"><span class="status" style="background: #aaa; color: #333;">{{ $surat->tipe_surat }}</span></td>
                    <td style="white-space: nowrap; padding: 15px;">{{ $surat->created_at->format('Y-m-d') }}</td>
                    <td style="white-space: nowrap; padding: 15px;">
                        <span class="status {{ $surat->status == 'Selesai' ? 'approved' : ($surat->status == 'Ditolak' ? 'rejected' : 'pending') }}">
                            {{ $surat->status }}
                        </span>
                    </td>
                    <td style="white-space: nowrap; padding: 15px;">{{ $surat->nama_pengaju }}</td>
                    <td style="text-align: center; padding: 15px;">
                        @if($surat->status == 'Menunggu ' . $userRole || $surat->status == 'Kembali ke ' . $userRole)
                            <a href="{{ route('surat.periksa', $surat->surat_id) }}" class="btn-aksi btn-periksa">Periksa</a>
                        @else
                            <a href="{{ route('surat.periksa', $surat->surat_id) }}" class="btn-aksi btn-lihat">Lihat</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-4" style="padding: 30px;">Tidak ada data antrian.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="d-flex justify-content-center mt-3" style="margin-bottom: 50px;">
        {{ $surats->withQueryString()->links() }}
    </div>

    {{-- HEADER BAGIAN GRAFIK --}}
    <div style="
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        width: 100%; 
        margin-top: 40px; 
        margin-bottom: 25px; 
        border-bottom: 1px solid rgba(255,255,255,0.1); 
        padding-bottom: 15px;
    ">
        <h3 style="margin: 0; font-size: 22px; font-weight: 600; color: white;">
            Statistik & Tren Surat
        </h3>
        
        <form method="GET" action="{{ route('admin.dashboard') }}" style="margin: 0;">
            @if(request('filter_jenis')) <input type="hidden" name="filter_jenis" value="{{ request('filter_jenis') }}"> @endif
            @if(request('filter_status')) <input type="hidden" name="filter_status" value="{{ request('filter_status') }}"> @endif

            <select name="periode_analisis" onchange="this.form.submit()" 
                    style="padding: 10px 20px; border-radius: 8px; background: #0d0c3b; color: white; border: 1px solid #2a2970; font-weight: 500; cursor: pointer; outline: none; font-size: 0.9rem; box-shadow: 0 4px 6px rgba(0,0,0,0.3); min-width: 160px;">
                <option value="Tahun Ini" {{ $periode == 'Tahun Ini' ? 'selected' : '' }}>Tahun Ini</option>
                <option value="Bulan Ini" {{ $periode == 'Bulan Ini' ? 'selected' : '' }}>Bulan Ini</option>
                <option value="7 Hari Terakhir" {{ $periode == '7 Hari Terakhir' ? 'selected' : '' }}>7 Hari Terakhir</option>
            </select>
        </form>
    </div>
    
    {{-- GRAFIK / CHART SECTION (SUDAH DIPERBAIKI DENGAN GAP/SPASI) --}}
    <div class="row" style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 50px;">
        
        {{-- CHART 1: TREN SURAT MASUK --}}
        <div class="card" style="flex: 1; min-width: 45%; padding: 20px; background: #0d0c3b; border: 1px solid #2a2970;">
            {{-- Tambahkan gap: 15px agar tidak nempel --}}
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; gap: 15px;">
                <h3 style="color: white; font-size: 1rem; margin: 0;">Tren Surat Masuk ({{ $periode }})</h3>
                <span class="chart-badge" style="background: #00c853;">Total: {{ $totalTren }}</span>
            </div>
            <canvas id="lineChartMasuk"></canvas>
        </div>

        {{-- CHART 2: TOTAL PER JENIS --}}
        <div class="card" style="flex: 1; min-width: 45%; padding: 20px; background: #0d0c3b; border: 1px solid #2a2970;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; gap: 15px;">
                <h3 style="color: white; font-size: 1rem; margin: 0;">Total per Jenis Surat ({{ $periode }})</h3>
                <span class="chart-badge" style="background: #17a2b8;">Total: {{ $totalJenis }}</span>
            </div>
            <canvas id="barChartJenis"></canvas>
        </div>

        {{-- CHART 3: RASIO STATUS --}}
        <div class="card" style="flex: 1; min-width: 30%; padding: 20px; background: #0d0c3b; border: 1px solid #2a2970;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; gap: 15px;">
                <h3 style="color: white; font-size: 1rem; margin: 0;">Rasio Selesai vs Ditolak</h3>
                <span class="chart-badge" style="background: #ffce00; color: black;">Total: {{ $totalKeputusan }}</span>
            </div>
            <div style="height: 250px; display: flex; justify-content: center;">
                <canvas id="donutChartStatus"></canvas>
            </div>
        </div>

        {{-- CHART 4: DURASI LAYANAN --}}
        <div class="card" style="flex: 1; min-width: 30%; padding: 20px; background: #0d0c3b; border: 1px solid #2a2970;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; gap: 15px;">
                <h3 style="color: white; font-size: 1rem; margin: 0;">Rata-rata Durasi (Jam)</h3>
                <span class="chart-badge" style="background: #6610f2;">Avg: {{ $avgDurasi }} Jam</span>
            </div>
            <canvas id="barChartDurasi"></canvas>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    // --- Chart 1: Line Chart (Tren) ---
    const ctxLine = document.getElementById('lineChartMasuk').getContext('2d');
    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: {!! json_encode($trenQuery->keys()) !!},
            datasets: [{
                label: 'Jumlah Surat',
                data: {!! json_encode($trenQuery->values()) !!},
                borderColor: '#00c853',
                backgroundColor: 'rgba(0, 200, 83, 0.2)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#2a2970' }, ticks: { stepSize: 1, color: '#aaa' } },
                x: { grid: { display: false }, ticks: { color: '#aaa' } }
            }
        }
    });

    // --- Chart 2: Bar Chart (Jenis Surat) ---
    const ctxBar = document.getElementById('barChartJenis').getContext('2d');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: {!! json_encode($jenisQuery->keys()) !!},
            datasets: [{
                label: 'Jumlah',
                data: {!! json_encode($jenisQuery->values()) !!},
                backgroundColor: '#17a2b8'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#2a2970' }, ticks: { stepSize: 1, color: '#aaa' } },
                x: { grid: { display: false }, ticks: { color: '#aaa' } }
            }
        }
    });

    // --- Chart 3: Donut Chart (Rasio Status) ---
    const ctxDonut = document.getElementById('donutChartStatus').getContext('2d');
    new Chart(ctxDonut, {
        type: 'doughnut',
        data: {
            labels: ['Selesai', 'Ditolak', 'Proses'],
            datasets: [{
                data: [{{ $statusSummary['Selesai'] }}, {{ $statusSummary['Ditolak'] }}, {{ $statusSummary['Proses'] }}],
                backgroundColor: ['#00c853', '#d50000', '#ffce00'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { position: 'bottom', labels: { color: 'white' } } 
            }
        }
    });

    // --- Chart 4: Horizontal Bar (Durasi) ---
    const ctxDurasi = document.getElementById('barChartDurasi').getContext('2d');
    new Chart(ctxDurasi, {
        type: 'bar',
        data: {
            labels: {!! json_encode($durasiQuery->keys()) !!},
            datasets: [{
                label: 'Rata-rata (Jam)',
                data: {!! json_encode($durasiQuery->values()) !!},
                backgroundColor: '#6610f2',
                borderRadius: 5
            }]
        },
        options: {
            indexAxis: 'y', 
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true, grid: { color: '#2a2970' }, ticks: { color: '#aaa' } },
                y: { grid: { display: false }, ticks: { color: '#aaa' } }
            }
        }
    });
</script>
@endpush