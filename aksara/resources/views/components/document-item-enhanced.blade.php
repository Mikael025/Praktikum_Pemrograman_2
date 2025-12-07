@props(['document', 'type' => 'penelitian'])

<div class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow">
    <div class="flex items-start justify-between">
        <div class="flex-1">
            <!-- Document Info -->
            <div class="flex items-center space-x-3 mb-2">
                <div class="flex-shrink-0">
                    @if(Str::endsWith($document->path_file, '.pdf'))
                        <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                        </svg>
                    @else
                        <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                        </svg>
                    @endif
                </div>
                
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-semibold text-gray-900 truncate">{{ $document->nama_file }}</h4>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ ucfirst(str_replace('_', ' ', $document->jenis_dokumen)) }} • 
                        Version {{ $document->version }} • 
                        {{ $document->uploaded_at->format('d M Y') }}
                    </p>
                </div>
            </div>
            
            <!-- Verification Status Badge -->
            <div class="flex items-center space-x-2 mt-2">
                @if($document->isVerified())
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Terverifikasi
                    </span>
                @elseif($document->isRejected())
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        Ditolak
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        Pending
                    </span>
                @endif
                
                @if($document->category)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ ucfirst($document->category) }}
                    </span>
                @endif
            </div>
            
            <!-- Tags -->
            @if(isset($document->tags) && $document->tags && count($document->tags) > 0)
                <div class="flex flex-wrap gap-1 mt-2">
                    @foreach($document->tags as $tag)
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                            #{{ $tag }}
                        </span>
                    @endforeach
                </div>
            @endif
            
            <!-- Rejection Reason -->
            @if($document->isRejected() && $document->rejection_reason)
                <div class="mt-2 p-2 bg-red-50 border border-red-200 rounded">
                    <p class="text-xs text-red-700">
                        <strong>Alasan Penolakan:</strong> {{ $document->rejection_reason }}
                    </p>
                </div>
            @endif
        </div>
        
        <!-- Actions -->
        <div class="flex flex-col space-y-1 ml-4">
            <button 
                onclick="previewDocument('{{ asset('storage/' . $document->path_file) }}', '{{ $document->nama_file }}')"
                class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50"
            >
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Preview
            </button>
            
            <a 
                href="{{ asset('storage/' . $document->path_file) }}" 
                download
                class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50"
            >
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Download
            </a>
            
            @php
                try {
                    $hasVersions = $document->version > 1 || $document->getAllVersions()->count() > 0;
                } catch (\Exception $e) {
                    $hasVersions = false;
                }
            @endphp
            
            @if($hasVersions)
                <button 
                    onclick="showVersionHistory({{ $document->id }}, '{{ $type }}')"
                    class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50"
                >
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    History
                </button>
            @endif
        </div>
    </div>
</div>
