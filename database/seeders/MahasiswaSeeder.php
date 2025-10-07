<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('mahasiswas')->insert([
            ['mhs_id' => 1, 'nim' => '164231088', 'nama' => 'Okan Athallah Maredith', 'email' => 'okan.athallah.maredith@ftmm.unair.ac.id', 'password' => 'okan'],
            ['mhs_id' => 2, 'nim' => '164231080', 'nama' => 'Ario Rizky', 'email' => 'ario.rizky@ftmm.unair.ac.id', 'password' => 'ario'],
        ]);
    }
}