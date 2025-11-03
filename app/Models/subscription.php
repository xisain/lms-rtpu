<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class subscription extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'start_at',
        'end_at',
        'status',
        'price_paid',
        'payment_method',
        'payment_status',
    ];
    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'price_paid'=> 'integer'
    ];
    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function plan() : BelongsTo {
        return $this->belongsTo(plan::class);
    }

}
