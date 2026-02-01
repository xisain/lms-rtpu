<?php

namespace App\Http\Controllers;

use App\Models\category;
use Illuminate\Http\Request;
use SweetAlert2\Laravel\Swal;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = category::latest()->paginate(10);

        return view('admin.category.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'nullable|in:pekerti,pelatihan',
            'instansi_id' => 'nullable|exists:instansi,id',
            'is_private' => 'nullable|boolean',
        ]);

        category::create($validated);

        return redirect()->route('admin.category.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        return view('admin.category.edit', compact('category'));
    }

    public function update($id, Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'nullable|in:pekerti,pelatihan',
            'instansi_id' => 'nullable|exists:instansi,id',
            'is_private' => 'nullable|boolean',
        ]);

        $category = category::findOrFail($id);
        $category->update($validated);

        return redirect()->route('admin.category.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {

        $category = category::findOrFail($id);
        Swal::success([
            'title' => 'Berhasil',
            'text' => 'Kategori "'.$category->category.'" Berhasil di hapus',
        ]);
        $category->delete();

        return redirect()->route('admin.category.index')
            ->with('success', 'Category deleted successfully.');
    }
}
