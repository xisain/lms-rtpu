<?php

namespace App\Http\Controllers;

use App\Mail\tolakAccount;
use App\Mail\WelcomeMail;
use App\Models\category;
use App\Models\course;
use App\Models\enrollment;
use App\Models\Instansi;
use App\Models\role;
use App\Models\subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use SweetAlert2\Laravel\Swal;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('role')->orderBy('id', 'asc')->get();
        $userNeedActivate = User::where('isActive', false)->count();

        return view('admin.users.index', compact('users', 'userNeedActivate'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $role = role::all();
        $category = category::all();

        return view('admin.users.edit', compact('user', 'role', 'category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'roles_id' => 'required|exists:roles,id',
            'category_id' => 'required|exists:categories,id',
            'isActive' => 'boolean',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['isActive'] = $request->has('isActive');

        $user->update($validated);
        Swal::success([
            'Title' => 'Berhasil',
            'text' => 'User Berhasil di Edit',
        ]);

        return redirect()->route('admin.user.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $delete = User::findOrFail($id);
        Swal::success([
            'Title' => 'Berhasil',
            'text' => 'User Berhasil di hapus',
        ]);
        $delete->delete();

        return redirect()->route('admin.user.index')->with('success', 'user berhasil di hapus');
    }

    public function createBulkUser()
    {

        $course = Course::get();
        $instansi = Instansi::get();

        return view('admin.users.create', ['course' => $course, 'instansi'=>$instansi]);
    }

    public function storeBulkUser(Request $request)
    {
        // dd($request->request);
        $request->validate([
            'instansi_id'=> 'required|exists:instansi,id',
            'course_id' => 'required|exists:courses,id',
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
            'default_password' => 'required|string',
        ]);
        $course = course::find($request->course_id);
        try {
            $file = $request->file('csv_file');
            $csv = array_map('str_getcsv', file($file));

            if (isset($csv[0][0])) {
                $csv[0][0] = str_replace("\xEF\xBB\xBF", '', $csv[0][0]);
            }

            $headerIndex = 0;
            foreach ($csv as $index => $row) {
                if (! empty($row[0]) && $row[0] !== '' && strpos($row[0], '#') !== 0) {
                    if (strtolower(trim($row[0])) === 'email') {
                        $headerIndex = $index;
                        break;
                    }
                }
            }

            $header = array_map('trim', $csv[$headerIndex]);

            $csv = array_slice($csv, $headerIndex + 1);

            $existingEmails = User::pluck('email')->map(function ($email) {
                return strtolower(trim($email));
            })->toArray();

            $successCount = 0;
            $errors = [];
            DB::beginTransaction();

            foreach ($csv as $index => $row) {
                $actualLineNumber = $headerIndex + $index + 2;

                if (empty(array_filter($row)) || (isset($row[0]) && strpos($row[0], '#') === 0)) {
                    continue;
                }

                if (count($header) !== count($row)) {
                    $errors[] = "Baris $actualLineNumber: Jumlah kolom tidak sesuai";

                    continue;
                }

                $data = array_combine($header, $row);

                // Validate email
                $email = trim($data['email'] ?? '');
                if (empty($email) || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Baris $actualLineNumber: Email tidak valid";

                    continue;
                }

                // Validate name
                $name = trim($data['name'] ?? '');
                if (empty($name)) {
                    $errors[] = "Baris $actualLineNumber: Nama tidak boleh kosong";

                    continue;
                }

                if (in_array(strtolower($email), $existingEmails)) {
                    $errors[] = "Baris $actualLineNumber: Email $email sudah terdaftar";

                    continue;
                }

                $password = !empty($data['password']) ? trim($data['password']) : $request->default_password;
                try {

                    $user = User::create([
                        'name' => $name,
                        'email' => $email,
                        'password' => Hash::make($password),
                        'roles_id' => 3,
                        'category_id' => 1,
                        'instansi_id'=>$request->instansi_id,
                        'isActive' => true,
                    ]);

                    if ($user && $user->id) {
                        \Log::debug("User created successfully at line $actualLineNumber", [
                            'user_id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email,
                            'created_at' => $user->created_at,
                        ]);
                    } else {
                        \Log::error("User creation returned null at line $actualLineNumber", [
                            'name' => $name,
                            'email' => $email,
                        ]);
                        $errors[] = "Baris $actualLineNumber: User dibuat tapi tidak ada ID";

                        continue;
                    }
                    $enrolled = enrollment::create([
                        'course_id' => $request->course_id,
                        'user_id' => $user->id,
                    ]);

                    // $subscription = subscription::create([
                    //     'plan_id' => $request->plan_id,
                    //     'user_id' => $user->id,
                    //     'payment_method_id' => 1,
                    //     'starts_at' => now(),
                    //     'ends_at' => now()->addDays($plan->duration_in_days),
                    //     'status' => 'approved',
                    //     'payment_proof_link' => 'string',
                    // ]);

                    // Debug: Cek apakah subscription berhasil dibuat
                    \Log::debug("Enrollment created for user {$user->id}", [
                        'enrollment_id' => $enrolled->id ?? 'null',
                        // 'plan_id' => $request->plan_id,
                    ]);

                    $existingEmails[] = strtolower($email);

                    // Send email if checked
                    if ($request->send_email) {
                        try {
                            Mail::to($user->email)->send(new WelcomeMail($user, $course));
                        } catch (\Exception $e) {
                            \Log::error("Failed to send welcome email to {$user->email}", [
                                'error' => $e->getMessage(),
                            ]);
                        }
                    }

                    $successCount++;

                } catch (\Exception $e) {
                    \Log::error("Failed to create user at line $actualLineNumber", [
                        'name' => $name,
                        'email' => $email,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);

                    $errors[] = "Baris $actualLineNumber: Gagal membuat user - ".$e->getMessage();

                    continue;
                }
            }

            DB::commit();

            if ($successCount === 0) {
                return back()->with('error', 'Tidak ada user yang berhasil ditambahkan. '.implode(', ', $errors))->withInput();
            }

            $message = "$successCount user berhasil ditambahkan";
            if (! empty($errors)) {
                $message .= '. Error: '.implode(' | ', array_slice($errors, 0, 5));
                if (count($errors) > 5) {
                    $message .= ' dan '.(count($errors) - 5).' error lainnya';
                }
            }

            return redirect()->route('admin.user.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Bulk user import failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage())->withInput();
        }
    }

    public function downloadTemplate()
    {
        $fileName = 'template_bulk_user_'.date('Y-m-d').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$fileName.'"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');

            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, ['email', 'name', 'password']);

            // Sample data
            fputcsv($file, ['user1@example.com', 'John Doe', '' ]);
            fputcsv($file, ['user2@example.com', 'Jane Smith', '']);
            fputcsv($file, ['user3@example.com', 'Bob Johnson', '']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function activate()
    {
        $users = User::where('isActive', 'false')->get();

        return view('admin.users.activate', compact('users'));
    }

    public function approved(Request $request, $id)
    {
        $user = User::find($id);
        if (! $user) {
            return redirect()->back()->with('error', 'error');
        } else {
            $user->isActive = true;
            $user->save();

            return redirect()->back()->with('success', 'Pembuatan akun Di Setujui');
        }
    }

    public function rejected(Request $request, $id)
    {
        $user = User::find($id);
        if (! $user) {
            return redirect()->back()->with('error', 'error');
        } else {
            Mail::to($user->email)->send(new tolakAccount(
                $user->name,
                'Data identitas tidak lengkap.',
            ));
            $user->isActive = false;
            $user->delete();

            return redirect()->back()->with('success', 'Pembuatan akun di Tolak, akun akan di hapus');
        }
    }
}
