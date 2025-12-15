<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FactPengajuan extends Model
{
    protected $table = 'fact_pengajuan'; // Nama tabel di DB
    public $timestamps = false; // Tidak butuh created_at/updated_at
    public $incrementing = false; // Fact table gak punya single ID

    // Relasi ke Dimensi (Penting untuk Join di Chart)
    public function waktu() { return $this->belongsTo(DimWaktu::class, 'id_waktu'); }
    public function jenisSurat() { return $this->belongsTo(DimJenisSurat::class, 'id_jenis_surat'); }
}