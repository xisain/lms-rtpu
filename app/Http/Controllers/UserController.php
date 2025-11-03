<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\role;
use Illuminate\Http\Request;
use App\Models\User;
use SweetAlert2\Laravel\Swal;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('role')->orderBy('id','asc')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $role = role::all();
        $category = category::all();

        return view('admin.users.edit', compact('user', 'role', 'category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'roles_id' => 'required|exists:roles,id',
            'category_id' => 'required|exists:categories,id',
            'isActive' => 'boolean',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['isActive'] = $request->has('isActive');

        $user->update($validated);
        Swal::success([
            'Title' => 'Berhasil',
            'text'=>'User Berhasil di Edit'
        ]);

        return redirect()->route('admin.user.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       $delete = User::findOrFail($id);
       Swal::success([
            'Title' => 'Berhasil',
            'text'=>'User Berhasil di hapus'
        ]);
       $delete->delete();
       return redirect()->route('admin.user.index')->with('success', 'user berhasil di hapus');
    }
}
