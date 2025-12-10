<?php

namespace App\Http\Controllers;

use App\Models\course;
use App\Models\CoursePurchase;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\progress;
use App\Models\Role;;
use App\Models\category;
use App\Models\Instansi;
use App\Models\Jurusan;

class profileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show()
    {

        $auth = auth()->user()->id;
        $user = User::with(['enrollment.course.material.submaterial', 'subscriptions','subscriptions.plan','subscriptions.payment'])
            ->find($auth);
        // $subscription = subscription::where('user_id', $user->id)->with(['plan','payment'])->first();
        // dd($subscription->plan->nama);
        // Hitung progress per course
        $courseProgress = [];
        $totalCourses = $user->enrollment->count();
        $completedCourses = 0;
        $totalPercentage = 0;

        foreach ($user->enrollment as $enrollment) {
            $course = $enrollment->course;
            $totalSubmaterials = 0;
            $completedSubmaterials = 0;

            foreach ($course->material as $material) {
                $submaterialCount = $material->submaterial->count();
                $totalSubmaterials += $submaterialCount;

                $completed = progress::where('user_id', $user->id)
                    ->whereIn('submaterial_id', $material->submaterial->pluck('id'))
                    ->where('status', 'completed')
                    ->count();

                $completedSubmaterials += $completed;
            }

            $percentage = $totalSubmaterials > 0 ? round(($completedSubmaterials / $totalSubmaterials) * 100, 2) : 0;

            $courseProgress[$course->id] = [
                'total' => $totalSubmaterials,
                'completed' => $completedSubmaterials,
                'percentage' => $percentage
            ];

            $totalPercentage += $percentage;

            if ($percentage == 100) {
                $completedCourses++;
            }
        }

        $averageProgress = $totalCourses > 0 ? round($totalPercentage / $totalCourses, 0) : 0;

        return view('profile.show', compact('user', 'courseProgress', 'totalCourses', 'completedCourses', 'averageProgress'));
    }

    public function transactionList(){
        $transaction = CoursePurchase::where('user_id', auth()->user()->id)->get();
        return view('profile.transaction.index',['transactions'=> $transaction]);
    }

    public function transactionDetail($id){
        $transaction = CoursePurchase::where('user_id', auth()->user()->id)->where('id', $id)->first();
        return view('profile.transaction.detail',
        [
         'transaction'=> $transaction,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $user = auth()->user();
        $role = Role::all();
        $instansi = Instansi::all();
        $jurusan = Jurusan::all();
        $category = category::all();   // kalau dipakai

        return view('profile.edit', compact('user', 'role', 'category', 'instansi','jurusan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'instansi_id' => 'nullable|exists:instansi,id',
            'jurusan_id' => 'nullable|exists:jurusan,id',
            'roles_id' => 'nullable|exists:roles,id'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        // Update instansi_id jika diisi
        if ($request->filled('instansi_id')) {
            $user->instansi_id = $request->instansi_id;
        }

        // Update jurusan_id jika diisi
        if ($request->filled('jurusan_id')) {
            $user->jurusan_id = $request->jurusan_id;
        }


        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui!');
    }


    /**
     * Store instansi from profile edit
     */
    public function storeInstansi(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'alamat' => 'nullable|string|max:500',
                'telepon' => 'nullable|string|max:20'
            ]);

            $instansi = Instansi::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Instansi berhasil ditambahkan!',
                'data' => $instansi
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store jurusan from profile edit
     */
    public function storeJurusan(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|unique:jurusan',
            'nama' => 'required|string|max:255'
        ]);

        try {
            $jurusan = Jurusan::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Jurusan berhasil ditambahkan!',
                'data' => $jurusan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
