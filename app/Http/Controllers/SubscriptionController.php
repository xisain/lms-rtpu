<?php

namespace App\Http\Controllers;

use App\Mail\enrollCourse;
use App\Models\course;
use App\Models\CoursePurchase;
use App\Models\enrollment;
use App\Models\payment;
use App\Models\plan;
use App\Models\subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plan = plan::orderBy('id', 'asc')->get();
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

        $features = ! empty($validated['features'])
            ? array_map('trim', explode(',', $validated['features']))
            : [];

        $courseIds = array_map('intval', $validated['course']);

        Plan::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'duration_in_days' => $validated['duration_in_days'],
            'features' => $features,
            'course' => $courseIds,
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
    public function update(Request $request, plan $plan)
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

        $features = array_map('trim', explode(',', $validated['features']));

        $courseIds = array_map('intval', $validated['course']);

        $plan->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'duration_in_days' => $validated['duration_in_days'],
            'features' => $features,
            'course' => $courseIds,
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

    public function viewPlan()
    {
        $plans = plan::where('is_active', true)->get();

        return view('subscription.plan', compact('plans'));
    }

    public function checkout(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);
        $paymentMethod = Payment::where('status', 'aktif')->get();

        return view('subscription.payment', compact('plan', 'paymentMethod'));

    }

    public function purchases(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'payment_method_id' => 'required|exists:payments,id',
            'notes' => 'nullable',
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'plan_id.required' => 'Silakan pilih paket langganan yang ingin dibeli.',
            'plan_id.exists' => 'Paket langganan yang dipilih tidak ditemukan.',

            'payment_method_id.required' => 'Silakan pilih metode pembayaran.',
            'payment_method_id.exists' => 'Metode pembayaran yang dipilih tidak valid.',

            'notes.string' => 'Catatan harus berupa teks.',

            'payment_proof.required' => 'Harap unggah bukti pembayaran Anda.',
            'payment_proof.file' => 'Bukti pembayaran harus berupa file.',
            'payment_proof.mimes' => 'Format file bukti pembayaran harus berupa JPG, JPEG, PNG, atau PDF.',
            'payment_proof.max' => 'Ukuran file bukti pembayaran tidak boleh lebih dari 2 MB.',
        ]);
        $validated['user_id'] = Auth::id();
        $image_path = '';
        if ($request->hasFile('payment_proof')) {
            $image_path = $request->file('payment_proof')->store('payment_proof', 'public');
        }
        $subscription = subscription::create([
            'user_id' => $validated['user_id'],
            'plan_id' => $validated['plan_id'],
            'payment_method_id' => $validated['payment_method_id'],
            'status' => 'waiting_approval',
            'payment_proof_link' => $image_path,
        ]);

        return redirect()->route('home')->with('success', 'pembelian Berhasil Sedang Menunggu Approval dari admin');
    }

    public function transactionTable(Request $request)
    {
        $query = subscription::query();

        // Filter by status if provided
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $subscriptions = $query->with(['payment', 'user', 'plan'])->latest()->paginate(15);

        $purchasesQuery = CoursePurchase::query();
        if ($request->has('status') && $request->status != '') {
            $purchasesQuery->where('status', $request->status);
        }
        $coursePurchases = $purchasesQuery->with(['payment', 'user', 'course'])->latest()->paginate(15, ['*'], 'purchases_page');

        return view('admin.transaction.index', compact('subscriptions', 'coursePurchases'));
    }

    public function approval(Request $request, $id)
    {
        try {
            $subscription = subscription::findOrFail($id);
            $plan = plan::where('id', $subscription->plan_id)->first();
            $duration = $plan->duration_in_days;

            // Validate the request
            $request->validate([
                'status' => 'required|in:approved,rejected',
                'notes' => 'required_if:status,rejected',

            ]);

            $subscription->status = $request->status;

            if ($request->status == 'approved') {
                $subscription->starts_at = Carbon::now();
                $subscription->ends_at = Carbon::now()->addDays($duration);
            } else {
                $subscription->notes = $request->notes;
            }

            $subscription->save();

            // Success message berdasarkan status
            $message = $request->status == 'approved'
                ? 'Transaksi berhasil disetujui!'
                : 'Transaksi berhasil ditolak!';

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses transaksi: '.$e->getMessage());
        }
    }

    /**
     * Approve or reject course purchase
     */
    public function approvePurchase(Request $request, $id)
    {
        try {
            $purchase = CoursePurchase::findOrFail($id);

            $request->validate([
                'status' => 'required|in:approved,rejected',
                'notes' => 'required_if:status,rejected',
            ]);

            $purchase->status = $request->status;

            if ($request->status == 'approved') {

                $existingEnrollment = enrollment::where('user_id', $purchase->user_id)
                    ->where('course_id', $purchase->course_id)
                    ->first();

                if (! $existingEnrollment) {
                    enrollment::create([
                        'user_id' => $purchase->user_id,
                        'course_id' => $purchase->course_id,
                    ]);
                    $user = User::find($purchase->user_id);
                    $course = Course::findOrFail($purchase->course_id);
                    Mail::to($user->email)->send(new enrollCourse($course, $user->name));
                }
            } else {
                $purchase->notes = $request->notes;
            }

            $purchase->save();

            $message = $request->status == 'approved'
                ? 'Pembelian course berhasil disetujui!'
                : 'Pembelian course berhasil ditolak!';

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses pembelian: '.$e->getMessage());
        }
    }
}
