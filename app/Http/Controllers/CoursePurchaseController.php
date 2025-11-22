<?php

namespace App\Http\Controllers;

use App\Models\course;
use App\Models\CoursePurchase;
use App\Models\payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SweetAlert2\Laravel\Swal;

class CoursePurchaseController extends Controller
{
    /**
     * Show checkout form for a single course
     */
    public function checkout(Request $request, $slug)
    {
        $course = course::where('slugs', $slug)->firstOrFail();


        if (! $course->is_paid || ! $course->public) {
            return back()->with('error', 'Course tidak tersedia untuk dibeli.');
        }

        $paymentMethod = payment::where('status', 'aktif')->get();

        return view('course.purchase-checkout', compact('course', 'paymentMethod'));
    }

    /**
     * Process course purchase
     */
    public function purchase(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'payment_method_id' => 'required|exists:payments,id',
            'notes' => 'nullable|string',
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'course_id.required' => 'Silakan pilih course yang ingin dibeli.',
            'course_id.exists' => 'Course yang dipilih tidak ditemukan.',
            'payment_method_id.required' => 'Silakan pilih metode pembayaran.',
            'payment_method_id.exists' => 'Metode pembayaran yang dipilih tidak valid.',
            'payment_proof.required' => 'Harap unggah bukti pembayaran Anda.',
            'payment_proof.file' => 'Bukti pembayaran harus berupa file.',
            'payment_proof.mimes' => 'Format file bukti pembayaran harus berupa JPG, JPEG, PNG, atau PDF.',
            'payment_proof.max' => 'Ukuran file bukti pembayaran tidak boleh lebih dari 2 MB.',
        ]);

        $course = course::findOrFail($validated['course_id']);
        $validated['user_id'] = Auth::id();
        $validated['price_paid'] = $course->price;
        $validated['status'] = 'waiting_approval';


        $existingPurchase = CoursePurchase::where('user_id', $validated['user_id'])
            ->where('course_id', $validated['course_id'])
            ->whereIn('status', ['waiting_approval', 'approved'])
            ->first();

        if ($existingPurchase) {
            return back()->with('error', 'Anda sudah memiliki pembelian untuk course ini.');
        }

        $image_path = '';
        if ($request->hasFile('payment_proof')) {
            $image_path = $request->file('payment_proof')->store('course_payment_proof', 'public');
        }

        CoursePurchase::create([
            'user_id' => $validated['user_id'],
            'course_id' => $validated['course_id'],
            'payment_method_id' => $validated['payment_method_id'],
            'price_paid' => $validated['price_paid'],
            'status' => $validated['status'],
            'payment_proof_link' => $image_path,
            'notes' => $validated['notes'] ?? null,
        ]);
        return redirect()->route('home')->with('success', 'Pembelian course berhasil. Sedang menunggu approval dari admin.');
    }
}
