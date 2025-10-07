<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class TemplateSurat extends Model
{
    public function jenisSurat() {
        return $this->belongsTo(JenisSurat::class, 'jenis_surat_id');
    }
}