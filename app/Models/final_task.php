<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class final_task extends Model
{
    protected $fillable = [
        'course_id','instruksi'
    ];
    public function course(){
        return $this->belongsTo(Course::class);
    }
    public function submission(){
        return $this->hasMany((final_task_submission::class));
    }
    public function review() {
        return $this->hasMany((final_task_review::class));
    }
}
