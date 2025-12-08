<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Instansi extends Model
{
    use HasFactory;

    protected $table = 'instansi';

    protected $fillable = [
        'nama',
        'alamat',
        'email',
        'telepon'
    ];

    // Jika relasi ke users
    public function users()
    {
        return $this->hasMany(User::class, 'instansi_id');
    }
}
