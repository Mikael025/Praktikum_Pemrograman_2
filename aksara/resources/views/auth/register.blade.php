<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <input type="hidden" name="role" id="register_role" value="dosen">

        <!-- Role Toggle -->
        <div class="bg-gray-200 p-1 rounded-full flex justify-between mt-4 mb-3" id="registerRoleToggle">
            <button type="button" data-role="dosen" class="w-1/2 py-2 text-sm font-semibold text-white bg-gray-500 rounded-full shadow" aria-pressed="true">
                Dosen
            </button>
            <button type="button" data-role="admin" class="w-1/2 py-2 text-sm font-semibold text-gray-500" aria-pressed="false">
                Admin
            </button>
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
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
    <script>
        (function(){
            const toggle = document.getElementById('registerRoleToggle');
            const hidden = document.getElementById('register_role');
            if(!toggle || !hidden) return;
            toggle.addEventListener('click', function(e){
                const btn = e.target.closest('button[data-role]');
                if(!btn) return;
                const role = btn.getAttribute('data-role');
                hidden.value = role;
                [...toggle.querySelectorAll('button[data-role]')].forEach(el => {
                    const active = el.getAttribute('data-role') === role;
                    el.setAttribute('aria-pressed', active ? 'true' : 'false');
                    if(active){
                        el.classList.add('text-white','bg-gray-500','rounded-full','shadow');
                        el.classList.remove('text-gray-500','bg-transparent');
                    }else{
                        el.classList.add('text-gray-500');
                        el.classList.remove('text-white','bg-gray-500');
                    }
                });
            });
        })();
    </script>
</x-guest-layout>
