<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            ['user_id' => 1, 'username' => 'bem01', 'nama' => 'Sekretariat BEM FTMM', 'role' => 'BEM', 'password' => '12345'],
            ['user_id' => 2, 'username' => 'akd01', 'nama' => 'Staf Akademik', 'role' => 'Akademik', 'password' => '12345'],
            ['user_id' => 3, 'username' => 'sek01', 'nama' => 'Sekretariat Dekanat', 'role' => 'Sekretariat', 'password' => '12345'],
            ['user_id' => 4, 'username' => 'dek01', 'nama' => 'Dekan', 'role' => 'Dekan', 'password' => '12345'],
            ['user_id' => 5, 'username' => 'dek02', 'nama' => 'Wakil Dekan I', 'role' => 'Dekan', 'password' => '12345'],
        ]);
    }
}