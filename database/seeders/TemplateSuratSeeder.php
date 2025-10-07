<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TemplateSuratSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('template_surats')->insert([
            ['template_id' => 1, 'jenis_surat_id' => 1, 'nama_template' => 'Template SIK', 'file_link' => 'https://domain-kampus.ac.id/template/SuratIzinKegiatan.docx'],
            ['template_id' => 2, 'jenis_surat_id' => 2, 'nama_template' => 'Template Permohonan', 'file_link' => 'https://domain-kampus.ac.id/template/SuratPermohonan.docx'],
            ['template_id' => 3, 'jenis_surat_id' => 3, 'nama_template' => 'Template Rekomendasi', 'file_link' => 'https://domain-kampus.ac.id/template/SuratRekomendasi.docx'],
            ['template_id' => 4, 'jenis_surat_id' => 4, 'nama_template' => 'Template Surat Tugas', 'file_link' => 'https://domain-kampus.ac.id/template/SuratTugas.docx'],
        ]);
    }
}