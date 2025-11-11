<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama',
        'account_number',
        'status',
        'account_name'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function subscription(): HasMany {
        return $this->hasMany(Subscription::class, 'payment_method_id');
    }

    // Scope untuk filter status aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    // Scope untuk filter status non-aktif
    public function scopeNonaktif($query)
    {
        return $query->where('status', 'nonaktif');
    }
}
