<?php

namespace App\Http\Controllers;

use App\Models\course;
use App\Models\CoursePurchase;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\progress;

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
        $user = User::with(['enrollment.course.material.submaterial', 'subscriptions','subscriptions.plan','subscriptions.payment','coursePurchase'])
            ->find($auth);
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
