<x-guest-layout>
    
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Role Toggle -->
    <div class="bg-gray-200 p-1 rounded-full flex justify-between mt-4 mb-3" id="roleToggle">
        <button type="button" data-role="dosen" class="w-1/2 py-2 text-sm font-semibold text-white bg-gray-500 rounded-full shadow" aria-pressed="true">
            Dosen
        </button>
        <button type="button" data-role="admin" class="w-1/2 py-2 text-sm font-semibold text-gray-500" aria-pressed="false">
            Admin
        </button>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <input type="hidden" name="intended_role" id="intended_role" value="dosen">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>


        <!-- Remember Me & Register -->
        <div class="flex items-center justify-between mt-4">
            <div class="flex items-center">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>
            
            <p class="text-sm text-gray-600">
                Don't have an account?
                <a href="{{route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500 hover:underline">Register here</a>
            </p>
        </div>

        <!-- Forgot Password & Login Button -->
        <div class="flex items-center justify-between mt-4">
            @if (Route::has('password.request'))
                <a class="text-sm text-indigo-600 hover:text-indigo-500 hover:underline focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 rounded-md" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @else
                <div></div>
            @endif

            <x-primary-button>
                {{ __('Sign in') }}
            </x-primary-button>
        </div>
    </form>
    <script>
        (function(){
            const toggle = document.getElementById('roleToggle');
            const hidden = document.getElementById('intended_role');
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
