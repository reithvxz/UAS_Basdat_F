<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    protected $primaryKey = 'surat_id';

    protected $fillable = [
        'mhs_id',
        'jenis_surat_id',
        'nama_pengaju',
        'atas_nama',
        'ormawa_id',
        'tipe_surat',
        'perihal',
        'status'
    ];

    /**
     * Relasi ke model JenisSurat (satu surat punya satu jenis surat).
     */
    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class, 'jenis_surat_id');
    }

    /**
     * Relasi ke model Lampiran (satu surat punya satu lampiran).
     */
    public function lampiran()
    {
        return $this->hasOne(Lampiran::class, 'surat_id');
    }

    /**
     * Relasi ke model Mahasiswa (satu surat dimiliki oleh satu mahasiswa).
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mhs_id');
    }

    /**
     * INI ADALAH BAGIAN YANG HILANG
     * Relasi ke model Approval (satu surat punya banyak riwayat approval).
     */
    public function approvals()
    {
        return $this->hasMany(Approval::class, 'surat_id');
    }
}