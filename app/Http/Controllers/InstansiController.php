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
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string|max:500',
            'telepon' => 'nullable|string|max:20'
        ]);

        try {
            $instansi = Instansi::create($validated);
            
            // Jika request AJAX, return JSON
            if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => true,
                    'message' => 'Instansi berhasil ditambahkan!',
                    'data' => $instansi
                ]);
            }

            return redirect()->route('admin.instansi.index')
                ->with('success', 'Instansi berhasil ditambahkan!');
        } catch (\Exception $e) {
            if ($request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 400);
            }

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
