<x-app-layout>
    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-slate-900">{{ __('Profile') }}</h1>
                <p class="mt-2 text-slate-600">{{ __('Kelola informasi profil dan pengaturan akun Anda') }}</p>
            </div>

            <!-- Profile Information Card -->
            <div class="mb-6 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 sm:p-8">
                    <div class="max-w-2xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            <!-- Update Password Card -->
            <div class="mb-6 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 sm:p-8">
                    <div class="max-w-2xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            <!-- Delete Account Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 sm:p-8">
                    <div class="max-w-2xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
