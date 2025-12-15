<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FactDurasiLayanan extends Model
{
    protected $table = 'fact_durasi_layanan';
    public $timestamps = false;
    public $incrementing = false;
    
    public function jenisSurat() { return $this->belongsTo(DimJenisSurat::class, 'id_jenis_surat'); }
}