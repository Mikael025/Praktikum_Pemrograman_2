<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $berita->title }} - {{ config('app.name', 'AKSA-RA') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800 antialiased">
    <header class="bg-indigo-700 text-white py-6 mb-8">
        <div class="max-w-4xl mx-auto px-4">
            <a href="{{ route('public.news', ['category' => $berita->category]) }}" class="text-indigo-200 hover:text-white text-sm">&larr; Kembali ke {{ ucfirst($berita->category) }}</a>
            <h1 class="text-3xl font-bold mt-2">{{ $berita->title }}</h1>
            <div class="mt-2 text-sm text-indigo-100 flex gap-2 items-center">
                <span>{{ $berita->published_at->format('d M Y') }}</span>
                <span>&bull;</span>
                <span>{{ ucfirst($berita->category) }}</span>
            </div>
        </div>
    </header>
    <main class="max-w-4xl mx-auto px-4 pb-16">
        @if($berita->image_path)
            <img src="{{ asset('storage/' . $berita->image_path) }}" alt="{{ $berita->title }}" class="w-full h-64 object-cover rounded-lg mb-8">
        @endif
        <article class="prose max-w-none">
            {!! $berita->content !!}
        </article>
    </main>
    <footer class="bg-slate-900 text-slate-300 py-8 mt-12">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <p class="text-sm">&copy; {{ date('Y') }} AKSA-RA. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
