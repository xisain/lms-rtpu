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

            <!-- Password Field -->
            <div class="relative">
                <label class="block text-sm font-medium mb-1">Password (Opsional)</label>
                <input type="password" name="password" id="password"
                       class="w-full border border-gray-300 rounded-lg p-2 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Kosongkan jika tidak diubah">
                <button type="button" id="togglePassword" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 mt-6">
                    <svg xmlns="http://www.w3.org/2000/svg" id="eyeIcon" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password Field -->
            <div class="relative">
                <label class="block text-sm font-medium mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="w-full border border-gray-300 rounded-lg p-2 pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="button" id="toggleConfirmPassword" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 mt-6">
                    <svg xmlns="http://www.w3.org/2000/svg" id="eyeIconConfirm" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
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
                <button type="submit" class="bg-[#009999] hover:bg-[#0f5757] text-white px-4 py-2 rounded-lg transition">
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

<!-- Script Show/Hide Password -->
<script>
document.getElementById('togglePassword').addEventListener('click', function() {
    const input = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    icon.innerHTML = isHidden
        ? `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7
               0.37-1.179 0.913-2.278 1.61-3.258M9.88 9.88a3 3 0 104.24 4.24M3 3l18 18" />`
        : `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M2.458 12C3.732 7.943 7.523 5 12 5
               c4.478 0 8.268 2.943 9.542 7
               -1.274 4.057-5.064 7-9.542 7
               -4.477 0-8.268-2.943-9.542-7z" />`;
});

document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
    const input = document.getElementById('password_confirmation');
    const icon = document.getElementById('eyeIconConfirm');
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    icon.innerHTML = isHidden
        ? `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7
               0.37-1.179 0.913-2.278 1.61-3.258M9.88 9.88a3 3 0 104.24 4.24M3 3l18 18" />`
        : `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M2.458 12C3.732 7.943 7.523 5 12 5
               c4.478 0 8.268 2.943 9.542 7
               -1.274 4.057-5.064 7-9.542 7
               -4.477 0-8.268-2.943-9.542-7z" />`;
});
</script>
@endsection
