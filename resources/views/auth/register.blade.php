<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <title>@yield('title') - LMS</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
<div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="flex justify-center items-center -mt-5">
        <img src="/images/logo.png" alt="Logo" class="w-16 h-16">
    </div>
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="mt-3 text-center text-3xl font-extrabold text-[#009999]">
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
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Password
                    </label>
                    <div class="mt-1 relative">
                        <input id="password" name="password" type="password" required autocomplete="new-password"
                            class="appearance-none block w-full px-3 py-2 border rounded-md shadow-sm placeholder-gray-400
                            focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm
                            @error('password') border-red-500 @else border-gray-300 @enderror">
                            <button type="button" id="togglePassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5 text-gray-500">
                                <i id="eyeIcon" class="fa-solid fa-eye"></i>
                            </button>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                        Confirm Password
                    </label>
                    <div class="mt-1 relative">
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm
                            placeholder-gray-400 focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                            <button type="button" id="toggleConfirmPassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5 text-gray-500">
                                <i id="eyeIconConfirm" class="fa-solid fa-eye"></i>
                            </button>
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
</body>
</html>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const toggles = [
        { button: 'togglePassword', input: 'password', icon: 'eyeIcon' },
        { button: 'toggleConfirmPassword', input: 'password_confirmation', icon: 'eyeIconConfirm' }
    ];

    toggles.forEach(({ button, input, icon }) => {
        const btn = document.getElementById(button);
        const field = document.getElementById(input);
        const eye = document.getElementById(icon);

        if (btn && field && eye) {
            btn.addEventListener('click', function () {
                const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
                field.setAttribute('type', type);
                eye.classList.toggle('fa-eye');
                eye.classList.toggle('fa-eye-slash');
            });
        }
    });
});
</script>

