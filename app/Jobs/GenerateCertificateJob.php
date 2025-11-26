<?php

namespace App\Jobs;

use App\Models\certificate;
use App\Models\Course;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot;

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

            // Buat filename dan path
            $filename = "certificate_{$this->certificate->id}.pdf";
            $path = storage_path("app/public/certificates/{$filename}");

            // Render HTML dari Blade
            $html = view('certificates.template', [
                'certificate' => $this->certificate,
                'user' => $this->user,
                'course' => $this->course
            ])->render();
            // shell_exec('whoiam');
            // Generate PDF dengan Browsershot
            Browsershot::html($html) // Matiin  setChromePath, setNodeBinary, setNPMBinary yg dibawah kalo mau pake dev local
                ->setChromePath('/usr/local/bin/chromium-wrapper.sh')
                ->setNodeBinary('/usr/bin/node')
                ->setNpmBinary('/usr/bin/npm')
                ->setOption('args', [
                    '--no-sandbox',
                    '--disable-setuid-sandbox',
                    '--disable-dev-shm-usage',
                    '--disable-gpu',
                    '--disable-software-rasterizer',
                    '--disable-extensions',
                    '--disable-web-security',
                    '--disable-features=IsolateOrigins,site-per-process',
                    '--no-first-run',
                    '--no-zygote',
                    '--single-process',
                    '--disable-crashpad',
                ])
                ->margins(0, 0, 0, 0)
                ->landscape()
                ->format('A4')
                ->showBackground() // penting agar background & gradient muncul
                ->emulateMedia('screen')
                ->waitUntilNetworkIdle() // pastikan font & asset selesai dimuat
                ->savePdf($path);

            // Update database dengan path relatif
            $this->certificate->update([
                'pdf_path' => "certificates/{$filename}"
            ]);

            Log::info('Certificate generated successfully (Browsershot)', [
                'certificate_id' => $this->certificate->id,
                'path' => $path,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to generate certificate (Browsershot)', [
                'certificate_id' => $this->certificate->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
