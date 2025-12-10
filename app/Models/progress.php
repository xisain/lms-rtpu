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
        // Ambil submaterial target dengan materialnya dan relasi material->course
        $submaterial = submaterial::with(['material' => function ($q) {
            $q->with('course');
        }])->findOrFail($submaterialId);
        $material = $submaterial->material;

        // --- Module-level dependency: require previous material completion ---
        // Ambil semua material untuk course ini, urutkan berdasarkan id (asumsi urutan id mewakili urutan modul)
        $materials = $material->course->material()->orderBy('id', 'asc')->get();

        // Jika material bukan yang pertama, pastikan material sebelumnya sudah selesai
        $materialIndex = $materials->search(function ($m) use ($material) {
            return $m->id === $material->id;
        });

        if ($materialIndex !== false && $materialIndex > 0) {
            $previousMaterial = $materials[$materialIndex - 1];

            // Cek apakah semua submateri di material sebelumnya selesai
            $allSubmaterialCompleted = $previousMaterial->submaterial()->get()->every(function ($s) use ($userId) {
                return static::isCompleted($userId, $s->id);
            });

            // Cek apakah quiz di material sebelumnya selesai (jika ada dan required)
            $quizCompleted = true;
            if ($previousMaterial->quiz) {
                // gunakan method model quiz::isCompleted
                $quizCompleted = $previousMaterial->quiz->isCompleted($userId);
            }

            if (! $allSubmaterialCompleted || ! $quizCompleted) {
                return false;
            }
        }

        // --- Within-material sequential check (existing behavior) ---
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
     public static function getCourseProgress(int $userId, int $courseId): float
    {
        $course = course::with(['material.submaterial', 'material.quiz'])->findOrFail($courseId);

        $totalItems = 0;
        $completedItems = 0;

        foreach($course->material as $material) {
            // Hitung submaterial
            $submaterialCount = $material->submaterial->count();
            $totalItems += $submaterialCount;

            $completedSubmaterials = static::where('user_id', $userId)
                ->whereIn('submaterial_id', $material->submaterial->pluck('id'))
                ->where('status', 'completed')
                ->count();
            $completedItems += $completedSubmaterials;

            // Hitung quiz jika ada
            if($material->quiz) {
                $totalItems++;
                if($material->quiz->isCompleted($userId)) {
                    $completedItems++;
                }
            }
        }

        return $totalItems > 0 ? ($completedItems / $totalItems) * 100 : 0;
    }
}
