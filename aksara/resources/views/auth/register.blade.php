<x-guest-layout>
    <div class="flex flex-col lg:flex-row min-h-screen bg-white font-sans overflow-hidden">

        <div class="w-full lg:w-1/2 flex flex-col justify-center px-8 md:px-16 lg:px-24 py-12 relative z-10 overflow-y-auto lg:overflow-y-visible">
            <div class="mb-8">
                <div class="flex items-center gap-2 text-blue-900 mb-4">
                    <div class="bg-blue-900 text-white p-2 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                    <span class="text-2xl font-bold tracking-tight">AKSARA</span>
                </div>
                <h2 class="text-3xl font-bold text-gray-900">Buat Akun Baru</h2>
                <p class="mt-2 text-sm text-gray-600">Daftar sebagai anggota komunitas kami.</p>
            </div>
    <form method="POST" action="{{ route('register') }}">
        @csrf

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

        <!-- NIP -->
        <div class="mt-4">
            <x-input-label for="nip" value="NIP" />
            <x-text-input id="nip" class="block mt-1 w-full" type="text" name="nip" :value="old('nip')" required />
            <x-input-error :messages="$errors->get('nip')" class="mt-2" />
        </div>

        <!-- Affiliation & Citizenship inline -->
        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="affiliation" value="Affiliation / Institution" />
                <x-text-input id="affiliation" class="block mt-1 w-full" type="text" name="affiliation" :value="old('affiliation')" required />
                <x-input-error :messages="$errors->get('affiliation')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="citizenship" value="Citizenship" />
                <x-text-input id="citizenship" class="block mt-1 w-full" type="text" name="citizenship" :value="old('citizenship')" required />
                <x-input-error :messages="$errors->get('citizenship')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4 btn-theme">
                {{ __('Register') }}
            </x-primary-button>
        </div>
        </div>
    </form>
        
        <div class="hidden lg:flex lg:w-1/2 panel-gradient relative overflow-hidden items-center justify-center text-white p-12 min-h-screen z-0">
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 rounded-full bg-blue-400 opacity-20 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 rounded-full bg-blue-900 opacity-30 blur-3xl"></div>

            <div class="relative z-10 max-w-lg">
                <div class="mb-6">
                    <span class="bg-blue-800 bg-opacity-50 text-blue-100 text-xs font-semibold px-3 py-1 rounded-full uppercase tracking-wide border border-blue-400">Bergabunglah</span>
                </div>
                <h1 class="text-5xl font-bold leading-tight mb-6">Mulai Perjalanan Anda.</h1>
                <p class="text-lg text-blue-100 leading-relaxed mb-8">
                    Daftar sekarang dan jadilah bagian dari komunitas peneliti dan praktisi kami yang inovatif.
                </p>

                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white/10 backdrop-blur-md p-4 rounded-xl border border-white/20">
                        <div class="text-3xl font-bold">1000+</div>
                        <div class="text-sm text-blue-100">Anggota Aktif</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md p-4 rounded-xl border border-white/20">
                        <div class="text-3xl font-bold">50+</div>
                        <div class="text-sm text-blue-100">Proyek Berjalan</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    
</x-guest-layout>
