<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisSuratSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jenis_surats')->insert([
            ['jenis_id' => 1, 'nama_surat' => 'Surat Izin Kegiatan', 'deskripsi' => 'Surat izin pelaksanaan kegiatan ormawa di fakultas'],
            ['jenis_id' => 2, 'nama_surat' => 'Surat Permohonan', 'deskripsi' => 'Surat permohonan pemakaian ruangan, barang inventaris, atau dispensasi kuliah'],
            ['jenis_id' => 3, 'nama_surat' => 'Surat Rekomendasi', 'deskripsi' => 'Surat rekomendasi/pengantar untuk lomba, magang, atau penelitian'],
            ['jenis_id' => 4, 'nama_surat' => 'Surat Tugas', 'deskripsi' => 'Surat tugas resmi untuk delegasi lomba, seminar, atau workshop'],
        ]);
    }
}