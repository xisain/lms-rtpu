<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class final_task_submission extends Model
{
    protected $fillable = [
        'final_task_id',
        'user_id',
        'link_google_drive',
        'status',

    ];
    public function final_task(){
        return $this->belongsTo(final_task::class);
    }
    public function user(){
        return $this->belongsTo(user::class);
    }
    public function review()
    {
        return $this->hasOne(final_task_review::class);
    }
}
