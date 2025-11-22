<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot;
use App\Models\User;
use App\Models\course;
use App\Models\certificate;
use Carbon\Carbon;

class GenerateDummyCertificate extends Command
{
    protected $signature = 'generate:dummy-certificate';
    protected $description = 'Generate dummy certificate PDF using Browsershot';

    public function handle()
    {
        $this->info('Preparing dummy data...');

        // ambil atau buat data dummy sederhana
        $user = User::first() ?? (object) ['name' => 'Student Dummy'];
        $course = course::first() ?? (object) ['nama_course' => 'Belajar Dasar Pemrograman Web', 'teacher' => (object) ['name' => 'admin']];
        $certificate = certificate::first() ?? (object) [
            'id' => 'dummy',
            'certificate_number' => 'CERT-DUMMY-12345',
            'issued_date' => Carbon::now(),
        ];

        // render blade menjadi HTML
        $html = view('certificates.template', [
            'user' => $user,
            'course' => $course,
            'certificate' => $certificate,
        ])->render();

        // pastikan folder ada
        if (!Storage::disk('public')->exists('certificates')) {
            Storage::disk('public')->makeDirectory('certificates');
        }

        $filename = "certificate_dummy_" . now()->format('Ymd_His') . ".pdf";
        $fullPath = storage_path("app/public/certificates/{$filename}");

        $this->info("Generating PDF to: {$fullPath}");
        $this->info(shell_exec('whoami'));
        Browsershot::html($html)
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
            ->margins(0,0,0,0)
            ->landscape()
            ->format('A4')
            ->showBackground()
            ->emulateMedia('screen')
            ->waitUntilNetworkIdle()
            ->savePdf($fullPath);

        $this->info("Saved PDF: storage/app/public/certificates/{$filename}");
        $this->info('Done.');
    }
}
