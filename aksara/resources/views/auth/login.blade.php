<x-guest-layout>
    <div class="flex min-h-screen bg-white font-sans">
        
        <div class="w-full lg:w-1/2 flex flex-col justify-center px-8 md:px-16 lg:px-24 py-12 relative">
            
            <div class="mb-10">
                <div class="flex items-center gap-2 text-blue-900">
                    <div class="bg-blue-900 text-white p-2 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                    <span class="text-2xl font-bold tracking-tight">AKSARA</span>
                </div>
                <h2 class="mt-6 text-3xl font-bold text-gray-900">Selamat Datang</h2>
                <p class="mt-2 text-sm text-gray-600">Silakan masuk ke akun Anda untuk melanjutkan.</p>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <div class="bg-gray-100 p-1.5 rounded-xl flex justify-between mb-8" id="roleToggle">
                <button type="button" data-role="dosen" class="w-1/2 py-2.5 text-sm font-bold text-white bg-blue-700 rounded-lg shadow-sm transition-all duration-300" aria-pressed="true">
                    Dosen
                </button>
                <button type="button" data-role="admin" class="w-1/2 py-2.5 text-sm font-bold text-gray-500 hover:text-blue-700 transition-all duration-300" aria-pressed="false">
                    Admin
                </button>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="intended_role" id="intended_role" value="dosen">

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input id="email" class="block w-full px-4 py-3 rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50" 
                           type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@email.com" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input id="password" class="block w-full px-4 py-3 rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50"
                           type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-700 shadow-sm focus:ring-blue-500" name="remember">
                        <span class="ms-2 text-sm text-gray-600">{{ __('Ingat saya') }}</span>
                    </label>
                    
                    @if (Route::has('password.request'))
                        <a class="text-sm font-medium text-blue-700 hover:text-blue-900 hover:underline" href="{{ route('password.request') }}">
                            {{ __('Lupa password?') }}
                        </a>
                    @endif
                </div>

                <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-gradient-to-r from-blue-700 to-blue-600 hover:from-blue-800 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform hover:scale-[1.01]">
                    {{ __('Sign in') }}
                </button>

                <div class="text-center mt-6">
                    <p class="text-sm text-gray-600">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="font-bold text-amber-500 hover:text-amber-600 hover:underline">Daftar sekarang</a>
                    </p>
                </div>
            </form>
        </div>

        <div class="hidden lg:flex w-1/2 bg-gradient-to-br from-[#1e3a8a] via-[#2563eb] to-[#60a5fa] relative overflow-hidden items-center justify-center text-white p-12">
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 rounded-full bg-blue-400 opacity-20 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 rounded-full bg-blue-900 opacity-30 blur-3xl"></div>
            
            <div class="relative z-10 max-w-lg">
                <div class="mb-6">
                    <span class="bg-blue-800 bg-opacity-50 text-blue-100 text-xs font-semibold px-3 py-1 rounded-full uppercase tracking-wide border border-blue-400">Pusat Inovasi</span>
                </div>
                <h1 class="text-5xl font-bold leading-tight mb-6">Mewujudkan Solusi Inovatif.</h1>
                <p class="text-lg text-blue-100 leading-relaxed mb-8">
                    Bergabunglah bersama kami untuk memajukan komunitas melalui penelitian dan pengabdian yang berdampak nyata.
                </p>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white/10 backdrop-blur-md p-4 rounded-xl border border-white/20">
                        <div class="text-3xl font-bold">120+</div>
                        <div class="text-sm text-blue-100">Penelitian Aktif</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md p-4 rounded-xl border border-white/20">
                        <div class="text-3xl font-bold">3000+</div>
                        <div class="text-sm text-blue-100">Penerima Manfaat</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

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
                        // Active Styles (Blue Button)
                        el.classList.add('text-white', 'bg-blue-700', 'shadow-sm');
                        el.classList.remove('text-gray-500', 'hover:text-blue-700');
                    }else{
                        // Inactive Styles (Gray Text)
                        el.classList.add('text-gray-500', 'hover:text-blue-700');
                        el.classList.remove('text-white', 'bg-blue-700', 'shadow-sm');
                    }
                });
            });
        })();
    </script>
</x-guest-layout>