<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Approval extends Model
{
    public $timestamps = false;
    protected $fillable = ['surat_id', 'role', 'status', 'catatan', 'approved_at'];
}