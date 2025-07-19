<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />


    <form method="POST" action="{{ route('login') }}">
        @csrf


        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full " type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Enter your email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>


        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />


            <div class="relative">
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    placeholder="Enter your password"
                >


                <!-- Tombol toggle show/hide -->
                <button type="button"
                    onclick="togglePassword()"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-indigo-500 focus:outline-none"
                >
                    <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7
                            -1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>


            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>


        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                       class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                       name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>


        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                   href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif


            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>


    <!-- JavaScript Toggle Password -->
    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');


            if (password.type === "password") {
                password.type = "text";
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943
                        -9.542-7a10.05 10.05 0 011.357-2.568M9.88 9.88a3 3 0 014.24 4.24" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />`;
            } else {
                password.type = "password";
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943
                        9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943
                        -9.542-7z" />`;
            }
        }
    </script>
</x-guest-layout>


