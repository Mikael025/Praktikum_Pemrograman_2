<div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-indigo-600">
    <!-- Header -->
    <div class="flex items-start justify-between mb-4">
        <div class="flex-1">
            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full {{ $doc->penelitian ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }} mb-2">
                {{ $doc->penelitian ? 'Penelitian' : 'Pengabdian' }}
            </span>
            <h3 class="text-lg font-semibold text-gray-900 mt-2">
                {{ $doc->penelitian?->judul ?? $doc->pengabdian?->judul }}
            </h3>
        </div>
    </div>

    <!-- Metadata -->
    <div class="space-y-2 mb-4 pb-4 border-b border-gray-200">
        <div class="flex justify-between text-sm">
            <span class="text-gray-600">Dosen</span>
            <span class="font-medium text-gray-900">{{ $doc->penelitian?->user?->name ?? $doc->pengabdian?->user?->name ?? '-' }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-gray-600">Tahun</span>
            <span class="font-medium text-gray-900">{{ $doc->penelitian?->tahun ?? $doc->pengabdian?->tahun ?? '-' }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-gray-600">Diunggah</span>
            <span class="font-medium text-gray-900">{{ $doc->created_at->format('d M Y') }}</span>
        </div>
    </div>

    <!-- File Info -->
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M8 16.5a1 1 0 11-2 0 1 1 0 012 0zM15 7a1 1 0 11-2 0 1 1 0 012 0z"></path>
                <path d="M4 5a2 2 0 012-2h6a1 1 0 01.8.4l2.975 3.696a1 1 0 01.2.8V14a2 2 0 01-2 2H6a2 2 0 01-2-2V5z"></path>
            </svg>
            <div>
                <div class="text-sm font-medium text-gray-900">
                    {{ pathinfo($doc->file_path, PATHINFO_EXTENSION) ? strtoupper(pathinfo($doc->file_path, PATHINFO_EXTENSION)) : 'PDF' }}
                </div>
                @if($doc->file_size)
                    <div class="text-xs text-gray-500">{{ number_format($doc->file_size / 1024 / 1024, 2) }} MB</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Download Button -->
    <a href="{{ route('public.download.document', ['type' => $type, 'id' => $doc->id]) }}" 
       class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
        </svg>
        Unduh Laporan
    </a>
</div>
