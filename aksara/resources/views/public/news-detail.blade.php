<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $berita->title }} - {{ config('app.name', 'AKSA-RA') }}</title>

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
        }

        /* Glassmorphism Classes */
        .glass {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
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
                <nav class="hidden md:flex space-x-8">
                    <a href="{{ route('public.home') }}" class="hover:text-amber-400 font-medium transition-colors">Beranda</a>
                    <a href="{{ route('public.visimisi') }}" class="hover:text-amber-400 font-medium transition-colors">Visi & Misi</a>
                    <a href="{{ route('public.news', ['category' => 'semua']) }}" class="hover:text-amber-400 font-medium transition-colors">Informasi/Berita</a>
                    <a href="{{ route('public.downloads') }}" class="hover:text-amber-400 font-medium transition-colors">Unduh</a>
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
            <a href="{{ route('public.home') }}" class="text-2xl font-bold hover:text-amber-400 transition-colors" onclick="document.getElementById('mobile-menu').classList.add('translate-x-full')">Beranda</a>
            <a href="{{ route('public.visimisi') }}" class="text-2xl font-bold hover:text-amber-400 transition-colors" onclick="document.getElementById('mobile-menu').classList.add('translate-x-full')">Visi & Misi</a>
            <a href="{{ route('public.news', ['category' => 'semua']) }}" class="text-2xl font-bold hover:text-amber-400 transition-colors" onclick="document.getElementById('mobile-menu').classList.add('translate-x-full')">Informasi/Berita</a>
            <a href="{{ route('public.downloads') }}" class="text-2xl font-bold hover:text-amber-400 transition-colors" onclick="document.getElementById('mobile-menu').classList.add('translate-x-full')">Unduh</a>
            <a href="{{ route('login') }}" class="px-8 py-3 bg-indigo-600 rounded-full font-bold shadow-lg shadow-indigo-500/50 hover:bg-indigo-700 transition-colors">Masuk Portal</a>
        </div>
    </header>

    {{-- Article Header --}}
    <header class="pt-32 pb-12 bg-gradient-to-b from-indigo-900 via-indigo-800 to-indigo-700 text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('public.news', ['category' => $berita->category]) }}" class="inline-flex items-center text-indigo-200 hover:text-white text-sm mb-4 transition-colors group">
                <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Kembali ke {{ ucfirst($berita->category) }}
            </a>
            <h1 class="text-4xl lg:text-5xl font-extrabold leading-tight mt-4 mb-6">{{ $berita->title }}</h1>
            <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center text-indigo-100">
                <span class="text-sm font-semibold">{{ $berita->published_at->format('d M Y') }}</span>
                <span class="hidden sm:block text-indigo-400">&bull;</span>
                <span class="inline-block px-3 py-1 bg-amber-500 text-indigo-900 text-xs font-bold rounded-full">{{ ucfirst($berita->category) }}</span>
            </div>
        </div>
    </header>
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        @if($berita->image_path)
            <img src="{{ asset('storage/' . $berita->image_path) }}" alt="{{ $berita->title }}" class="w-full h-96 object-cover rounded-2xl mb-12 shadow-lg shadow-indigo-900/10">
        @endif
        <article class="prose prose-lg max-w-none">
            <style>
                .prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
                    @apply text-slate-900 font-bold mt-8 mb-4;
                }
                .prose h1 { @apply text-3xl; }
                .prose h2 { @apply text-2xl; }
                .prose h3 { @apply text-xl; }
                .prose p { @apply text-slate-700 leading-relaxed mb-4; }
                .prose a { @apply text-indigo-600 hover:text-indigo-700 font-semibold; }
                .prose ul, .prose ol { @apply mb-4 pl-6; }
                .prose li { @apply mb-2 text-slate-700; }
                .prose blockquote { @apply border-l-4 border-indigo-600 pl-4 italic text-slate-600 my-4; }
            </style>
            {!! $berita->content !!}
        </article>

        {{-- Back Link --}}
        <div class="mt-12 pt-8 border-t border-slate-200">
            <a href="{{ route('public.news', ['category' => $berita->category]) }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition-colors group">
                <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Kembali ke {{ ucfirst($berita->category) }}
            </a>
        </div>
    </main>

    {{-- ======================== FOOTER ======================== --}}
    <footer class="bg-slate-50 pt-16 pb-8 border-t border-slate-200 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
                <div class="col-span-1 lg:col-span-1">
                    <a href="{{ route('public.home') }}" class="flex items-center gap-2 mb-4">
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
                        <li><a href="{{ route('public.home') }}" class="hover:text-indigo-600 transition-colors">Beranda</a></li>
                        <li><a href="{{ route('public.visimisi') }}" class="hover:text-indigo-600 transition-colors">Visi Misi</a></li>
                        <li><a href="{{ route('public.news', ['category' => 'semua']) }}" class="hover:text-indigo-600 transition-colors">Informasi/Berita</a></li>
                        <li><a href="{{ route('public.downloads') }}" class="hover:text-indigo-600 transition-colors">Unduh</a></li>
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
