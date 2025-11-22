<?php

namespace App\Models;

use Carbon\Carbon;
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
        'price',
        'is_paid',
        'teacher_id',
    ];

    public function category()
    {
        return $this->belongsTo(category::class);
    }

    public function material()
    {
        return $this->hasMany(material::class, 'course_id', 'id');
    }

    public function enrollment()
    {
        return $this->hasMany(enrollment::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function purchases()
    {
        return $this->hasMany(CoursePurchase::class);
    }

    public function expireCourse()
    {
        if (! $this->end_date) {
            return false;
        }

        return Carbon::now()->isAfter($this->end_date);
    }

    public function maxSlotEnrollment()
    {
        if (! $this->isLimitedCourse) {
            return false;
        }

        return $this->enrollment()->count() >= $this->maxEnrollment;
    }

    public function countEnrollment()
    {
        return $this->enrollment()->count();
    }
    
}
