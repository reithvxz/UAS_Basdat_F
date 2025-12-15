<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DimAdmin extends Model
{
    protected $table = 'dim_admin';
    protected $primaryKey = 'id_admin';
    public $timestamps = false;

    protected $fillable = [
        'nama_admin',
        'role',
        'jabatan'
    ];
}