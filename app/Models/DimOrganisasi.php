<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DimOrganisasi extends Model
{
    protected $table = 'dim_organisasi';
    protected $primaryKey = 'id_organisasi';
    public $timestamps = false;

    protected $fillable = [
        'nama_organisasi',
        'tipe_organisasi'
    ];
}