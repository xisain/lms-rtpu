<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class material extends Model
{
    protected $fillable = [
        'nama_materi',
        'course_id'
    ];

    public function course() {
        return $this->belongsTo(course::class, 'course_id');
    }
    public function submaterial() {
        return $this->hasMany(submaterial::class,);
    }
    public function progress() {
        return $this->hasMany(progress::class);
    }
}
