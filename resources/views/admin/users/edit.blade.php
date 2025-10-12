@extends('layout.sidebar')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-xl font-bold mb-4">Edit User</h1>

    <form action="{{ route('admin.user.update', $user) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium">Nama</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border rounded p-2">
            @error('name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border rounded p-2">
            @error('email') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium">Password (Opsional)</label>
            <input type="password" name="password" class="w-full border rounded p-2"
                placeholder="Kosongkan jika tidak diubah">
            @error('password') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="w-full border rounded p-2">
        </div>

        <div>
            <label class="block text-sm font-medium">Role</label>
            <select name="roles_id" class="w-full border rounded p-2">
                @foreach($role as $r)
                <option value="{{ $r->id }}" {{ old('roles_id', $user->roles_id) == $r->id ? 'selected' : '' }}>
                    {{ ucfirst($r->name) }}
                </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium">Kategori</label>
            <select name="category_id" class="w-full border rounded p-2">
                @foreach($category as $c)
                <option value="{{ $c->id }}" {{ old('category_id', $user->category_id) == $c->id ? 'selected' : '' }}>
                    {{ ucfirst($c->category ?? 'Tanpa Nama') }}
                </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="inline-flex items-center">
                <input type="checkbox" name="isActive" value="1" class="mr-2" {{ old('isActive', $user->isActive) ?
                'checked' : '' }}>
                <span>User Aktif</span>
            </label>
        </div>
        <div class="pt-4">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
            <a href="{{ route('admin.user.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded">Batal</a>
        </div>
    </form>
</div>
@endsection
