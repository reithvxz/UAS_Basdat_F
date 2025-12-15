<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DimMahasiswa extends Model
{
    protected $table = 'dim_mahasiswa';
    protected $primaryKey = 'id_mahasiswa';
    
    // id_mahasiswa auto-increment (default), jadi $incrementing = true (default)
    public $timestamps = false;

    protected $fillable = [
        'nim',
        'nama_mahasiswa',
        'prodi',
        'angkatan',
        'fakultas'
    ];
}