<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ramsey\Uuid\Type\Integer;

class plan extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'features',
        'duration_in_days',
        'is_active',
    ];
    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean'
    ];

    public function subscription(): HasMany
    {
        return $this->hasMany(subscription::class);
    }

}
