<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Berita {{ $categoryLabel }} - {{ config('app.name', 'AKSA-RA') }}</title>

        {{-- Fonts --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        {{-- Scripts & Tailwind --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.tailwindcss.com"></script>

        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            
            :root {
                --primary: #4338ca;
                --secondary: #0f172a;
                --accent: #f59e0b;
            }

            .glass {
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            }

            .bg-mesh {
                background-color: #f8fafc;
                background-image: 
                    radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%), 
                    radial-gradient(at 50% 0%, hsla(225,39%,30%,1) 0, transparent 50%), 
                    radial-gradient(at 100% 0%, hsla(339,49%,30%,1) 0, transparent 50%);
            }

            .fade-in-up {
                opacity: 0;
                transform: translateY(20px);
                transition: opacity 0.8s ease-out, transform 0.8s ease-out;
            }
            .fade-in-up.visible {
                opacity: 1;
                transform: translateY(0);
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
                        <a href="{{ route('public.news.umum') }}" class="hover:text-amber-400 font-medium transition-colors">Informasi/Berita</a>
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
        </header>

        <main>
            {{-- ======================== HERO SECTION ======================== --}}
            <section class="relative pt-32 pb-16 overflow-hidden bg-mesh text-white rounded-b-[3rem]"> 
                <div class="max-w-7xl mx-auto px-4 relative z-10 text-center">
                    <h1 class="text-5xl lg:text-6xl font-extrabold mb-4 tracking-tight">
                        Berita {{ $categoryLabel }}
                    </h1>
                    <p class="text-lg text-indigo-600 max-w-2xl mx-auto">
                        Dapatkan informasi terkini tentang kegiatan dan penelitian dari AKSA-RA
                    </p>
                </div>
            </section>

            {{-- ======================== NEWS LISTING ======================== --}}
            <section class="py-16 bg-slate-50">
                <div class="max-w-4xl mx-auto px-4">
                    {{-- Category Filter --}}
                    <div class="mb-12 flex flex-wrap gap-3">
                        <a href="{{ route('public.news', ['category' => 'umum']) }}" class="px-4 py-2 rounded-full font-medium transition-colors {{ $category === 'umum' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                            Umum
                        </a>
                        <a href="{{ route('public.news', ['category' => 'penelitian']) }}" class="px-4 py-2 rounded-full font-medium transition-colors {{ $category === 'penelitian' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                            Penelitian
                        </a>
                        <a href="{{ route('public.news', ['category' => 'pengabdian']) }}" class="px-4 py-2 rounded-full font-medium transition-colors {{ $category === 'pengabdian' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                            Pengabdian
                        </a>
                    </div>

                    {{-- News Grid --}}
                    <div class="space-y-8">
                        @forelse($informasi as $berita)
                        <article class="group border-b border-slate-200 pb-8 last:border-b-0 fade-in-up">
                            <div class="flex flex-col md:flex-row gap-6">
                                {{-- Image --}}
                                <div class="md:w-48 md:h-40 flex-shrink-0">
                                    @if($berita->image_path)
                                        <img src="{{ asset('storage/' . $berita->image_path) }}" alt="{{ $berita->title }}" class="w-full h-full object-cover rounded-lg group-hover:shadow-lg transition-shadow">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-slate-200 to-slate-300 rounded-lg flex items-center justify-center">
                                            <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                {{-- Content --}}
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-sm font-semibold text-indigo-600">{{ ucfirst($berita->category) }}</span>
                                        <span class="text-sm text-slate-500">•</span>
                                        <time class="text-sm text-slate-500">{{ $berita->published_at->format('d M Y') }}</time>
                                    </div>
                                    <h3 class="text-2xl font-bold text-slate-900 mb-3 group-hover:text-indigo-600 transition-colors">
                                        {{ $berita->title }}
                                    </h3>
                                    <p class="text-slate-600 mb-4 line-clamp-3">
                                        {{ strip_tags($berita->content) }}
                                    </p>
                                    <a href="{{ route('public.news', ['category' => $berita->category]) }}#{{ $berita->slug }}" class="inline-flex items-center text-indigo-600 font-semibold hover:translate-x-1 transition-transform">
                                        Baca Selengkapnya
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </article>
                        @empty
                        <div class="text-center py-16">
                            <svg class="mx-auto h-12 w-12 text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h3 class="text-lg font-semibold text-slate-900 mb-2">Belum ada berita</h3>
                            <p class="text-slate-600">Berita kategori ini akan segera tersedia.</p>
                        </div>
                        @endforelse
                    </div>

                    {{-- Pagination --}}
                    @if($informasi->hasPages())
                    <div class="mt-12">
                        {{ $informasi->links() }}
                    </div>
                    @endif
                </div>
            </section>
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

        {{-- ======================== JAVASCRIPT ======================== --}}
        <script>
            // Header Scroll Logic
            const navbar = document.getElementById('navbar');
            const logoText = document.getElementById('logo-text');
            const btnLogin = document.getElementById('btn-login');

            function handleScroll() {
                if (window.scrollY > 10) {
                    navbar.classList.add('glass', 'shadow-sm', 'text-slate-800');
                    navbar.classList.remove('text-white/90');
                    logoText.classList.remove('text-white');
                    logoText.classList.add('text-slate-900');
                    btnLogin.classList.add('border-slate-200', 'bg-slate-900', 'text-white');
                    btnLogin.classList.remove('border-white/20', 'bg-white/10');
                } else {
                    navbar.classList.remove('glass', 'shadow-sm', 'text-slate-800');
                    navbar.classList.add('text-white/90');
                    logoText.classList.remove('text-slate-900');
                    logoText.classList.add('text-white');
                    btnLogin.classList.remove('border-slate-200', 'bg-slate-900');
                    btnLogin.classList.add('border-white/20', 'bg-white/10', 'text-white');
                }
            }

            window.addEventListener('scroll', handleScroll);

            // Fade-in animation
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.1
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.fade-in-up').forEach(el => observer.observe(el));
        </script>
    </body>
</html>
