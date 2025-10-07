<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrmawaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ormawas')->insert([
            ['ormawa_id' => 1, 'tipe' => 'HIMA', 'nama_ormawa' => 'HIMATESDA'],
            ['ormawa_id' => 2, 'tipe' => 'HIMA', 'nama_ormawa' => 'HMTI'],
            ['ormawa_id' => 3, 'tipe' => 'HIMA', 'nama_ormawa' => 'IME'],
            ['ormawa_id' => 4, 'tipe' => 'HIMA', 'nama_ormawa' => 'HIMANO'],
            ['ormawa_id' => 5, 'tipe' => 'HIMA', 'nama_ormawa' => 'HIMATERA'],
            ['ormawa_id' => 6, 'tipe' => 'BSO', 'nama_ormawa' => 'IRIS'],
            ['ormawa_id' => 7, 'tipe' => 'BSO', 'nama_ormawa' => 'Argon'],
            ['ormawa_id' => 8, 'tipe' => 'BSO', 'nama_ormawa' => 'Vena'],
            ['ormawa_id' => 9, 'tipe' => 'BSO', 'nama_ormawa' => 'EV-OS'],
            ['ormawa_id' => 10, 'tipe' => 'BSO', 'nama_ormawa' => 'I-Mercy'],
            ['ormawa_id' => 11, 'tipe' => 'BSO', 'nama_ormawa' => 'Rasena'],
            ['ormawa_id' => 12, 'tipe' => 'BSO', 'nama_ormawa' => 'Kombo'],
            ['ormawa_id' => 13, 'tipe' => 'BEM', 'nama_ormawa' => 'BEM FTMM'],
        ]);
    }
}