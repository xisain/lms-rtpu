<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateCertificateJob;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CertificateController extends Controller
{
    public function generate(Course $course, User $user)
    {
        // Check if user has completed the course
        $progress = $user->progress()
            ->where('course_id', $course->id)
            ->where('status', 'completed')
            ->first();

        if (!$progress) {
            return response()->json(['message' => 'User has not completed this course'], 400);
        }

        // Check if certificate already exists
        $certificate = Certificate::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($certificate) {
            if ($certificate->pdf_path) {
                return response()->json([
                    'message' => 'Certificate already exists',
                    'certificate' => $certificate,
                    'pdf_url' => Storage::url($certificate->pdf_path)
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Certificate is being generated',
                    'certificate' => $certificate
                ], 202);
            }
        }

        // Generate unique certificate number
        $certificateNumber = 'CERT-' . Str::upper(Str::random(8));

        // Create certificate record
        $certificate = Certificate::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'certificate_number' => $certificateNumber,
            'issued_date' => Carbon::now(),
        ]);

        // Dispatch job to generate PDF
        GenerateCertificateJob::dispatch($user, $course, $certificate);

        return response()->json([
            'message' => 'Certificate generation has been queued',
            'certificate' => $certificate
        ], 202);
    }

    public function download(Certificate $certificate)
    {
        try {
            // Cek authentication
            if (!auth()->check()) {
                abort(401, 'Unauthenticated');
            }

            // Authorization: Hanya pemilik atau admin yang bisa download
            $user = auth()->user();
            if ($user->id !== $certificate->user_id && !$user->isAdmin()) {
                abort(403, 'Unauthorized access');
            }

            // Cek apakah PDF sudah ada
            if (empty($certificate->pdf_path)) {
                return back()->with('error', 'Certificate is still being generated. Please try again later.');
            }

            // Build file path
            $file_path = storage_path('app/public/' . $certificate->pdf_path);

            // Cek file existence
            if (!file_exists($file_path)) {
                \Log::error('Certificate file not found', [
                    'certificate_id' => $certificate->id,
                    'pdf_path' => $certificate->pdf_path,
                    'full_path' => $file_path
                ]);

                return back()->with('error', 'Certificate PDF not found. Please contact support.');
            }

            // Cek file readable
            if (!is_readable($file_path)) {
                \Log::error('Certificate file not readable', ['file_path' => $file_path]);
                return back()->with('error', 'Cannot read certificate file. Please contact support.');
            }

            // Log download activity
            \Log::info('Certificate downloaded', [
                'certificate_id' => $certificate->id,
                'user_id' => $user->id,
                'ip' => request()->ip()
            ]);

            // Sanitize filename
            $courseName = preg_replace('/[^A-Za-z0-9\-\_]/', '_', $certificate->course->name);
            $userName = preg_replace('/[^A-Za-z0-9\-\_]/', '_', $certificate->user->name);
            $filename = "certificate_{$courseName}_{$userName}.pdf";

            // Download file
            return response()->download($file_path, $filename, [
                'Content-Type' => 'application/pdf'
            ]);
        } catch (\Exception $e) {
            \Log::error('Download error', [
                'certificate_id' => $certificate->id ?? 'unknown',
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Error downloading certificate. Please try again.');
        }
    }

    public function status(Certificate $certificate)
    {
        if (!$certificate->pdf_path) {
            return response()->json([
                'message' => 'Certificate is still being generated',
                'status' => 'pending'
            ], 202);
        }

        if (!Storage::disk('public')->exists($certificate->pdf_path)) {
            return response()->json([
                'message' => 'Certificate PDF not found',
                'status' => 'error'
            ], 404);
        }

        return response()->json([
            'message' => 'Certificate is ready',
            'status' => 'completed',
            'certificate' => $certificate,
            'pdf_url' => Storage::disk('public')->url($certificate->pdf_path)
        ]);
    }
}
