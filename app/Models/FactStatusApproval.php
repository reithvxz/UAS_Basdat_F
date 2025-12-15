<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FactStatusApproval extends Model
{
    protected $table = 'fact_status_approval';
    public $timestamps = false;
    public $incrementing = false;

    public function admin() { return $this->belongsTo(DimAdmin::class, 'id_admin'); }
}