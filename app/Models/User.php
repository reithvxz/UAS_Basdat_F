<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id'; // Sesuai SQL Anda

    protected $fillable = [
        'nama',      // Sesuai SQL Anda
        'username',  // Sesuai SQL Anda
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Beritahu Laravel password ada di kolom 'password'
    public function getAuthPassword()
    {
        return $this->password;
    }
}