@props(['document'])

<div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
    <div class="flex-1">
        <div class="flex items-center mb-2">
            <!-- Document Icon -->
            <svg class="w-5 h-5 mr-2 
                @if($document->jenis_dokumen === 'proposal') text-blue-600
                @elseif($document->jenis_dokumen === 'laporan_akhir') text-green-600
                @elseif($document->jenis_dokumen === 'sertifikat') text-purple-600
                @else text-gray-600
                @endif" 
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <div>
                <p class="text-sm font-medium text-gray-900">{{ $document->nama_file }}</p>
                <p class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $document->jenis_dokumen)) }}</p>
            </div>
        </div>

        <!-- Category Badge -->
        @if($document->category)
            <div class="mb-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                    @if($document->category === 'penting') bg-red-100 text-red-800
                    @elseif($document->category === 'wajib') bg-orange-100 text-orange-800
                    @elseif($document->category === 'tambahan') bg-blue-100 text-blue-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                    </svg>
                    {{ ucfirst($document->category) }}
                </span>
            </div>
        @endif

        <!-- Tags -->
        @if($document->tags && count($document->tags) > 0)
            <div class="flex flex-wrap gap-1">
                @foreach($document->tags as $tag)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                        #{{ $tag }}
                    </span>
                @endforeach
            </div>
        @endif

        <p class="text-xs text-gray-400 mt-2">
            Uploaded: {{ $document->uploaded_at->format('d M Y, H:i') }}
        </p>
    </div>

    <!-- Actions -->
    <div class="flex items-center space-x-2 ml-4">
        <a href="{{ asset('storage/' . $document->path_file) }}" 
           target="_blank" 
           class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-xs font-medium text-gray-700 bg-white hover:bg-gray-50">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            View
        </a>
        <a href="{{ asset('storage/' . $document->path_file) }}" 
           download 
           class="inline-flex items-center px-3 py-1.5 border border-transparent rounded-md text-xs font-medium text-white bg-indigo-600 hover:bg-indigo-700">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Download
        </a>
    </div>
</div>
