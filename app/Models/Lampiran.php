<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute; // Tambahkan ini jika menggunakan Laravel versi lebih baru

class Lampiran extends Model
{
    // Nama tabel jika tidak mengikuti konvensi Laravel (lampirans)
    // protected $table = 'Lampiran'; 

    // Primary key jika bukan 'id'
    // protected $primaryKey = 'lampiran_id'; 

    public $timestamps = false; // Karena tidak ada created_at/updated_at di tabel ini
    
    protected $fillable = [
        'surat_id', 
        'nama_file', 
        'file_path',
        'uploaded_at' // Pastikan uploaded_at ada di fillable jika Anda mengisinya secara manual
    ];

    /**
     * PERBAIKAN: Beritahu Laravel untuk memperlakukan 'uploaded_at' sebagai objek tanggal.
     */
    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    /**
     * Relasi ke model Surat (satu lampiran dimiliki oleh satu surat).
     */
    public function surat()
    {
        return $this->belongsTo(Surat::class, 'surat_id');
    }
}