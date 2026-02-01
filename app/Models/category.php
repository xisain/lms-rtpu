<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    protected $fillable = [
        'category',
        'description',
        'type',
        'instansi_id',
        'is_private',
    ];

    public function course() {
        return $this->hasMany(course::class);
    }
    public function instansi() {
        return $this->belongsTo(Instansi::class);
    }
}
