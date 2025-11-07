<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payment = Payment::get();
        return view('admin.payment.index', compact('payment'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.payment.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'nama.required' => 'Nama pembayaran wajib diisi',
            'account_name.required' => 'Nama pemilik rekening wajib diisi',
            'account_number.required' => 'Nomor rekening wajib diisi',
            'status.required' => 'Status wajib dipilih',
            'status.in' => 'Status harus aktif atau nonaktif',
        ]);

        Payment::create($validated);

        return redirect()->route('admin.payment.index')
            ->with('success', 'Metode pembayaran berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $payment = Payment::findOrFail($id);
        return view('admin.payment.edit', compact('payment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'nama.required' => 'Nama pembayaran wajib diisi',
            'account_name.required' => 'Nama pemilik rekening wajib diisi',
            'account_number.required' => 'Nomor rekening wajib diisi',
            'status.required' => 'Status wajib dipilih',
            'status.in' => 'Status harus aktif atau nonaktif',
        ]);

        $payment = Payment::findOrFail($id);
        $payment->update($validated);

        return redirect()->route('admin.payment.index')
            ->with('success', 'Metode pembayaran berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return redirect()->route('admin.payment.index')
            ->with('success', 'Metode pembayaran berhasil dihapus');
    }
}
