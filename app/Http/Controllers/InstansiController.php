<?php

namespace App\Http\Controllers;

use App\Models\Instansi;
use Illuminate\Http\Request;

class InstansiController extends Controller
{
    public function index()
    {
        $instansi = Instansi::all();
        return view('admin.instansi.index', compact('instansi'));
    }

    public function create()
    {
        return view('admin.instansi.create');
    }
public function store(Request $request)
{
    // Log paling awal - pastikan method ini dipanggil
    \Log::info('=== STORE METHOD DIPANGGIL ===');
    \Log::info('Request Method: ' . $request->method());
    \Log::info('Request URL: ' . $request->fullUrl());
    \Log::info('Request Headers:', $request->headers->all());
    \Log::info('Semua input:', $request->all());
    \Log::info('CSRF Token: ' . $request->header('X-CSRF-TOKEN'));

    try {
        \Log::info('Memulai validasi...');
        
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string|max:500',
            'telepon' => 'nullable|string|max:20'
        ]);

        \Log::info('✓ Validasi berhasil');
        \Log::info('Data tervalidasi:', $validated);

        \Log::info('Mencoba simpan ke database...');
        $instansi = Instansi::create($validated);
        
        \Log::info('✓ BERHASIL SIMPAN! ID: ' . $instansi->id);
        \Log::info('Data tersimpan:', $instansi->toArray());

        return redirect()->route('admin.instansi.index')
            ->with('success', 'Instansi berhasil ditambahkan!');

    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('✗ VALIDASI GAGAL');
        \Log::error('Errors:', $e->errors());
        
        throw $e; // Lempar ulang agar Laravel handle

    } catch (\Exception $e) {
        \Log::error('✗ ERROR: ' . $e->getMessage());
        \Log::error('File: ' . $e->getFile() . ':' . $e->getLine());
        \Log::error('Trace: ' . $e->getTraceAsString());

        return redirect()->back()
            ->withInput()
            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

    public function edit($id)
    {
        $instansi = Instansi::findOrFail($id);
        return view('admin.instansi.edit', compact('instansi'));
    }

    public function update(Request $request, $id)
    {
        $instansi = Instansi::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string|max:500',
            'telepon' => 'nullable|string|max:20'
        ]);

        try {
            $instansi->update($validated);
            return redirect()->route('admin.instansi.index')->with('success', 'Instansi berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $instansi = Instansi::findOrFail($id);
        $instansi->delete();

        return redirect()->route('admin.instansi.index')->with('success', 'Instansi berhasil dihapus!');
    }
}
