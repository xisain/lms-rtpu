<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class progress extends Model
{
    protected $fillable = [
        'user_id',
        'submaterial_id',
        'status'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    public function user() {
        return $this->belongsTo(user::class);
    }

    public function submaterial() {
        return $this->belongsTo(submaterial::class);
    }

    // Cek apakah suatu submateri sudah selesai
    public static function isCompleted(int $userId, int $submaterialId): bool
    {
        return static::where('user_id', $userId)
            ->where('submaterial_id', $submaterialId)
            ->where('status', 'completed')
            ->exists();
    }

    // Cek apakah user bisa mengakses suatu submateri
    public static function canAccess(int $userId, int $submaterialId): bool
    {
        // Ambil submaterial target dengan materialnya
        $submaterial = submaterial::with('material')->findOrFail($submaterialId);
        $material = $submaterial->material;

        // Ambil semua submaterial dalam material yang sama, urutkan berdasar ID
        $submaterials = $material->submaterial()
            ->orderBy('id', 'asc')
            ->get();

        // Jika ini submaterial pertama, izinkan akses
        if ($submaterials->first()->id === $submaterialId) {
            return true;
        }

        // Cari submaterial sebelumnya
        $currentIndex = $submaterials->search(function($item) use ($submaterialId) {
            return $item->id === $submaterialId;
        });

        if ($currentIndex > 0) {
            $previousSubmaterial = $submaterials[$currentIndex - 1];
            // Cek apakah submaterial sebelumnya sudah selesai
            return static::isCompleted($userId, $previousSubmaterial->id);
        }

        return false;
    }

    // Dapatkan persentase penyelesaian untuk suatu materi
    public static function getMaterialProgress(int $userId, int $materialId): float
    {
        $material = material::with('submaterial')->findOrFail($materialId);
        $totalSubmaterials = $material->submaterial->count();

        if ($totalSubmaterials === 0) {
            return 0;
        }

        $completedCount = static::where('user_id', $userId)
            ->whereIn('submaterial_id', $material->submaterial->pluck('id'))
            ->where('status', 'completed')
            ->count();

        return ($completedCount / $totalSubmaterials) * 100;
    }
}
