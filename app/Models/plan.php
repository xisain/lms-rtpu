<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'features',
        'course',  // tetap pakai JSON
        'duration_in_days',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'course' => 'array',  // cast ke array
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    // Relasi ke Subscription
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    // Helper method untuk mendapatkan Course objects dari array IDs
    public function getCourses()
    {
        if (empty($this->course)) {
            return collect([]);
        }

        return \App\Models\Course::whereIn('id', $this->course)->get();
    }

    // Helper method untuk cek apakah plan punya course tertentu
    public function hasCourse($courseId): bool
    {
        return in_array($courseId, $this->course ?? []);
    }

    // Accessor untuk mendapatkan courses sebagai collection
    protected function courses(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getCourses(),
        );
    }
}
