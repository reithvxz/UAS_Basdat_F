<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // ---------------------------------------------------------------------
        // 1. DATA MASTER: ADMIN (12345)
        // ---------------------------------------------------------------------
        $admins = [
            ['username' => 'bem01', 'nama' => 'Admin BEM', 'role' => 'BEM'],
            ['username' => 'akd01', 'nama' => 'Admin Akademik', 'role' => 'Akademik'],
            ['username' => 'sek01', 'nama' => 'Admin Sekretariat', 'role' => 'Sekretariat'],
            ['username' => 'dek01', 'nama' => 'Dekan FTMM', 'role' => 'Dekan'],
            ['username' => 'wd101', 'nama' => 'Wakil Dekan 1', 'role' => 'Wakil Dekan 1'],
            ['username' => 'dek02', 'nama' => 'Wakil Dekan 2', 'role' => 'Wakil Dekan 1'], // <<< TAMBAHAN UNTUK dek02
        ];

        foreach ($admins as $admin) {
            DB::table('users')->insert([
                'nama' => $admin['nama'],
                'username' => $admin['username'],
                'password' => '12345',
                'role' => $admin['role'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ---------------------------------------------------------------------
        // 2. DATA MASTER: JENIS SURAT
        // ---------------------------------------------------------------------
        DB::table('jenis_surats')->insert([
            ['jenis_id' => 1, 'nama_surat' => 'Surat Izin Kegiatan'],
            ['jenis_id' => 2, 'nama_surat' => 'Surat Permohonan'],
            ['jenis_id' => 3, 'nama_surat' => 'Surat Rekomendasi'],
            ['jenis_id' => 4, 'nama_surat' => 'Surat Tugas'],
            ['jenis_id' => 5, 'nama_surat' => 'Surat Keterangan'],
        ]);

        // ---------------------------------------------------------------------
        // 3. DATA MASTER: ORMAWA
        // ---------------------------------------------------------------------
        $ormawas = [
            ['nama' => 'Himatesda (TSD)', 'tipe' => 'HIMA'],
            ['nama' => 'Himano (Nanoteknologi)', 'tipe' => 'HIMA'],
            ['nama' => 'HMTI (Teknik Industri)', 'tipe' => 'HIMA'],
            ['nama' => 'HIMATERA (Robotika)', 'tipe' => 'HIMA'],
            ['nama' => 'IME (Teknik Elektro)', 'tipe' => 'HIMA'],
            ['nama' => 'Iris', 'tipe' => 'BSO'],
            ['nama' => 'Argon', 'tipe' => 'BSO'],
            ['nama' => 'Vena', 'tipe' => 'BSO'],
            ['nama' => 'EV-OS', 'tipe' => 'BSO'],
            ['nama' => 'I-Mercy', 'tipe' => 'BSO'],
            ['nama' => 'Rasena', 'tipe' => 'BSO'],
            ['nama' => 'Kombo', 'tipe' => 'BSO'],
            ['nama' => 'BEM FTMM', 'tipe' => 'BEM'],
            ['nama' => 'BLM FTMM', 'tipe' => 'BLM'], // Tambah BLM
        ];

        foreach ($ormawas as $index => $o) {
            DB::table('ormawas')->insert([
                'ormawa_id' => $index + 1,
                'nama_ormawa' => $o['nama'],
                'tipe' => $o['tipe'],
            ]);
        }

        // ---------------------------------------------------------------------
        // 4. DATA MAHASISWA (5 ORANG)
        // ---------------------------------------------------------------------
        $mahasiswas = [
            ['nama' => 'OKAN ATHALLAH MAREDITH', 'nim' => '164231088', 'pass' => 'okan', 'prodi' => 'Teknologi Sains Data'],
            ['nama' => 'ARIO RIZKY MUHAMMAD', 'nim' => '165231080', 'pass' => 'ario', 'prodi' => 'Teknik Industri'],
            ['nama' => 'ATHALIA ANDRIA LOLY ARUAN', 'nim' => '162231110', 'pass' => 'athalia', 'prodi' => 'Rekayasa Nanoteknologi'],
            ['nama' => 'BUNGA AMANDA AURORA', 'nim' => '166231098', 'pass' => 'bunga', 'prodi' => 'Teknik Elektro'],
            ['nama' => 'RATU APHRODITE CINTA AURORA', 'nim' => '163231009', 'pass' => 'ratu', 'prodi' => 'Teknik Robotika'],
        ];

        foreach ($mahasiswas as $index => $mhs) {
            DB::table('mahasiswas')->insert([
                'mhs_id' => $index + 1,
                'nama' => $mhs['nama'],
                'nim' => $mhs['nim'],
                'email' => strtolower(explode(' ', $mhs['nama'])[0]) . '@ftmm.unair.ac.id',
                'password' => $mhs['pass'],
                'prodi' => $mhs['prodi'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ---------------------------------------------------------------------
        // 5. DATA DUMMY SURAT (100 SURAT)
        // ---------------------------------------------------------------------
        $suratIdCounter = 1;
        $perihals = ['Permohonan Keringanan UKT', 'Izin Kegiatan LDKK', 'Peminjaman Aula', 'Surat Tugas Delegasi', 'Rekomendasi Beasiswa', 'Izin Absensi', 'Peminjaman Sound System', 'Permohonan Data TA', 'Undangan Pemateri', 'Surat Pengantar Magang', 'Izin Dies Natalis', 'Laporan Pertanggungjawaban'];
        $roles = ['BEM', 'Akademik', 'Sekretariat', 'Dekan'];

        foreach ($mahasiswas as $mhsIndex => $mhs) {
            $mhsId = $mhsIndex + 1;
            
            // Setiap mahasiswa buat 20 surat (Total 5 org x 20 = 100 Surat)
            for ($i = 0; $i < 20; $i++) {
                
                // --- A. LOGIKA WAKTU (DIJAMIN MUNCUL DI SEMUA GRAFIK) ---
                if ($i < 4) { 
                    // 20% Data -> 7 HARI TERAKHIR
                    $createdAt = Carbon::now()->subDays(rand(0, 6))->setHour(rand(8, 16));
                } 
                elseif ($i < 10) { 
                    // 30% Data -> BULAN INI
                    $createdAt = Carbon::now()->startOfMonth()->addDays(rand(0, 15))->setHour(rand(8, 16));
                } 
                else { 
                    // 50% Data -> SEPANJANG TAHUN
                    $createdAt = Carbon::now()->subMonths(rand(1, 8))->setDay(rand(1, 28))->setHour(rand(8, 16));
                }
                
                // --- B. ATRIBUT LAIN ---
                $jenisId = rand(1, 5);
                $tipe = (rand(0, 1) == 0) ? 'Scan' : 'Fisik';
                
                $opsiAtasNama = ['Mahasiswa', 'HIMA', 'BSO', 'BEM', 'BLM'];
                $atasNama = $opsiAtasNama[$i % 5];
                
                $ormawaId = null;
                if ($atasNama == 'HIMA') $ormawaId = rand(1, 5);
                if ($atasNama == 'BSO') $ormawaId = rand(6, 12);
                if ($atasNama == 'BEM') $ormawaId = 13;
                if ($atasNama == 'BLM') $ormawaId = 14;

                // --- C. LOGIKA STATUS ---
                // 40% Selesai, 20% Ditolak, 30% Pending, 10% Dibatalkan
                $randStatus = rand(1, 10);
                $rolePenolak = null;

                if ($randStatus <= 4) { 
                    $statusAkhir = 'Selesai'; 
                } elseif ($randStatus <= 6) { 
                    $statusAkhir = 'Ditolak'; 
                    $rolePenolak = $roles[rand(0, 2)];
                } elseif ($randStatus <= 9) {
                    $pendingRoles = ['Menunggu BEM', 'Menunggu Akademik', 'Menunggu Sekretariat', 'Menunggu Dekan'];
                    $statusAkhir = $pendingRoles[rand(0, 3)]; 
                } else {
                    $statusAkhir = 'Dibatalkan'; // Status khusus Dibatalkan
                }

                // Insert Surat
                DB::table('surats')->insert([
                    'surat_id' => $suratIdCounter,
                    'mhs_id' => $mhsId,
                    'jenis_surat_id' => $jenisId,
                    'nama_pengaju' => $mhs['nama'],
                    'atas_nama' => $atasNama,
                    'ormawa_id' => $ormawaId,
                    'tipe_surat' => $tipe,
                    'perihal' => $perihals[rand(0, count($perihals)-1)],
                    'status' => $statusAkhir,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt->copy()->addDays(rand(1,5))->addHours(rand(1,10)), 
                ]);

                // --- D. LOGIKA APPROVAL HISTORY ---
                if ($statusAkhir != 'Dibatalkan') { // Tidak perlu approval jika dibatalkan
                    if ($statusAkhir != 'Menunggu BEM') {
                        // BEM
                        $st = ($statusAkhir == 'Ditolak' && $rolePenolak == 'BEM') ? 'Rejected' : 'Approved';
                        DB::table('approvals')->insert(['surat_id'=>$suratIdCounter, 'role'=>'BEM', 'status'=>$st, 'catatan'=>($st=='Rejected'?'Format salah':null), 'approved_at'=>$createdAt->copy()->addHours(rand(1,5))]);
                        if($st=='Rejected'){ $suratIdCounter++; continue; }
                        
                        if ($statusAkhir != 'Menunggu Akademik') {
                            // Akademik
                            $st = ($statusAkhir == 'Ditolak' && $rolePenolak == 'Akademik') ? 'Rejected' : 'Approved';
                            DB::table('approvals')->insert(['surat_id'=>$suratIdCounter, 'role'=>'Akademik', 'status'=>$st, 'catatan'=>($st=='Rejected'?'Data akademik tidak valid':null), 'approved_at'=>$createdAt->copy()->addHours(rand(6,24))]);
                            if($st=='Rejected'){ $suratIdCounter++; continue; }

                            if ($statusAkhir != 'Menunggu Sekretariat') {
                                // Sekretariat
                                $st = ($statusAkhir == 'Ditolak' && $rolePenolak == 'Sekretariat') ? 'Rejected' : 'Approved';
                                DB::table('approvals')->insert(['surat_id'=>$suratIdCounter, 'role'=>'Sekretariat', 'status'=>$st, 'catatan'=>($st=='Rejected'?'Tolong revisi konten surat':null), 'approved_at'=>$createdAt->copy()->addDays(rand(1,2))]);
                                if($st=='Rejected'){ $suratIdCounter++; continue; }

                                if ($statusAkhir == 'Selesai') {
                                    // Dekan
                                    DB::table('approvals')->insert(['surat_id'=>$suratIdCounter, 'role'=>'Dekan', 'status'=>'Approved', 'approved_at'=>$createdAt->copy()->addDays(rand(2,4))]);
                                }
                            }
                        }
                    }
                }
                $suratIdCounter++;
            }
        }
        
        // ---------------------------------------------------------------------
        // 6. DATA MASTER: TEMPLATE SURAT (BARU DIBUAT)
        // ---------------------------------------------------------------------
        $commonLink = 'https://docs.google.com/document/d/13p9OVK4H1Rgs5CnwxM-e64_je5rVsaSj/edit?usp=drive_link&ouid=117939435133508992500&rtpof=true&sd=true';

        DB::table('template_surats')->insert([
            ['jenis_surat_id' => 1, 'nama_template' => 'Surat Izin Kegiatan (Template)', 'file_link' => $commonLink],
            ['jenis_surat_id' => 2, 'nama_template' => 'Surat Permohonan (Template)', 'file_link' => $commonLink],
            ['jenis_surat_id' => 3, 'nama_template' => 'Surat Rekomendasi (Template)', 'file_link' => $commonLink],
            ['jenis_surat_id' => 4, 'nama_template' => 'Surat Tugas (Template)', 'file_link' => $commonLink],
            ['jenis_surat_id' => 5, 'nama_template' => 'Surat Keterangan (Template)', 'file_link' => $commonLink],
        ]);
    }
}