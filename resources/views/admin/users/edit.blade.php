@extends('layout.sidebar')

@section('content')
<div class="container mx-auto px-4 py-8 -mt-7">
    <h1 class="text-3xl font-bold mb-6">Edit User</h1>

    <!-- Card Wrapper -->
    <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-200">
        <form action="{{ route('admin.user.update', $user) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-1">Nama</label>
                <input type="text" name="name"
                       value="{{ old('name', $user->name) }}"
                       class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email" name="email"
                       value="{{ old('email', $user->email) }}"
                       class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Password (Opsional)</label>
                <input type="password" name="password"
                       class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Kosongkan jika tidak diubah">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation"
                       class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Role</label>
                <select name="roles_id"
                        class="w-full border border-gray-300 rounded-lg p-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($role as $r)
                        <option value="{{ $r->id }}"
                            {{ old('roles_id', $user->roles_id) == $r->id ? 'selected' : '' }}>
                            {{ ucfirst($r->name) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Kategori</label>
                <select name="category_id"
                        class="w-full border border-gray-300 rounded-lg p-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($category as $c)
                        <option value="{{ $c->id }}"
                            {{ old('category_id', $user->category_id) == $c->id ? 'selected' : '' }}>
                            {{ ucfirst($c->category ?? 'Tanpa Nama') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="isActive" value="1"
                           class="mr-2 accent-blue-600 focus:ring-2 focus:ring-blue-500"
                           {{ old('isActive', $user->isActive) ? 'checked' : '' }}>
                    <span>User Aktif</span>
                </label>
            </div>

            <div class="pt-4 flex space-x-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                    Update
                </button>
                <a href="{{ route('admin.user.index') }}"
                   class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
