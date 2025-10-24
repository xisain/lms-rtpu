<?php

namespace App\Jobs;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenerateCertificateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    private $course;
    private $certificate;

    public function __construct(User $user, Course $course, Certificate $certificate)
    {
        $this->user = $user;
        $this->course = $course;
        $this->certificate = $certificate;
    }

    public function handle(): void
    {
        try {
            // Pastikan folder certificates ada
            if (!Storage::disk('public')->exists('certificates')) {
                Storage::disk('public')->makeDirectory('certificates');
            }

            // Generate PDF
            $pdf = PDF::loadView('certificates.template', [
                'certificate' => $this->certificate,
                'user' => $this->user,
                'course' => $this->course
            ])->setPaper('a4', 'landscape');

            // Save PDF
            $filename = "certificate_{$this->certificate->id}.pdf";
            $path = "certificates/{$filename}";

            // Simpan ke storage/app/public/certificates/
            if (!Storage::disk('public')->put($path, $pdf->output())) {
                throw new \Exception("Failed to save PDF file");
            }

            // Update certificate dengan path relatif (tanpa 'public/')
            $this->certificate->update([
                'pdf_path' => $path
            ]);

            \Log::info('Certificate generated successfully', [
                'certificate_id' => $this->certificate->id,
                'path' => $path
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to generate certificate', [
                'certificate_id' => $this->certificate->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e; // Re-throw untuk menandai job gagal
        }
}
}
