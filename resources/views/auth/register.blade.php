@extends('layout.navbar')

@section('title', 'Register')

@section('content')
<div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Create a new account
        </h2>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <form class="space-y-6" action="{{ route('register') }}" method="POST">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">
                        Full Name
                    </label>
                    <div class="mt-1">
                        <input id="name" name="name" type="text" required autocomplete="name" value="{{ old('name') }}"
                            class="appearance-none block w-full px-3 py-2 border rounded-md shadow-sm placeholder-gray-400
                            focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm
                            @error('name') border-red-500 @else border-gray-300 @enderror">
                    </div>
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Email address
                    </label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" required autocomplete="email" value="{{ old('email') }}"
                            class="appearance-none block w-full px-3 py-2 border rounded-md shadow-sm placeholder-gray-400
                            focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm
                            @error('email') border-red-500 @else border-gray-300 @enderror">
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Password
                    </label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" required autocomplete="new-password"
                            class="appearance-none block w-full px-3 py-2 border rounded-md shadow-sm placeholder-gray-400
                            focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm
                            @error('password') border-red-500 @else border-gray-300 @enderror">
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                        Confirm Password
                    </label>
                    <div class="mt-1">
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
                            placeholder-gray-400 focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                    </div>
                </div>

                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm
                        font-medium text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2
                        focus:ring-offset-2 focus:ring-teal-500">
                        Register
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-medium text-teal-600 hover:text-teal-500">
                        Sign in
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
