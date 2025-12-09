<x-layouts.dosen>
    <div class="space-y-6">
        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm">
            <a href="{{ route('dashboard.dosen') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Dosen</a>
            <span class="text-slate-400">/</span>
            <span class="text-slate-600">Profil</span>
        </nav>

        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900">{{ __('Profile') }}</h1>
            <p class="mt-2 text-slate-600">{{ __('Kelola informasi profil dan pengaturan akun Anda') }}</p>
        </div>

        <!-- Profile Information Card -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 sm:p-8">
                <div class="max-w-2xl">
                    @include('profile.dosen.partials.update-profile-information-form')
                </div>
            </div>
        </div>

        <!-- Update Password Card -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 sm:p-8">
                <div class="max-w-2xl">
                    @include('profile.dosen.partials.update-password-form')
                </div>
            </div>
        </div>

        <!-- Delete Account Card -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 sm:p-8">
                <div class="max-w-2xl">
                    @include('profile.dosen.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-layouts.dosen>
