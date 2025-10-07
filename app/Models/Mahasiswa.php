<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Mahasiswa extends Authenticatable
{
    protected $primaryKey = 'mhs_id';
    protected $fillable = ['nama', 'nim', 'email', 'password'];
    protected $hidden = ['password'];
}