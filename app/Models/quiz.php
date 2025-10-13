<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class quiz extends Model
{
    protected $fillable = [
        'material_id',
        'judul_quiz',
        'is_required',
    ];
    protected $cast = [
        'is_required'=> 'boolean',
    ];
    public function material(): BelongsTo {
        return $this->belongsTo(material::class);
    }
    public function questions() : HasMany {
        return $this->hasMany(quiz_question::class);
    }
    public function attemps() : HasMany {
        return $this->hasMany(quiz_attempt::class);
    }
    // Menggambil last Attempt dari user
    public function getLastAttempt($userId)
    {
        return $this->attemps()
        ->where('user_id', $userId)->orderBy('created_at', 'desc')->first();

    }
    // Check User Sudah Menyeselaikan Quiz Atau belum
    public function isCompleted($userId): bool
    {
        return $this->attemps()
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->exists();
    }

    public function canAccess($user): bool
    {
        // Check if all submaterials in the material are completed
        return $this->material->isAllSubmaterialCompleted($user->id);
    }

}
