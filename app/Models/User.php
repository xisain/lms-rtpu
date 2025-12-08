<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    protected $with = ['role'];
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'roles_id', // default 1
        'category_id', // default 1
        'instansi_id',
        'jurusan_id',
        'isActive' // default 0
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'roles_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function enrollment(){
        return $this->hasMany(enrollment::class);
    }
    public function role() {
        return $this->belongsTo(role::class, 'roles_id');
    }
    public function teacher(){
        return $this->hasMany(course::class);
    }
    public function progress() {
        return $this->hasMany(progress::class);
    }
    public function subscriptions()
    {
        return $this->hasMany(subscription::class);
    }
    public function hasPendingSubscription(?int $planId = null): bool
    {
        $query = $this->subscriptions()->where('status', 'waiting_approval');

        if ($planId) {
            $query->where('plan_id', $planId);
        }

        return $query->exists();
    }


    public function hasActiveSubscription(?int $planId = null): bool
    {
        $query = $this->subscriptions()
            ->where('status', 'approved')
            ->where('ends_at', '>', now());

        if ($planId) {
            $query->where('plan_id', $planId);
        }

        return $query->exists();
    }


    public function getPendingSubscriptions()
    {
        return $this->subscriptions()
            ->where('status', 'waiting_approval')
            ->with(['plan', 'paymentMethod'])
            ->get();
    }


    public function getActiveSubscription()
    {
        return $this->subscriptions()
            ->where('status', 'approved')
            ->where('ends_at', '>', now())
            ->first();
    }
    public function coursePurchase(){
        return $this->hasMany(CoursePurchase::class);
    }
}
