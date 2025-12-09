<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Unduh Laporan - AKSARA</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50">
    <!-- Header Hero -->
    <div class="bg-gradient-to-r from-indigo-600 to-blue-600 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold mb-4">Laporan Akhir Penelitian & Pengabdian</h1>
            <p class="text-indigo-100 text-lg">Akses laporan lengkap dari proyek-proyek yang telah diselesaikan</p>
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">
                <div class="bg-white/10 backdrop-blur rounded-lg p-4">
                    <div class="text-3xl font-bold">{{ $totalDocuments }}</div>
                    <div class="text-indigo-100">Total Laporan</div>
                </div>
                <div class="bg-white/10 backdrop-blur rounded-lg p-4">
                    <div class="text-3xl font-bold">{{ $penelitianCount }}</div>
                    <div class="text-indigo-100">Penelitian</div>
                </div>
                <div class="bg-white/10 backdrop-blur rounded-lg p-4">
                    <div class="text-3xl font-bold">{{ $pengabdianCount }}</div>
                    <div class="text-indigo-100">Pengabdian</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Filters Section -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <form method="GET" action="{{ route('public.downloads') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Category Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                        <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">Semua Kategori</option>
                            <option value="penelitian" {{ request('category') == 'penelitian' ? 'selected' : '' }}>Penelitian</option>
                            <option value="pengabdian" {{ request('category') == 'pengabdian' ? 'selected' : '' }}>Pengabdian</option>
                        </select>
                    </div>

                    <!-- Year Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                        <select name="year" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">Semua Tahun</option>
                            @for ($year = now()->year; $year >= 2020; $year--)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endfor
                        </select>
                    </div>

                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cari Judul</label>
                        <div class="flex gap-2">
                            <input type="text" name="search" placeholder="Cari judul..." value="{{ request('search') }}" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">Cari</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- No Results Message -->
        @if($documents->isEmpty())
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-500 text-lg">Belum ada laporan tersedia dengan filter yang dipilih</p>
            </div>
        @else
            <!-- Desktop View (Table) -->
            <div class="hidden md:block bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Jenis</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Judul</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Dosen</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Tahun</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">File</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($documents as $doc)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $doc->penelitian ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $doc->penelitian ? 'Penelitian' : 'Pengabdian' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $doc->penelitian?->judul ?? $doc->pengabdian?->judul }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            Diunggah {{ $doc->created_at->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-700">
                                            {{ $doc->penelitian?->user?->name ?? $doc->pengabdian?->user?->name ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $doc->penelitian?->tahun ?? $doc->pengabdian?->tahun ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-700">
                                            {{ $doc->file_size ? number_format($doc->file_size / 1024 / 1024, 2) . ' MB' : '-' }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ pathinfo($doc->file_path, PATHINFO_EXTENSION) ? strtoupper(pathinfo($doc->file_path, PATHINFO_EXTENSION)) : 'PDF' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('public.download.document', ['type' => ($doc->penelitian ? 'penelitian' : 'pengabdian'), 'id' => $doc->id]) }}" 
                                           class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
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

            <!-- Mobile View (Cards) -->
            <div class="md:hidden space-y-4">
                @foreach($documents as $doc)
                    @include('components.document-card', [
                        'doc' => $doc,
                        'type' => $doc->penelitian ? 'penelitian' : 'pengabdian'
                    ])
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $documents->links() }}
            </div>
        @endif
    </div>
        </div>
    </body>
</html>

