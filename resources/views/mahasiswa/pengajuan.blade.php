@extends('layouts.frontend')
@section('title', 'Pengajuan Surat | SIMAS-FTMM')

@section('navbar')
<nav>
    <div class="logo">SIMAS-FTMM</div>
    <a href="{{ route('dashboard') }}" class="home-btn">Home</a>
</nav>
@endsection

@section('content')
<div class="container">
    <div class="form-box" id="formBox">
        <div class="step active">
            <h2>Formulir Pengajuan Surat</h2>
            
            @if ($errors->any())
            <div style="background: #ffdddd; color: #d50000; padding: 10px; border-radius: 8px; margin-bottom: 20px; font-size:14px; text-align: left;">
                <strong>Terjadi Kesalahan:</strong>
                <ul style="margin-left: 20px; margin-top: 5px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            
            <form method="POST" action="{{ route('pengajuan.store') }}" enctype="multipart/form-data" style="text-align: left;">
                @csrf

                <label for="nama_pengaju">Nama Pengaju</label>
                <input type="text" name="nama_pengaju" id="nama_pengaju" value="{{ old('nama_pengaju', Auth::guard('mahasiswa')->user()->nama) }}" required>

                <label for="atas_nama">Atas Nama</label>
                <select name="atas_nama" id="atas_nama" required>
                    <option value="">-- Pilih Atas Nama --</option>
                    <option value="Mahasiswa" {{ old('atas_nama') == 'Mahasiswa' ? 'selected' : '' }}>Mahasiswa (Pribadi)</option>
                    <option value="HIMA" {{ old('atas_nama') == 'HIMA' ? 'selected' : '' }}>HIMA</option>
                    <option value="BSO" {{ old('atas_nama') == 'BSO' ? 'selected' : '' }}>BSO</option>
                    <option value="BEM" {{ old('atas_nama') == 'BEM' ? 'selected' : '' }}>BEM FTMM</option>
                </select>

                {{-- MODIFIKASI: Kontainer untuk pilihan dinamis agar lebih rapi --}}
                <div id="dynamic-options-container">
                    <div id="hima_field" style="display:none;">
                        <label for="ormawa_id_hima">Pilih HIMA</label>
                        <select name="ormawa_id_hima" id="ormawa_id_hima">
                            @foreach($himas as $hima)
                            <option value="{{ $hima->ormawa_id }}">{{ $hima->nama_ormawa }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="bso_field" style="display:none;">
                        <label for="ormawa_id_bso">Pilih BSO</label>
                        <select name="ormawa_id_bso" id="ormawa_id_bso">
                            @foreach($bsos as $bso)
                            <option value="{{ $bso->ormawa_id }}">{{ $bso->nama_ormawa }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <input type="hidden" name="ormawa_id" id="ormawa_id">

                <label for="jenis_id">Jenis Surat</label>
                <select name="jenis_id" id="jenis_id" required>
                    <option value="">-- Pilih Jenis Surat --</option>
                    @foreach($jenisSurats as $jenis)
                    <option value="{{ $jenis->jenis_id }}" {{ old('jenis_id') == $jenis->jenis_id ? 'selected' : '' }}>{{ $jenis->nama_surat }}</option>
                    @endforeach
                </select>
                <div id="template_link" style="margin-top: -15px; margin-bottom: 20px; font-size: 14px;"></div>

                <label for="tipe_surat">Tipe Surat</label>
                <select name="tipe_surat" id="tipe_surat" required>
                    <option value="">-- Pilih Tipe Surat --</option>
                    <option value="Scan" {{ old('tipe_surat') == 'Scan' ? 'selected' : '' }}>Scan (Digital)</option>
                    <option value="Fisik" {{ old('tipe_surat') == 'Fisik' ? 'selected' : '' }}>Fisik (Cetak)</option>
                </select>

                <label for="perihal">Perihal</label>
                <input type="text" name="perihal" id="perihal" value="{{ old('perihal') }}" required>
                
                <label for="lampiran">Upload Lampiran (PDF, maks 2MB)</label>
                <input type="file" name="lampiran" id="lampiran" accept="application/pdf" required style="padding: 12px; background: #f0f0f0;">

                <div class="btn-row" style="margin-top: 20px;">
                    <a href="{{ route('dashboard') }}" class="btn" style="background: #6c757d; text-decoration:none;">Batal</a>
                    <button type="submit" class="btn">Submit Pengajuan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const atasNamaSelect = document.getElementById('atas_nama');
        const himaField = document.getElementById('hima_field');
        const bsoField = document.getElementById('bso_field');
        const himaSelect = document.getElementById('ormawa_id_hima');
        const bsoSelect = document.getElementById('ormawa_id_bso');
        const hiddenOrmawaId = document.getElementById('ormawa_id');
        const jenisSelect = document.getElementById('jenis_id');
        const templateLinkDiv = document.getElementById('template_link');

        function toggleOrmawa() {
            const val = atasNamaSelect.value;
            himaField.style.display = 'none';
            bsoField.style.display = 'none';

            // Nonaktifkan select yang tidak terlihat agar nilainya tidak terkirim
            himaSelect.disabled = true;
            bsoSelect.disabled = true;
            hiddenOrmawaId.value = '';

            if (val === 'HIMA') {
                himaField.style.display = 'block';
                himaSelect.disabled = false; // Aktifkan kembali
                hiddenOrmawaId.value = himaSelect.value;
            } else if (val === 'BSO') {
                bsoField.style.display = 'block';
                bsoSelect.disabled = false; // Aktifkan kembali
                hiddenOrmawaId.value = bsoSelect.value;
            }
        }

        himaSelect.addEventListener('change', () => hiddenOrmawaId.value = himaSelect.value);
        bsoSelect.addEventListener('change', () => hiddenOrmawaId.value = bsoSelect.value);

        function showTemplateLink() {
            const jenisId = jenisSelect.value;
            templateLinkDiv.innerHTML = '';
            if (jenisId) {
                fetch(`{{ route('ajax.template.link') }}?jenis_id=${jenisId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.link) {
                            templateLinkDiv.innerHTML = `📄 <a href="${data.link}" target="_blank" style="text-decoration:none; color:#0d0c3b;">Download Template Surat</a>`;
                        }
                    });
            }
        }

        atasNamaSelect.addEventListener('change', toggleOrmawa);
        jenisSelect.addEventListener('change', showTemplateLink);
        
        // Panggil fungsi saat halaman pertama kali dimuat
        toggleOrmawa();
        showTemplateLink();
    });
</script>
@endpush