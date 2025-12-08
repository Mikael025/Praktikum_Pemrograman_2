<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'AKSA-RA') }} - Inovasi Tanpa Batas</title>

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

            /* Mesh Gradient Background for Hero */
            .bg-mesh {
                background-color: #f8fafc;
                background-image: 
                    radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%), 
                    radial-gradient(at 50% 0%, hsla(225,39%,30%,1) 0, transparent 50%), 
                    radial-gradient(at 100% 0%, hsla(339,49%,30%,1) 0, transparent 50%);
            }

            /* Animations */
            .fade-in-up {
                opacity: 0;
                transform: translateY(20px);
                transition: opacity 0.8s ease-out, transform 0.8s ease-out;
            }
            .fade-in-up.visible {
                opacity: 1;
                transform: translateY(0);
            }

            .blob-shape {
                border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%;
                animation: morph 8s linear infinite;
            }

            @keyframes morph {
                0%, 100% { border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%; }
                34% { border-radius: 70% 30% 50% 50% / 30% 30% 70% 70%; }
                67% { border-radius: 100% 60% 60% 100% / 100% 100% 60% 60%; }
            }
        </style>
    </head>
    <body class="bg-slate-50 text-slate-800 antialiased selection:bg-indigo-500 selection:text-white">

        {{-- ======================== HEADER ======================== --}}
        {{-- Default text-white/90 agar terlihat di background gelap --}}
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
                <a href="{{ route('public.home') }}" class="text-2xl font-bold hover:text-amber-400">Beranda</a>
                <a href="{{ route('public.visimisi') }}" class="text-2xl font-bold hover:text-amber-400">Visi & Misi</a>
                <a href="{{ route('public.news', ['category' => 'semua']) }}" class="text-2xl font-bold hover:text-amber-400">Berita</a>
                <a href="{{ route('login') }}" class="px-8 py-3 bg-indigo-600 rounded-full font-bold shadow-lg shadow-indigo-500/50">Masuk Portal</a>
            </div>
        </header>

        <main>
            {{-- ======================== HERO SECTION ======================== --}}
            <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden bg-mesh text-white rounded-b-[3rem]">
                {{-- Noise Overlay --}}
                <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 mix-blend-soft-light"></div>
                
                <div class="max-w-7xl mx-auto px-4 relative z-10">
                    <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-20">
                        
                        {{-- Hero Text --}}
                        <div class="lg:w-1/2 text-center lg:text-left fade-in-up">
                            <div class="inline-block px-4 py-1.5 rounded-full border border-white/20 bg-white/5 backdrop-blur-md mb-6">
                                <span class="text-indigo-100 text-xs font-bold tracking-wider uppercase">Revolusi Riset & Pengabdian</span>
                            </div>
                            
                            <h1 class="text-5xl lg:text-7xl font-extrabold leading-tight mb-6 tracking-tight">
                                Solusi Cerdas <br> untuk 
                                {{-- Gradient Teks Diperbaiki --}}
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 via-amber-400 to-orange-500 drop-shadow-sm">Masa Depan</span>
                            </h1>
                            
                            {{-- Warna paragraf diperbaiki agar menyatu dengan background --}}
                            <p class="text-lg text-indigo-100/90 mb-8 leading-relaxed max-w-2xl mx-auto lg:mx-0 font-light">
                            </p>
                            
                            <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                                <a href="#" class="px-8 py-4 bg-white text-indigo-950 rounded-full font-bold hover:bg-indigo-50 transition-all shadow-xl shadow-indigo-900/20 transform hover:-translate-y-1">
                                    Mulai Jelajahi
                                </a>
                                <a href="#" class="px-8 py-4 bg-transparent border border-white/30 text-white rounded-full font-bold hover:bg-white/10 transition-all flex items-center justify-center gap-2 group backdrop-blur-sm">
                                    <span>Tonton Video</span>
                                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </a>
                            </div>
                        </div>

                        {{-- Hero Image --}}
                        <div class="lg:w-1/2 relative fade-in-up" style="transition-delay: 200ms;">
                            {{-- Glow Effects --}}
                            <div class="absolute -top-10 -right-10 w-72 h-72 bg-amber-400/20 rounded-full blur-3xl filter mix-blend-screen"></div>
                            <div class="absolute -bottom-10 -left-10 w-72 h-72 bg-indigo-500/30 rounded-full blur-3xl filter mix-blend-screen"></div>
                            
                            <div class="relative z-10 blob-shape overflow-hidden shadow-2xl border-[6px] border-white/10 transform hover:scale-105 transition-transform duration-500">
                                <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Kolaborasi Riset" class="w-full h-full object-cover">
                            </div>

                            <div class="absolute -bottom-6 -left-6 bg-white p-4 rounded-2xl shadow-xl z-20 animate-bounce" style="animation-duration: 3s;">
                                <div class="flex items-center gap-3">
                                    <div class="bg-green-100 p-2 rounded-full text-green-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-slate-500 font-bold uppercase">Proyek Selesai</p>
                                        <p class="text-xl font-bold text-slate-800">120+</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ======================== STATS BANNER ======================== --}}
            <section class="max-w-6xl mx-auto px-4 -mt-16 relative z-20 fade-in-up">
                <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-8 grid grid-cols-2 md:grid-cols-4 gap-8 divide-x divide-slate-100">
                    <div class="text-center">
                        <p class="text-4xl font-extrabold text-indigo-600 mb-1 counter" data-target="450">0</p>
                        <p class="text-sm font-semibold text-slate-500">Publikasi Riset</p>
                    </div>
                    <div class="text-center pl-4">
                        <p class="text-4xl font-extrabold text-indigo-600 mb-1 counter" data-target="86">0</p>
                        <p class="text-sm font-semibold text-slate-500">Hak Cipta</p>
                    </div>
                    <div class="text-center pl-4">
                        <p class="text-4xl font-extrabold text-indigo-600 mb-1 counter" data-target="120">0</p>
                        <p class="text-sm font-semibold text-slate-500">Desa Binaan</p>
                    </div>
                    <div class="text-center pl-4">
                        <p class="text-4xl font-extrabold text-indigo-600 mb-1 counter" data-target="35">0</p>
                        <p class="text-sm font-semibold text-slate-500">Mitra Industri</p>
                    </div>
                </div>
            </section>

            {{-- ======================== FEATURED RESEARCH ======================== --}}
            <section class="py-24 bg-slate-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16 fade-in-up">
                        <h2 class="text-indigo-600 font-bold tracking-wide uppercase mb-2">Inovasi Terkini</h2>
                        <h3 class="text-3xl md:text-4xl font-extrabold text-slate-900">Menembus Batas Pengetahuan</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        @forelse($featuredBerita as $index => $berita)
                        <div class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300 border border-slate-100 fade-in-up delay-{{ $loop->iteration }}00">
                            <div class="relative h-56 overflow-hidden">
                                @if($berita->image_path)
                                    <img src="{{ asset('storage/' . $berita->image_path) }}" alt="{{ $berita->title }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-slate-200 to-slate-300 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 to-transparent"></div>
                                <span class="absolute top-4 left-4 bg-{{ $berita->category === 'penelitian' ? 'amber' : ($berita->category === 'pengabdian' ? 'teal' : 'indigo') }}-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">{{ ucfirst($berita->category) }}</span>
                            </div>
                            <div class="p-6">
                                <h4 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-indigo-600 transition-colors line-clamp-2">{{ $berita->title }}</h4>
                                <p class="text-slate-600 text-sm mb-4 line-clamp-3">{{ strip_tags($berita->content) }}</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-slate-500">{{ $berita->published_at->format('d M Y') }}</span>
                                    <a href="{{ route('public.news.detail', ['category' => $berita->category, 'slug' => $berita->slug]) }}" class="inline-flex items-center text-indigo-600 font-semibold text-sm hover:translate-x-1 transition-transform">
                                        Baca Selengkapnya <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @empty
                        {{-- Fallback cards jika tidak ada berita --}}
                        <div class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300 border border-slate-100 fade-in-up delay-100">
                            <div class="relative h-56 overflow-hidden">
                                <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Data Science" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 to-transparent"></div>
                                <span class="absolute top-4 left-4 bg-amber-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">Technology</span>
                            </div>
                            <div class="p-6">
                                <h4 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-indigo-600 transition-colors">Big Data untuk Prediksi Panen Raya</h4>
                                <p class="text-slate-600 text-sm mb-4 line-clamp-3">Implementasi machine learning dalam membantu petani menentukan masa tanam yang optimal berdasarkan data iklim historis.</p>
                                <a href="#" class="inline-flex items-center text-indigo-600 font-semibold text-sm hover:translate-x-1 transition-transform">
                                    Baca Selengkapnya <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </a>
                            </div>
                        </div>

                        <div class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300 border border-slate-100 fade-in-up delay-200">
                            <div class="relative h-56 overflow-hidden">
                                <img src="https://images.unsplash.com/photo-1532094349884-543bc11b234d?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Lab Research" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 to-transparent"></div>
                                <span class="absolute top-4 left-4 bg-teal-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">Health</span>
                            </div>
                            <div class="p-6">
                                <h4 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-indigo-600 transition-colors">Ekstraksi Senyawa Herbal Lokal</h4>
                                <p class="text-slate-600 text-sm mb-4 line-clamp-3">Penelitian mendalam mengenai potensi tanaman endemik lokal sebagai alternatif obat diabetes tipe 2 yang terjangkau.</p>
                                <a href="#" class="inline-flex items-center text-indigo-600 font-semibold text-sm hover:translate-x-1 transition-transform">
                                    Baca Selengkapnya <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </a>
                            </div>
                        </div>

                        <div class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300 border border-slate-100 fade-in-up delay-300">
                            <div class="relative h-56 overflow-hidden">
                                <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Economy" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 to-transparent"></div>
                                <span class="absolute top-4 left-4 bg-indigo-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">Economy</span>
                            </div>
                            <div class="p-6">
                                <h4 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-indigo-600 transition-colors">Digitalisasi UMKM Pasca Pandemi</h4>
                                <p class="text-slate-600 text-sm mb-4 line-clamp-3">Analisis strategi adaptasi pelaku usaha mikro dalam memanfaatkan platform e-commerce untuk keberlanjutan bisnis.</p>
                                <a href="#" class="inline-flex items-center text-indigo-600 font-semibold text-sm hover:translate-x-1 transition-transform">
                                    Baca Selengkapnya <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </a>
                            </div>
                        </div>
                        @endforelse
                    </div>

                    <div class="mt-12 text-center">
                        <a href="{{ route('public.downloads') }}" class="inline-block px-6 py-3 border-2 border-slate-200 text-slate-600 font-bold rounded-lg hover:border-indigo-600 hover:text-indigo-600 transition-colors">
                            Lihat Arsip Penelitian
                        </a>
                    </div>
                </div>
            </section>

            {{-- ======================== TESTIMONIALS ======================== --}}
            <section class="py-24 bg-white relative overflow-hidden">
                <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-indigo-50 rounded-full blur-3xl opacity-50"></div>
                
                <div class="max-w-7xl mx-auto px-4 relative z-10">
                    <div class="flex flex-col md:flex-row justify-between items-end mb-12 fade-in-up">
                        <div class="max-w-xl">
                            <h2 class="text-indigo-600 font-bold tracking-wide uppercase mb-2">Suara Komunitas</h2>
                            <h3 class="text-3xl md:text-4xl font-extrabold text-slate-900">Dampak Nyata di Lapangan</h3>
                        </div>
                        <div class="flex gap-2 mt-4 md:mt-0">
                            <button class="p-2 rounded-full border border-slate-200 hover:bg-slate-100 text-slate-500"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></button>
                            <button class="p-2 rounded-full bg-slate-900 text-white hover:bg-slate-800"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Testimonial 1 --}}
                        <div class="p-8 bg-slate-50 rounded-2xl border border-slate-100 relative fade-in-up">
                            <svg class="absolute top-6 right-6 w-12 h-12 text-indigo-100" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21L14.017 18C14.017 16.0547 15.5947 14.4772 17.5401 14.4772L19.5852 14.4772L19.5852 9.54443L17.5401 9.54443C15.6796 9.54443 14.1706 8.03541 14.1706 6.17495L14.1706 3L22 3L22 13.0449C22 17.4389 18.4116 21 14.017 21ZM5.01657 21L5.01657 18C5.01657 16.0547 6.59426 14.4772 8.53966 14.4772L10.5847 14.4772L10.5847 9.54443L8.53966 9.54443C6.67915 9.54443 5.17013 8.03541 5.17013 6.17495L5.17013 3L13 3L13 13.0449C13 17.4389 9.41113 21 5.01657 21Z"/></svg>
                            <p class="text-slate-700 italic text-lg mb-6 relative z-10">"Kerjasama dengan tim peneliti AKSA-RA memberikan wawasan baru bagi pengembangan produk desa kami. Penjualan kami meningkat 300% berkat strategi branding yang mereka sarankan."</p>
                            <div class="flex items-center gap-4">
                                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" alt="User" class="w-12 h-12 rounded-full object-cover ring-2 ring-indigo-500 ring-offset-2">
                                <div>
                                    <h4 class="font-bold text-slate-900">Budi Santoso</h4>
                                    <p class="text-sm text-slate-500">Ketua Koperasi Tani Makmur</p>
                                </div>
                            </div>
                        </div>

                        {{-- Testimonial 2 --}}
                        <div class="p-8 bg-slate-50 rounded-2xl border border-slate-100 relative fade-in-up delay-100">
                             <svg class="absolute top-6 right-6 w-12 h-12 text-indigo-100" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21L14.017 18C14.017 16.0547 15.5947 14.4772 17.5401 14.4772L19.5852 14.4772L19.5852 9.54443L17.5401 9.54443C15.6796 9.54443 14.1706 8.03541 14.1706 6.17495L14.1706 3L22 3L22 13.0449C22 17.4389 18.4116 21 14.017 21ZM5.01657 21L5.01657 18C5.01657 16.0547 6.59426 14.4772 8.53966 14.4772L10.5847 14.4772L10.5847 9.54443L8.53966 9.54443C6.67915 9.54443 5.17013 8.03541 5.17013 6.17495L5.17013 3L13 3L13 13.0449C13 17.4389 9.41113 21 5.01657 21Z"/></svg>
                            <p class="text-slate-700 italic text-lg mb-6 relative z-10">"Platform yang inovatif dan sangat responsif terhadap kebutuhan mitra. Program pengabdian masyarakat yang dijalankan benar-benar tepat sasaran dan berkelanjutan."</p>
                            <div class="flex items-center gap-4">
                                <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" alt="User" class="w-12 h-12 rounded-full object-cover ring-2 ring-indigo-500 ring-offset-2">
                                <div>
                                    <h4 class="font-bold text-slate-900">Dr. Sarah Wijaya</h4>
                                    <p class="text-sm text-slate-500">Direktur LSM Lingkungan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ======================== CTA SECTION ======================== --}}
            <section class="py-20 bg-slate-900 text-white relative overflow-hidden">
                <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10"></div>
                <div class="absolute top-0 right-0 w-full h-full bg-gradient-to-br from-indigo-900 via-slate-900 to-slate-900 opacity-90"></div>
                
                <div class="max-w-4xl mx-auto px-4 text-center relative z-10 fade-in-up">
                    <h2 class="text-4xl lg:text-5xl font-extrabold mb-6 tracking-tight">Siap Berkolaborasi?</h2>
                    <p class="text-lg text-slate-300 mb-10 max-w-2xl mx-auto">
                        Bergabunglah dengan jaringan peneliti dan praktisi kami untuk menciptakan solusi yang berdampak luas bagi Indonesia.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="#" class="px-8 py-4 bg-amber-500 text-slate-900 rounded-full font-bold hover:bg-amber-400 transition-colors shadow-lg shadow-amber-500/20">
                            Hubungi Kami
                        </a>
                        <a href="{{ route('login') }}" class="px-8 py-4 bg-slate-800 border border-slate-700 text-white rounded-full font-bold hover:bg-slate-700 transition-colors">
                            Login Mitra
                        </a>
                    </div>
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
                    // State: Scrolled (Background Putih, Teks Gelap)
                    navbar.classList.add('glass', 'shadow-sm', 'text-slate-800');
                    navbar.classList.remove('text-white/90');
                    
                    logoText.classList.remove('text-white');
                    logoText.classList.add('text-slate-900');
                    
                    // Button login adjust styling if needed, or keep transparent
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

            // Intersection Observer for Animations & Counters
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.1
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        
                        // Counter Animation
                        const counters = entry.target.querySelectorAll('.counter');
                        counters.forEach(counter => {
                            const target = +counter.getAttribute('data-target');
                            const duration = 2000;
                            const increment = target / (duration / 16);
                            
                            let current = 0;
                            const updateCounter = () => {
                                current += increment;
                                if (current < target) {
                                    counter.innerText = Math.ceil(current);
                                    requestAnimationFrame(updateCounter);
                                } else {
                                    counter.innerText = target;
                                }
                            };
                            updateCounter();
                        });

                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.fade-in-up').forEach(el => observer.observe(el));
        </script>
    </body>
</html>