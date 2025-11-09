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
public function submaterial()
{
    return $this->hasMany(submaterial::class, 'material_id', 'id');
}

    public function progress() {
        return $this->hasMany(progress::class);
    }
    public function quiz() {
        return $this->hasOne(quiz::class);
    }

    public function isAllSubmaterialCompleted(int $userId): bool
    {
        $totalSubmaterials = $this->submaterial()->count();
        $completedCount = progress::where('user_id', $userId)
        ->whereIn('submaterial_id', $this->submaterial->pluck('id'))
        ->where('status','completed')->count();
        return $totalSubmaterials > 0 && $completedCount === $totalSubmaterials;
    }
}
