<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    protected $fillable = [
        'category',
        'description',
    ];

    public function course() {
        return $this->hasMany(course::class);
    }
}
