<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class progress extends Model
{
    protected $fillable  = [
        'status'
    ];

    public function user(){
        return $this->belongsToMany(user::class);
    }
    public function submaterial() {
        return $this->belongsToMany(submaterial::class);
    }
}
