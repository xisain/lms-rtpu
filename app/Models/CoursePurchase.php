<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoursePurchase extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'payment_method_id',
        'price_paid',
        'status',
        'payment_proof_link',
        'notes',
    ];

    protected $casts = [
        'price_paid' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(course::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(payment::class, 'payment_method_id');
    }
}
