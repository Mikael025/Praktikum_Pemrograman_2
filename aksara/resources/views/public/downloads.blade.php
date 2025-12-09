<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Unduh Laporan - {{ config('app.name', 'AKSA-RA') }}</title>

        {{-- Fonts --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        {{-- Scripts & Tailwind --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.tailwindcss.com"></script>

        <style>
            /* Typography Modern */
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            
            /* Custom CSS Variables */
            :root {
                --primary: #4338ca; /* Indigo 700 */
                --secondary: #0f172a; /* Slate 900 */
                --accent: #f59e0b; /* Amber 500 */
                /* Blue theme for hero subtitle gradient */
                --blue-dark: #0b3b75; /* navy/dark blue */
                --blue-light: #60a5fa; /* light blue (Tailwind blue-400) */
            }

            /* Glassmorphism Classes */
            .glass {
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            }

            /* Mesh Gradient Background for Hero */
            .bg-mesh {
                background-color: #f8fafc;
                background-image: 
                    radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%), 
                    radial-gradient(at 50% 0%, hsla(225,39%,30%,1) 0, transparent 50%), 
                    radial-gradient(at 100% 0%, hsla(339,49%,30%,1) 0, transparent 50%);
            }
        </style>
    </head>
    <body class="bg-slate-50 text-slate-800 antialiased selection:bg-indigo-500 selection:text-white">

        {{-- ======================== HEADER ======================== --}}
        <header id="navbar" class="fixed w-full z-50 transition-all duration-300 top-0 text-white/90">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    {{-- Logo --}}
                    <a href="{{ route('public.home') }}" class="flex items-center gap-2 group">
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-indigo-500/20 group-hover:rotate-12 transition-transform duration-300">A</div>
                        <span class="text-2xl font-bold tracking-tight text-white transition-colors duration-300" id="logo-text">KSARA</span>
                    </a>

                    {{-- Desktop Nav --}}
                    <nav class="hidden md:flex space-x-8 items-center">
                        <a href="{{ route('public.home') }}" class="hover:text-amber-400 font-medium transition-colors">Beranda</a>
                        <a href="{{ route('public.visimisi') }}" class="hover:text-amber-400 font-medium transition-colors">Visi & Misi</a>
                        <a href="{{ route('public.news', ['category' => 'semua']) }}" class="hover:text-amber-400 font-medium transition-colors">Informasi/Berita</a>
                        <a href="{{ route('public.downloads') }}" class="text-amber-400 font-bold">Unduh</a>
                    </nav>

                    {{-- Auth & Mobile Toggle --}}
                    <div class="flex items-center gap-4">
                        <a href="{{ route('login') }}" id="btn-login" class="hidden md:inline-flex px-6 py-2.5 rounded-full border border-white/20 bg-white/10 text-white text-sm font-semibold hover:bg-white hover:text-indigo-900 backdrop-blur-sm transition-all duration-300">
                            Masuk Portal
                        </a>
                        <button id="mobile-toggle" class="md:hidden hover:text-amber-400 transition-colors">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Mobile Menu --}}
            <div id="mobile-menu" class="fixed inset-0 bg-slate-900/95 z-40 transform translate-x-full transition-transform duration-300 md:hidden flex flex-col justify-center items-center space-y-8 text-white">
                <button id="close-menu" class="absolute top-6 right-6 p-2">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                <a href="{{ route('public.home') }}" class="text-2xl font-bold hover:text-amber-400">Beranda</a>
                <a href="{{ route('public.visimisi') }}" class="text-2xl font-bold hover:text-amber-400">Visi & Misi</a>
                <a href="{{ route('public.news', ['category' => 'semua']) }}" class="text-2xl font-bold hover:text-amber-400">Berita</a>
                <a href="{{ route('public.downloads') }}" class="text-2xl font-bold text-amber-400">Unduh</a>
                <a href="{{ route('login') }}" class="px-8 py-3 bg-indigo-600 rounded-full font-bold shadow-lg shadow-indigo-500/50">Masuk Portal</a>
            </div>
        </header>

        <main>
        <main>
            {{-- HERO SECTION --}}
            <section class="relative pt-32 pb-20 lg:pt-40 lg:pb-24 overflow-hidden bg-mesh text-white rounded-b-[2rem]">
                <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 mix-blend-soft-light"></div>
                <div class="max-w-5xl mx-auto px-4 relative z-10 text-center">
                    <h1 class="text-4xl md:text-6xl font-extrabold leading-tight mb-4 tracking-tight">
                        Unduh Laporan
                    </h1>
                    <p class="text-lg text-[#4f46e5] max-w-3xl mx-auto font-light">
                        Akses laporan akhir dari penelitian dan pengabdian masyarakat yang telah diselesaikan
                    </p>
                </div>
            </section>

            {{-- DOWNLOADS CONTENT --}}
            <section class="py-24 bg-slate-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {{-- Stats --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm text-center">
                            <p class="text-4xl font-bold text-indigo-600 mb-2">{{ $totalDocuments }}</p>
                            <p class="text-slate-600">Total Laporan</p>
                        </div>
                        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm text-center">
                            <p class="text-4xl font-bold text-blue-600 mb-2">{{ $penelitianCount }}</p>
                            <p class="text-slate-600">Penelitian</p>
                        </div>
                        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm text-center">
                            <p class="text-4xl font-bold text-green-600 mb-2">{{ $pengabdianCount }}</p>
                            <p class="text-slate-600">Pengabdian</p>
                        </div>
                    </div>

                    {{-- Filters --}}
                    <div class="bg-white rounded-xl shadow-sm p-8 border border-slate-200 mb-12">
                        <h3 class="text-xl font-bold text-slate-900 mb-6">Filter Laporan</h3>
                        <form method="GET" action="{{ route('public.downloads') }}" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                {{-- Category --}}
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Kategori</label>
                                    <select name="category" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white">
                                        <option value="">Semua Kategori</option>
                                        <option value="penelitian" {{ request('category') == 'penelitian' ? 'selected' : '' }}>Penelitian</option>
                                        <option value="pengabdian" {{ request('category') == 'pengabdian' ? 'selected' : '' }}>Pengabdian</option>
                                    </select>
                                </div>

                                {{-- Year --}}
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Tahun</label>
                                    <select name="year" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white">
                                        <option value="">Semua Tahun</option>
                                        @for ($year = now()->year; $year >= 2020; $year--)
                                            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                        @endfor
                                    </select>
                                </div>

                                {{-- Search --}}
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Cari Judul</label>
                                    <div class="flex gap-2">
                                        <input type="text" name="search" placeholder="Cari judul..." value="{{ request('search') }}" class="flex-1 px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">Cari</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- Documents --}}
                    @if($documents->isEmpty())
                        <div class="bg-white rounded-xl shadow-sm p-12 text-center border border-slate-200">
                            <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-slate-600 text-lg font-medium">Belum ada laporan dengan filter yang dipilih</p>
                        </div>
                    @else
                        {{-- Desktop Table --}}
                        <div class="hidden md:block bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-slate-50 border-b border-slate-200">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Jenis</th>
                                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Judul</th>
                                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Dosen</th>
                                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Tahun</th>
                                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">File</th>
                                            <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200">
                                        @foreach($documents as $doc)
                                            <tr class="hover:bg-slate-50 transition">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-3 py-1 text-xs font-bold rounded-full {{ $doc->penelitian ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                        {{ $doc->penelitian ? 'Penelitian' : 'Pengabdian' }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="text-sm font-semibold text-slate-900">{{ $doc->penelitian?->judul ?? $doc->pengabdian?->judul }}</div>
                                                    <div class="text-xs text-slate-500">{{ $doc->created_at->format('d M Y') }}</div>
                                                </td>
                                                <td class="px-6 py-4 text-sm text-slate-700">{{ $doc->penelitian?->user?->name ?? $doc->pengabdian?->user?->name ?? '-' }}</td>
                                                <td class="px-6 py-4 text-sm text-slate-700">{{ $doc->penelitian?->tahun ?? $doc->pengabdian?->tahun ?? '-' }}</td>
                                                <td class="px-6 py-4">
                                                    <div class="text-sm text-slate-700">{{ $doc->file_size ? number_format($doc->file_size / 1024 / 1024, 2) . ' MB' : '-' }}</div>
                                                    <div class="text-xs text-slate-500">{{ pathinfo($doc->file_path, PATHINFO_EXTENSION) ? strtoupper(pathinfo($doc->file_path, PATHINFO_EXTENSION)) : 'PDF' }}</div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <a href="{{ route('public.download.document', ['type' => ($doc->penelitian ? 'penelitian' : 'pengabdian'), 'id' => $doc->id]) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                        </svg>
                                                        Unduh
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Mobile Cards --}}
                        <div class="md:hidden space-y-4">
                            @foreach($documents as $doc)
                                @include('components.document-card', [
                                    'doc' => $doc,
                                    'type' => $doc->penelitian ? 'penelitian' : 'pengabdian'
                                ])
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-8">
                            {{ $documents->links() }}
                        </div>
                    @endif
                </div>
            </section>
        </main>

        {{-- ======================== FOOTER ======================== --}}
        <footer class="bg-slate-900 text-slate-300 pt-16 pb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
                    <div class="col-span-1 lg:col-span-1">
                        <a href="#" class="flex items-center gap-2 mb-4">
                            <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-bold">A</div>
                            <span class="text-xl font-bold text-white">KSARA</span>
                        </a>
                        <p class="text-slate-400 text-sm leading-relaxed mb-4">
                            Pusat inovasi dan pengabdian yang berdedikasi untuk memajukan ilmu pengetahuan demi kesejahteraan masyarakat.
                        </p>
                    </div>
                    <div>
                        <h4 class="font-bold text-white mb-4">Navigasi</h4>
                        <ul class="space-y-3 text-sm">
                            <li><a href="{{ route('public.home') }}" class="hover:text-amber-400 transition-colors">Beranda</a></li>
                            <li><a href="{{ route('public.visimisi') }}" class="hover:text-amber-400 transition-colors">Visi Misi</a></li>
                            <li><a href="{{ route('public.news', ['category' => 'semua']) }}" class="hover:text-amber-400 transition-colors">Berita</a></li>
                            <li><a href="{{ route('public.downloads') }}" class="hover:text-amber-400 transition-colors">Unduh</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold text-white mb-4">Legal</h4>
                        <ul class="space-y-3 text-sm">
                            <li><a href="#" class="hover:text-amber-400 transition-colors">Kebijakan Privasi</a></li>
                            <li><a href="#" class="hover:text-amber-400 transition-colors">Syarat & Ketentuan</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold text-white mb-4">Kontak</h4>
                        <ul class="space-y-3 text-sm">
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-indigo-400 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span class="text-slate-400">Gedung Rektorat Lt. 3,<br>Universitas Teknologi</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                <span class="text-slate-400">halo@aksara.ac.id</span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="border-t border-slate-700 pt-8 mt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-sm text-slate-500 text-center md:text-left">&copy; {{ date('Y') }} AKSA-RA. All rights reserved.</p>
                </div>
            </div>
        </footer>

        {{-- ======================== JAVASCRIPT ======================== --}}
        <script>
            // Header Scroll Logic
            const navbar = document.getElementById('navbar');
            const logoText = document.getElementById('logo-text');
            const btnLogin = document.getElementById('btn-login');

            function handleScroll() {
                if (window.scrollY > 10) {
                    // State: Scrolled (Background Putih, Teks Gelap)
                    navbar.classList.add('glass', 'shadow-sm', 'text-slate-800');
                    navbar.classList.remove('text-white/90');
                    
                    logoText.classList.remove('text-white');
                    logoText.classList.add('text-slate-900');
                    
                    btnLogin.classList.add('border-slate-200', 'bg-slate-900', 'text-white');
                    btnLogin.classList.remove('border-white/20', 'bg-white/10');
                } else {
                    // State: Top (Background Transparan, Teks Putih)
                    navbar.classList.remove('glass', 'shadow-sm', 'text-slate-800');
                    navbar.classList.add('text-white/90');
                    
                    logoText.classList.remove('text-slate-900');
                    logoText.classList.add('text-white');
                    
                    btnLogin.classList.remove('border-slate-200', 'bg-slate-900');
                    btnLogin.classList.add('border-white/20', 'bg-white/10', 'text-white');
                }
            }

            window.addEventListener('scroll', handleScroll);
            // Initial check
            handleScroll();

            // Mobile Menu
            const mobileToggle = document.getElementById('mobile-toggle');
            const closeMenu = document.getElementById('close-menu');
            const mobileMenu = document.getElementById('mobile-menu');

            mobileToggle.addEventListener('click', () => {
                mobileMenu.classList.remove('translate-x-full');
            });

            closeMenu.addEventListener('click', () => {
                mobileMenu.classList.add('translate-x-full');
            });
        </script>
    </body>
</html>

