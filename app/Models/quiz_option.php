<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class quiz_option extends Model
{
        use HasFactory;

    protected $fillable = [
        'quiz_question_id',
        'teks_pilihan',
        'is_correct'
    ];

    protected $casts = [
        'is_correct' => 'boolean'
    ];

    /**
     * Get the question this option belongs to.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(quiz_question::class, 'quiz_question_id');
    }
}
