<?php

namespace App\Http\Controllers;

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
        return view('admin.plan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|integer',
            'duration_in_days' => 'required|integer',
            'features' => 'required|string',
            'is_active' => 'boolean',
        ]);
        if ($request->has('features')) {
            $validated['features'] = array_map('trim', explode(',', $request->features));
        }


        plan::create($validated);

        return redirect()->route('admin.plan.index')->with('success', 'Berhasil Membuat Plan');
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
        $plan = Plan::find($id);

        return view('admin.plan.edit', compact('plan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'duration_in_days' => 'required|integer',
            'features' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($request->filled('features')) {
            $validated['features'] = array_map('trim', explode(',', $request->features));
        }
        $plan = Plan::find($id);
        $plan->update($validated);
        return redirect()->route('admin.plan.index')
            ->with('success', 'Berhasil Memperbarui Plan');
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
