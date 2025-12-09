<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
        </style>
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-800" x-data="{ sidebarOpen: false }">
        <div class="min-h-screen">
            <div class="flex min-h-screen">
                <!-- Sidebar Desktop -->
                <aside class="hidden md:flex md:w-64 md:flex-col bg-white border-r border-slate-200 sticky top-0 h-screen">
                    <div class="h-16 flex items-center gap-3 px-4 border-b border-slate-200">
                        <div class="w-9 h-9 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-lg flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-indigo-500/20">A</div>
                        <span class="text-xl font-bold text-slate-900">KSARA</span>
                    </div>
                    <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
                        <x-sidebar-link href="{{ route('dashboard.admin') }}" :active="request()->routeIs('dashboard.admin')">Dashboard</x-sidebar-link>
                        <x-sidebar-link href="{{ route('penelitian.index') }}" :active="request()->routeIs('penelitian.*')">Penelitian</x-sidebar-link>
                        <x-sidebar-link href="{{ route('pengabdian.index') }}" :active="request()->routeIs('pengabdian.*')">Pengabdian Masyarakat</x-sidebar-link>
                        <x-sidebar-link href="{{ route('admin.informasi.index') }}" :active="request()->routeIs('admin.informasi.*')">Informasi/Berita</x-sidebar-link>
                        <div x-data="{ open: {{ request()->routeIs('admin.laporan.*') ? 'true' : 'false' }} }" class="relative">
                            <button @click="open = !open" type="button" class="w-full text-left flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-800 hover:bg-gray-700 hover:text-white {{ request()->routeIs('admin.laporan.*') ? 'bg-gray-700 text-white' : '' }}">
                                Laporan & Rekap
                                <svg class="ml-auto h-4 w-4" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            <div x-show="open" @click.away="open=false" class="mt-1 ml-3 space-y-1">
                                <x-sidebar-link href="{{ route('admin.laporan.index') }}" :active="request()->routeIs('admin.laporan.index')">Data Laporan</x-sidebar-link>
                                <x-sidebar-link href="{{ route('admin.laporan.perbandingan') }}" :active="request()->routeIs('admin.laporan.perbandingan')">Perbandingan</x-sidebar-link>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="pt-2">
                            @csrf
                            <button type="submit" class="w-full text-left flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-800 hover:bg-gray-700 hover:text-white">Logout</button>
                        </form>
                    </nav>
                </aside>

                <!-- Sidebar Mobile -->
                <aside 
                    x-show="sidebarOpen" 
                    @click.outside="sidebarOpen = false"
                    class="fixed inset-0 z-40 flex md:hidden bg-black/50"
                >
                    <div class="w-64 bg-white h-screen flex flex-col shadow-lg">
                        <div class="h-16 flex items-center gap-3 px-4 border-b border-slate-200">
                            <div class="w-9 h-9 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-lg flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-indigo-500/20">A</div>
                            <span class="text-xl font-bold text-slate-900">KSARA</span>
                            <button @click="sidebarOpen = false" class="ml-auto">
                                <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
                            <a href="{{ route('dashboard.admin') }}" @click="sidebarOpen = false" class="block px-3 py-2 rounded-md text-sm font-medium text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 {{ request()->routeIs('dashboard.admin') ? 'bg-indigo-50 text-indigo-600' : '' }}">Dashboard</a>
                            <a href="{{ route('penelitian.index') }}" @click="sidebarOpen = false" class="block px-3 py-2 rounded-md text-sm font-medium text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 {{ request()->routeIs('penelitian.*') ? 'bg-indigo-50 text-indigo-600' : '' }}">Penelitian</a>
                            <a href="{{ route('pengabdian.index') }}" @click="sidebarOpen = false" class="block px-3 py-2 rounded-md text-sm font-medium text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 {{ request()->routeIs('pengabdian.*') ? 'bg-indigo-50 text-indigo-600' : '' }}">Pengabdian Masyarakat</a>
                            <a href="{{ route('admin.informasi.index') }}" @click="sidebarOpen = false" class="block px-3 py-2 rounded-md text-sm font-medium text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 {{ request()->routeIs('admin.informasi.*') ? 'bg-indigo-50 text-indigo-600' : '' }}">Informasi/Berita</a>
                            <div x-data="{ open: {{ request()->routeIs('admin.laporan.*') ? 'true' : 'false' }} }" class="relative">
                                <button @click="open = !open" type="button" class="w-full text-left flex items-center px-3 py-2 rounded-md text-sm font-medium text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 {{ request()->routeIs('admin.laporan.*') ? 'bg-indigo-50 text-indigo-600' : '' }}">
                                    Laporan & Rekap
                                    <svg class="ml-auto h-4 w-4" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </button>
                                <div x-show="open" @click.away="open=false" class="mt-1 ml-3 space-y-1">
                                    <a href="{{ route('admin.laporan.index') }}" @click="sidebarOpen = false" class="block px-3 py-2 rounded-md text-sm font-medium text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 {{ request()->routeIs('admin.laporan.index') ? 'bg-indigo-50 text-indigo-600' : '' }}">Data Laporan</a>
                                    <a href="{{ route('admin.laporan.perbandingan') }}" @click="sidebarOpen = false" class="block px-3 py-2 rounded-md text-sm font-medium text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 {{ request()->routeIs('admin.laporan.perbandingan') ? 'bg-indigo-50 text-indigo-600' : '' }}">Perbandingan</a>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('logout') }}" class="pt-2">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center px-3 py-2 rounded-md text-sm font-medium text-slate-700 hover:bg-red-50 hover:text-red-600">Logout</button>
                            </form>
                        </nav>
                    </div>
                </aside>

            
                <div class="flex-1 flex flex-col min-w-0">
                    <!-- Topbar -->
                    <header class="h-16 flex items-center justify-between px-4 md:px-6 bg-white border-b border-slate-200 sticky top-0 z-30">
                        <button @click="sidebarOpen = !sidebarOpen" class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-slate-500 hover:bg-slate-100 hover:text-slate-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                            <svg class="h-6 w-6" :class="sidebarOpen ? 'hidden' : 'block'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                            <svg class="h-6 w-6" :class="sidebarOpen ? 'block' : 'hidden'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                        <div class="flex-1 hidden md:block"></div>
                        <div x-data="{ open: false }" class="relative flex items-center gap-2 md:gap-4">
                            <button @click="open = !open" class="flex items-center gap-2 md:gap-3 px-2 md:px-3 py-2 rounded-lg hover:bg-slate-100 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <div class="h-8 w-8 md:h-9 md:w-9 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-lg flex items-center justify-center text-white font-bold text-xs md:text-sm shadow-lg shadow-indigo-500/20">A</div>
                                <div class="hidden md:block text-right">
                                    <p class="text-sm font-semibold text-slate-900">{{ auth()->user()->name ?? 'Admin' }}</p>
                                    <p class="text-xs text-slate-500">Administrator</p>
                                </div>
                                <svg class="h-4 w-4 text-slate-500 hidden md:block" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div x-show="open" @click.away="open=false" class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden" style="top: 100%; margin-top: 0.5rem;">
                                <!-- Header -->
                                <div class="px-4 py-3 bg-gradient-to-r from-indigo-50 to-slate-50 border-b border-slate-200">
                                    <p class="text-sm font-semibold text-slate-900">{{ auth()->user()->name ?? 'Admin' }}</p>
                                    <p class="text-xs text-slate-500">{{ auth()->user()->email ?? 'admin@example.com' }}</p>
                                </div>
                                
                                <!-- Menu Items -->
                                <div class="py-2">
                                    <a href="{{ route('profile.admin.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        <span>Lihat Profile</span>
                                    </a>
                                    <a href="{{ route('profile.admin.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                        <span>Ganti Password</span>
                                    </a>
                                </div>
                                
                                <!-- Divider -->
                                <div class="border-t border-slate-200"></div>
                                
                                <!-- Logout -->
                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </header>

                    <!-- Content -->
                    <main class="flex-1 p-3 md:p-4 lg:p-8 overflow-y-auto">
                        {{ $slot }}
                    </main>
                    
                    {{-- ======================== FOOTER ======================== --}}
                    <footer class="bg-slate-50 pt-16 pb-8 border-t border-slate-200">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
                                <div class="col-span-1 lg:col-span-1">
                                    <a href="#" class="flex items-center gap-2 mb-4">
                                        <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-bold">A</div>
                                        <span class="text-xl font-bold text-slate-900">KSARA</span>
                                    </a>
                                    <p class="text-slate-500 text-sm leading-relaxed mb-4">
                                        Pusat inovasi dan pengabdian yang berdedikasi untuk memajukan ilmu pengetahuan demi kesejahteraan masyarakat.
                                    </p>
                                    <div class="flex space-x-4">
                                        <a href="#" class="text-slate-400 hover:text-indigo-600 transition-colors"><span class="sr-only">Facebook</span><svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"/></svg></a>
                                        <a href="#" class="text-slate-400 hover:text-indigo-600 transition-colors"><span class="sr-only">Twitter</span><svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/></svg></a>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="font-bold text-slate-900 mb-4">Navigasi</h4>
                                    <ul class="space-y-2 text-sm text-slate-600">
                                        <li><a href="#" class="hover:text-indigo-600 transition-colors">Beranda</a></li>
                                        <li><a href="#" class="hover:text-indigo-600 transition-colors">Visi Misi</a></li>
                                        <li><a href="#" class="hover:text-indigo-600 transition-colors">Layanan Kami</a></li>
                                        <li><a href="#" class="hover:text-indigo-600 transition-colors">Karir</a></li>
                                    </ul>
                                </div>
                            
                                <div>
                                    <h4 class="font-bold text-slate-900 mb-4">Legal</h4>
                                    <ul class="space-y-2 text-sm text-slate-600">
                                        <li><a href="#" class="hover:text-indigo-600 transition-colors">Kebijakan Privasi</a></li>
                                        <li><a href="#" class="hover:text-indigo-600 transition-colors">Syarat & Ketentuan</a></li>
                                        <li><a href="#" class="hover:text-indigo-600 transition-colors">Hak Cipta</a></li>
                                    </ul>
                                </div>
                            
                                <div>
                                    <h4 class="font-bold text-slate-900 mb-4">Kontak</h4>
                                    <ul class="space-y-2 text-sm text-slate-600">
                                        <li class="flex items-start gap-2">
                                            <svg class="w-5 h-5 text-indigo-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            <span>Gedung Rektorat Lt. 3,<br>Universitas Teknologi</span>
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                            <span>halo@aksara.ac.id</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="border-t border-slate-200 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                                <p class="text-sm text-slate-500 text-center md:text-left">&copy; {{ date('Y') }} AKSA-RA. All rights reserved.</p>
                            </div>
                        </div>
                    </footer>
                </div>
            </div>
        </div>
    </body>
</html>


