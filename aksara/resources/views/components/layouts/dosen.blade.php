<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <div class="flex min-h-screen">
                <!-- Sidebar -->
                <aside class="hidden md:flex md:w-64 md:flex-col bg-gray-900 text-gray-100">
                    <div class="h-16 flex items-center gap-3 px-4 border-b border-gray-800">
                        <img src="{{ asset('images/logoAksara.png') }}" alt="Logo" class="h-8 w-8">
                        <span class="text-lg font-semibold">Aksara</span>
                    </div>
                    <nav class="flex-1 px-2 py-4 space-y-1">
                        <x-sidebar-link href="{{ route('dashboard.dosen') }}" :active="request()->routeIs('dashboard.dosen')">Dashboard</x-sidebar-link>
                        <x-sidebar-link href="{{ route('dosen.penelitian.index') }}" :active="request()->routeIs('dosen.penelitian.*')">Penelitian</x-sidebar-link>
                        <x-sidebar-link href="{{ route('dosen.pengabdian.index') }}" :active="request()->routeIs('dosen.pengabdian.*')">Pengabdian Masyarakat</x-sidebar-link>
                        <div class="pt-2 mt-2 border-t border-gray-800"></div>
                        <x-sidebar-link href="{{ route('dosen.informasi') }}" :active="request()->routeIs('dosen.informasi')">Informasi/Berita</x-sidebar-link>
                        <form method="POST" action="{{ route('logout') }}" class="pt-2">
                            @csrf
                            <button type="submit" class="w-full text-left flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Logout</button>
                        </form>
                    </nav>
                </aside>

                <!-- Main -->
                <div class="flex-1 flex flex-col min-w-0">
                    <!-- Topbar -->
                    <header class="h-16 flex items-center justify-between px-4 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-10">
                        <div class="flex items-center gap-2 md:hidden">
                            <!-- Placeholder for mobile menu (future) -->
                            <span class="text-gray-500">☰</span>
                        </div>
                        <div class="flex-1"></div>
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('images/logoAksara.png') }}" alt="Logo" class="h-8 w-8">
                            <span class="text-sm md:text-base font-medium text-gray-900 dark:text-gray-100">{{ auth()->user()->name ?? 'Dosen' }}</span>
                        </div>
                    </header>

                    <!-- Content -->
                    <main class="p-4 md:p-6 lg:p-8">
                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>
