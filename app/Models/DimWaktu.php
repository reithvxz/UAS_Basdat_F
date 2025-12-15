<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DimWaktu extends Model
{
    // Arahkan ke tabel dimensi di database
    protected $table = 'dim_waktu';

    // Primary Key (id_waktu format YYYYMMDD)
    protected $primaryKey = 'id_waktu';

    // Karena id_waktu kita isi manual (bukan auto-increment), set ke false
    public $incrementing = false;

    // Data Warehouse tidak butuh created_at/updated_at
    public $timestamps = false;

    protected $fillable = [
        'id_waktu',
        'tanggal',
        'tahun',
        'kuartal',
        'bulan',
        'hari',
        'minggu_ke'
    ];
}