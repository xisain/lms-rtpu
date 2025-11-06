<?php

namespace App\Http\Controllers;

use App\Models\course;
use App\Models\plan;
use App\Models\subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plan = Plan::orderBy('id', 'asc')->paginate(10);

        return view('admin.plan.index', compact('plan'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $course = course::all();
        return view('admin.plan.create', [
            'course' => $course,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration_in_days' => 'required|integer|min:1',
            'features' => 'nullable|string',
            'course' => 'required|array|min:1',
            'course.*' => 'exists:courses,id',
            'is_active' => 'required|boolean',
        ]);

        // Convert features string to array
        $features = !empty($validated['features'])
            ? array_map('trim', explode(',', $validated['features']))
            : [];

        // Simpan course sebagai array of integers
        $courseIds = array_map('intval', $validated['course']);

        // Create plan
        Plan::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'duration_in_days' => $validated['duration_in_days'],
            'features' => $features,
            'course' => $courseIds,  // simpan array IDs
            'is_active' => $validated['is_active'],
        ]);

        return redirect()->route('admin.plan.index')
            ->with('success', 'Plan berhasil ditambahkan!');
    }
    /**
     * Display the specified resource.
     */
    public function show(subscription $subscription) {}

    /**
     * Show the form for editing the specified resource.
     */
   public function edit($id)
    {
        $plan = plan::findOrFail($id);
        $course = Course::where('public', true)->get();
        return view('admin.plan.edit', compact('plan', 'course'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration_in_days' => 'required|integer|min:1',
            'features' => 'nullable|string',
            'course' => 'required|array|min:1',
            'course.*' => 'exists:courses,id',
            'is_active' => 'required|boolean',
        ]);
        // Convert features string to array
        $features = array_map('trim', explode(',', $validated['features']));

        // Simpan course sebagai array of integers
        $courseIds = array_map('intval', $validated['course']);
        // Update plan
        $plan = plan::find($id);
        $plan->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'duration_in_days' => $validated['duration_in_days'],
            'features' => $features,
            'course' => $courseIds,  // update array IDs
            'is_active' => $validated['is_active'],
        ]);
        return redirect()->route('admin.plan.index')
            ->with('success', 'Plan berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $plan = plan::findOrFail($id);
        $plan->delete();
         return redirect()->route('admin.plan.index')
            ->with('success', 'Plan Berhasil Di Hapus');
    }
}
