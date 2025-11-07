<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'payment_method_id',
        'starts_at',
        'end_at',
        'status',
        'payment_proof_link',
    ];
    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'price_paid'=> 'integer'
    ];
    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function plan() : BelongsTo {
        return $this->belongsTo(plan::class);
    }
    public function payment() : BelongsTo {
        return $this->belongsTo(Payment::class,'payment_method_id');
    }

}
