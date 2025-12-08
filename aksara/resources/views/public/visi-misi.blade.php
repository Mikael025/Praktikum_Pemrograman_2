<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Visi & Misi - {{ config('app.name', 'AKSA-RA') }}</title>

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
                        <a href="{{ route('public.visimisi') }}" class="text-amber-400 font-bold">Visi & Misi</a>
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

            {{-- Mobile Menu --}}
            <div id="mobile-menu" class="fixed inset-0 bg-slate-900/95 z-40 transform translate-x-full transition-transform duration-300 md:hidden flex flex-col justify-center items-center space-y-8 text-white">
                <button id="close-menu" class="absolute top-6 right-6 p-2">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                <a href="{{ route('public.home') }}" class="text-2xl font-bold hover:text-amber-400">Beranda</a>
                <a href="{{ route('public.visimisi') }}" class="text-2xl font-bold text-amber-400">Visi & Misi</a>
                <a href="{{ route('public.news.umum') }}" class="text-2xl font-bold hover:text-amber-400">Berita</a>
                <a href="{{ route('login') }}" class="px-8 py-3 bg-indigo-600 rounded-full font-bold shadow-lg shadow-indigo-500/50">Masuk Portal</a>
            </div>
        </header>

        <main>
            {{-- HERO SECTION --}}
            <section class="relative pt-32 pb-20 lg:pt-40 lg:pb-24 overflow-hidden bg-mesh text-white rounded-b-[2rem]">
                <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 mix-blend-soft-light"></div>
                <div class="max-w-5xl mx-auto px-4 relative z-10 text-center">
                    <h1 class="text-4xl md:text-6xl font-extrabold leading-tight mb-4 tracking-tight">
                        Visi & Misi
                    </h1>
                    <p class="text-lg text-[#4f46e5] max-w-3xl mx-auto font-light">
                        Menjadi fondasi dan penunjuk arah bagi setiap langkah inovasi yang kami gagas di Lembaga Penelitian dan Pengabdian kepada Masyarakat.
                    </p>
                </div>
            </section>

            {{-- Visi Misi CONTENT --}}
            <section class="py-24 bg-slate-50">
                <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="space-y-20">
                        {{-- Visi --}}
                        <div class="flex flex-col md:flex-row items-center gap-12">
                            <div class="md:w-2/5">
                                <div class="relative">
                                    <div class="absolute -inset-2 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-2xl transform -rotate-3 transition-transform hover:rotate-0 duration-300"></div>
                                    <div class="relative bg-slate-800 rounded-2xl p-8 text-white shadow-2xl">
                                        <h2 class="text-4xl font-bold mb-4">Visi</h2>
                                        <p class="text-lg text-indigo-200 leading-relaxed">Menjadi pusat unggulan inovasi riset dan pengabdian masyarakat yang bereputasi global dan berdampak transformatif bagi kemajuan bangsa pada tahun 2045.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="md:w-3/5">
                                <h3 class="text-2xl font-bold text-slate-800 mb-4">Menuju Reputasi Global</h3>
                                <p class="text-slate-600 leading-relaxed">
                                    Visi ini mencerminkan cita-cita luhur kami untuk tidak hanya menjadi yang terdepan dalam kancah riset nasional, tetapi juga untuk diakui di tingkat internasional. Kami bertekad untuk menghasilkan karya-karya inovatif yang tidak hanya relevan secara akademis, tetapi juga memberikan solusi nyata bagi permasalahan bangsa, mendorong kemajuan teknologi, dan meningkatkan kesejahteraan masyarakat secara berkelanjutan.
                                </p>
                            </div>
                        </div>

                        {{-- Misi --}}
                        <div>
                            <h2 class="text-3xl md:text-4xl font-bold text-center text-slate-900 mb-12">Misi Kami</h2>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                                <div class="bg-white p-8 rounded-xl border border-slate-200 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                                    <div class="mb-5 inline-block p-4 bg-indigo-100 text-indigo-600 rounded-full">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-slate-800 mb-3">Riset Unggul & Inovatif</h3>
                                    <p class="text-slate-600 leading-relaxed">Menyelenggarakan penelitian multidisiplin yang inovatif, berstandar mutu internasional, dan berorientasi pada pemecahan masalah strategis bangsa.</p>
                                </div>
                                <div class="bg-white p-8 rounded-xl border border-slate-200 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                                    <div class="mb-5 inline-block p-4 bg-green-100 text-green-600 rounded-full">
                                         <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-slate-800 mb-3">Pengabdian Berdampak Nyata</h3>
                                    <p class="text-slate-600 leading-relaxed">Melaksanakan program pengabdian kepada masyarakat berbasis hasil riset yang mampu mendorong kemandirian dan kemajuan sosial-ekonomi.</p>
                                </div>
                                <div class="bg-white p-8 rounded-xl border border-slate-200 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                                    <div class="mb-5 inline-block p-4 bg-amber-100 text-amber-600 rounded-full">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-slate-800 mb-3">Ekosistem Inovasi Kolaboratif</h3>
                                    <p class="text-slate-600 leading-relaxed">Membangun ekosistem kolaboratif yang sinergis antara akademisi, industri, pemerintah, dan masyarakat untuk akselerasi hilirisasi inovasi.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        {{-- FOOTER --}}
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

        {{-- JAVASCRIPT --}}
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
