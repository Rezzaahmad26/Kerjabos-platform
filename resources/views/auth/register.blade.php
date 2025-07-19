<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
       {{-- Occupation --}}
        <div class="mt-4">
            <x-input-label for="occupation" :value="__('Occupations')" />
                <select id="occupation" name="occupation[]" multiple
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="Frontend Developer">Frontend Developer</option>
                        <option value="Backend Developer">Backend Developer</option>
                        <option value="UI/UX Designer">UI/UX Designer</option>
                        <option value="Graphic Designer">Graphic Designer</option>
                        <option value="Mobile Developer">Mobile Developer</option>
                        <option value="DevOps">DevOps</option>
                        <option value="Fullstack Developer">Fullstack Developer</option>
                </select>
            <x-input-error :messages="$errors->get('occupation')" class="mt-2" />
        </div>


        <div class="mt-4">
            <x-input-label for="avatar" :value="__('Avatar')" />
            <x-text-input id="avatar" class="block mt-1 w-full" type="file" name="avatar" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('avatar')" class="mt-2" />
        </div>

        {{-- pemilihan Role --}}
        <div class="mt-4">
            <x-input-label for="role" :value="__('role')" />
            <select name="role" id="role_id" class="py-3 rounded-lg  pl-3 w-full border border-slate-300">
                <option value="">Chosee Role</option>
                <option value="project_freelancer">Freelancer</option>
                <option value="project_client">Client</option>
            </select>

            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>



        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>



    </form>
        {{-- <script>
            document.addEventListener('DOMContentLoaded', function () {
                const occupationSelect = document.getElementById('occupation');
                if (occupationSelect) {
                    new Choices(occupationSelect, {
                        removeItemButton: true,
                        placeholder: true,
                        placeholderValue: 'Pilih Occupation...',
                        searchPlaceholderValue: 'Cari...',
                    });
                }
            });
        </script> --}}
</x-guest-layout>
