<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class quiz_question extends Model
{
     use HasFactory;

    protected $fillable = [
        'quiz_id',
        'pertanyaan'
    ];

    /**
     * Get the quiz this question belongs to.
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Get the options for this question.
     */
    public function options(): HasMany
    {
        return $this->hasMany(quiz_option::class);
    }

    /**
     * Get the correct option for this question.
     */
    public function getCorrectOption()
    {
        return $this->options()->where('is_correct', true)->first();
    }
}
