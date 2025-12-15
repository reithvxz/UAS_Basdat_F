<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Mahasiswa extends Authenticatable
{
    use Notifiable;

    protected $table = 'mahasiswas'; // Pastikan nama tabel benar
    protected $primaryKey = 'mhs_id'; // Primary Key kamu

    protected $fillable = [
        'nama', 
        'nim', 
        'email', 
        'password', 
        'prodi'
    ];

    protected $hidden = [
        'password', 
        'remember_token'
    ];

    // INI PENTING: Beritahu Laravel kolom password yang dipakai
    public function getAuthPassword()
    {
        return $this->password;
    }
}