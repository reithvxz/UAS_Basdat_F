<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Lampiran extends Model
{
    public $timestamps = false;
    protected $fillable = ['surat_id', 'nama_file', 'file_path'];
}