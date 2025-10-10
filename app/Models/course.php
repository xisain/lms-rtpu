<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class course extends Model
{
    protected $fillable = [
        'category_id',
        'nama_course',
        'image_link',
        'slugs',
        'description',
        'isLimitedCourse',
        'start_date',
        'end_date',
        'maxEnrollment',
        'public'
    ];

    public function category() {
        return $this->belongsTo(category::class);
    }
    public function material() {
        return $this->hasMany(material::class);
    }
    public function enrollment() {
        return $this->hasMany(enrollment::class);
    }
}
