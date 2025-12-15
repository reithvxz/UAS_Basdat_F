<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DimJenisSurat extends Model
{
    protected $table = 'dim_jenis_surat';
    protected $primaryKey = 'id_jenis_surat';
    public $timestamps = false;

    protected $fillable = [
        'jenis_surat',
        'tipe_surat'
    ];
}