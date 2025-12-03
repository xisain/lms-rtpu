<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class submaterial extends Model
{
    protected $fillable = [
        'material_id',
        'nama_submateri',
        'type',
        'isi_materi',
        'hidden',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id', 'id');
    }
}
