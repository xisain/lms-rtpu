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
        'public',
        'teacher_id',
    ];

    public function category() {
        return $this->belongsTo(category::class);
    }
public function material()
{
    return $this->hasMany(Material::class, 'course_id', 'id');
}

    public function enrollment() {
        return $this->hasMany(enrollment::class);
    }
    public function teacher(){
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
