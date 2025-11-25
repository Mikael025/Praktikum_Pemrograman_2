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
        <div class="min-h-screen bg-gray-100">
            <div class="flex min-h-screen">
                <!-- Sidebar -->
                <aside class="hidden md:flex md:w-64 md:flex-col bg-white text-gray-800">
                    <div class="h-16 flex items-center gap-3 px-4 border-b">
                        <img src="{{ asset('images/logoAksara.png') }}" alt="Logo" class="h-8 w-8">
                        <span class="text-lg font-semibold">Aksara</span>
                    </div>
                    <nav class="flex-1 px-2 py-4 space-y-1">
                        <x-sidebar-link href="{{ route('dashboard.dosen') }}" :active="request()->routeIs('dashboard.dosen')">Dashboard</x-sidebar-link>
                        <x-sidebar-link href="{{ route('dosen.penelitian.index') }}" :active="request()->routeIs('dosen.penelitian.*')">Penelitian</x-sidebar-link>
                        <x-sidebar-link href="{{ route('dosen.pengabdian.index') }}" :active="request()->routeIs('dosen.pengabdian.*')">Pengabdian Masyarakat</x-sidebar-link>
                        <div x-data="{ open: {{ request()->routeIs('dosen.laporan.*') ? 'true' : 'false' }} }" class="relative">
                            <button @click="open = !open" type="button" class="w-full text-left flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-800 hover:bg-gray-700 hover:text-white {{ request()->routeIs('dosen.laporan.*') ? 'bg-gray-700 text-white' : '' }}">
                                Laporan & Rekap
                                <svg class="ml-auto h-4 w-4" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            <div x-show="open" @click.away="open=false" class="mt-1 ml-3 space-y-1">
                                <x-sidebar-link href="{{ route('dosen.laporan.index') }}" :active="request()->routeIs('dosen.laporan.index')">Data Laporan</x-sidebar-link>
                                <x-sidebar-link href="{{ route('dosen.laporan.perbandingan') }}" :active="request()->routeIs('dosen.laporan.perbandingan')">Perbandingan</x-sidebar-link>
                            </div>
                        </div>
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" type="button" class="w-full text-left flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-800 hover:bg-gray-700 hover:text-white">
                                Informasi/Berita
                                <svg class="ml-auto h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            <div x-show="open" @click.away="open=false" class="mt-1 ml-3 space-y-1">
                                <x-sidebar-link href="{{ route('dosen.informasi', ['k' => 'penelitian']) }}" :active="request()->fullUrlIs(route('dosen.informasi', ['k' => 'penelitian']))">Informasi Penelitian</x-sidebar-link>
                                <x-sidebar-link href="{{ route('dosen.informasi', ['k' => 'pengabdian']) }}" :active="request()->fullUrlIs(route('dosen.informasi', ['k' => 'pengabdian']))">Informasi Pengabdian Masyarakat</x-sidebar-link>
                                <x-sidebar-link href="{{ route('dosen.informasi', ['k' => 'umum']) }}" :active="request()->fullUrlIs(route('dosen.informasi', ['k' => 'umum']))">Informasi Umum</x-sidebar-link>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="pt-2">
                            @csrf
                            <button type="submit" class="w-full text-left flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-800 hover:bg-gray-700 hover:text-white">Logout</button>
                        </form>
                    </nav>
                </aside>

                <!-- Main -->
                <div class="flex-1 flex flex-col min-w-0">
                    <!-- Topbar -->
                    <header class="h-16 flex items-center justify-between px-4 bg-white border-b border-gray-200 sticky top-0 z-10">
                        <div class="flex items-center gap-2 md:hidden">
                            <!-- Placeholder for mobile menu (future) -->
                            <span class="text-gray-500">☰</span>
                        </div>
                        <div class="flex-1"></div>
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('images/logoAksara.png') }}" alt="Logo" class="h-8 w-8">
                            <span class="text-sm md:text-base font-medium text-gray-900">{{ auth()->user()->name ?? 'Dosen' }}</span>
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
