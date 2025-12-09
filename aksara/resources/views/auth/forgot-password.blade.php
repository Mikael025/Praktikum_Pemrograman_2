<x-guest-layout>
    <div class="flex flex-col lg:flex-row min-h-screen bg-white font-sans overflow-hidden">

        <div class="w-full lg:w-1/2 flex flex-col justify-center px-8 md:px-16 lg:px-24 py-12 relative z-10 overflow-y-auto lg:overflow-y-visible">

            <div class="mb-10">
                <div class="flex items-center gap-2 text-blue-900">
                    <div class="bg-blue-900 text-white p-2 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                    <span class="text-2xl font-bold tracking-tight">AKSARA</span>
                </div>
                <h2 class="mt-6 text-3xl font-bold text-gray-900">Lupa Password?</h2>
                <p class="mt-2 text-sm text-gray-600">Tidak masalah. Beri tahu kami email Anda dan kami akan mengirimkan link reset password.</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full border border-gray-300" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <x-primary-button class="btn-theme">
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
            <a href="{{ route('login') }}" class="text-sm" style="color:var(--primary-600);">
                {{ __('Back to Sign in') }}
            </a>
        </div>
            </form>

            <div class="mt-8 text-center">
                <p class="text-sm text-gray-600">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-bold text-blue-700 hover:text-blue-900 hover:underline">Daftar sekarang</a>
                </p>
            </div>
        </div>

        <div class="hidden lg:flex lg:w-1/2 panel-gradient relative overflow-hidden items-center justify-center text-white p-12 min-h-screen z-0">
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 rounded-full bg-blue-400 opacity-20 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 rounded-full bg-blue-900 opacity-30 blur-3xl"></div>

            <div class="relative z-10 max-w-lg">
                <div class="mb-6">
                    <span class="bg-blue-800 bg-opacity-50 text-blue-100 text-xs font-semibold px-3 py-1 rounded-full uppercase tracking-wide border border-blue-400">Keamanan Akun</span>
                </div>
                <h1 class="text-5xl font-bold leading-tight mb-6">Pulihkan Akses Anda.</h1>
                <p class="text-lg text-blue-100 leading-relaxed mb-8">
                    Kami akan membantu Anda membuat password baru dengan aman dan cepat.
                </p>

                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white/10 backdrop-blur-md p-4 rounded-xl border border-white/20">
                        <div class="text-3xl font-bold">99%</div>
                        <div class="text-sm text-blue-100">Tingkat Keberhasilan</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md p-4 rounded-xl border border-white/20">
                        <div class="text-3xl font-bold">&lt;5m</div>
                        <div class="text-sm text-blue-100">Waktu Reset</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-guest-layout>
