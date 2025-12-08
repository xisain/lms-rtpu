<?php

namespace App\Http\Controllers;

use App\Models\course;
use App\Models\CoursePurchase;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\progress;
use App\Models\Role;;
use App\Models\Category;
use App\Models\subscription;

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
        $role = Role::all();           // ⬅ Tambahkan ini agar role tampil di profile.edit
        $category = Category::all();   // kalau dipakai

        return view('profile.edit', compact('user', 'role', 'category'));
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
            'password' => 'nullable|min:6|confirmed'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
