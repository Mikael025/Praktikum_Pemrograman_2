@props(['documents', 'type' => 'penelitian'])

@php
    $requiredDocs = [
        'proposal' => 'Proposal',
        'laporan_akhir' => 'Laporan Akhir',
    ];
    
    $uploadedTypes = $documents->pluck('jenis_dokumen')->toArray();
    $totalRequired = count($requiredDocs);
    $totalUploaded = count(array_intersect(array_keys($requiredDocs), $uploadedTypes));
    $progress = $totalRequired > 0 ? round(($totalUploaded / $totalRequired) * 100) : 0;
@endphp

<div class="bg-white rounded-lg border border-gray-200 p-4">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-semibold text-gray-900">Checklist Dokumen</h3>
        <span class="text-xs font-medium text-gray-600">{{ $totalUploaded }}/{{ $totalRequired }}</span>
    </div>
    
    <!-- Progress Bar -->
    <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
        <div class="bg-indigo-600 h-2 rounded-full transition-all duration-300" style="width: {{ $progress }}%"></div>
    </div>
    
    <!-- Document List -->
    <div class="space-y-2">
        @foreach($requiredDocs as $docType => $docLabel)
            @php
                $doc = $documents->where('jenis_dokumen', $docType)->first();
                $isUploaded = $doc !== null;
                $isVerified = $isUploaded && $doc->isVerified();
                $isRejected = $isUploaded && $doc->isRejected();
                $isPending = $isUploaded && $doc->isPending();
            @endphp
            
            <div class="flex items-center justify-between p-2 rounded-lg {{ $isUploaded ? 'bg-green-50' : 'bg-gray-50' }}">
                <div class="flex items-center space-x-2">
                    @if($isVerified)
                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    @elseif($isRejected)
                        <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    @elseif($isPending)
                        <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                    @else
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-2a6 6 0 100-12 6 6 0 000 12z" clip-rule="evenodd"/>
                        </svg>
                    @endif
                    
                    <div>
                        <p class="text-sm font-medium {{ $isUploaded ? 'text-gray-900' : 'text-gray-500' }}">
                            {{ $docLabel }}
                        </p>
                        @if($isUploaded)
                            <p class="text-xs text-gray-500">
                                v{{ $doc->version }} • 
                                @if($isVerified)
                                    <span class="text-green-600">Terverifikasi</span>
                                @elseif($isRejected)
                                    <span class="text-red-600">Ditolak</span>
                                @else
                                    <span class="text-yellow-600">Menunggu Verifikasi</span>
                                @endif
                            </p>
                        @endif
                    </div>
                </div>
                
                @if($isUploaded)
                    <button 
                        onclick="previewDocument('{{ asset('storage/' . $doc->path_file) }}', '{{ $doc->nama_file }}')"
                        class="text-indigo-600 hover:text-indigo-800 text-xs font-medium"
                    >
                        Lihat
                    </button>
                @endif
            </div>
        @endforeach
        
        <!-- Supporting Documents -->
        @php
            $supportingDocs = $documents->where('jenis_dokumen', 'dokumen_pendukung');
        @endphp
        @if($supportingDocs->count() > 0)
            <div class="pt-2 mt-2 border-t border-gray-200">
                <p class="text-xs font-medium text-gray-600 mb-2">Dokumen Pendukung ({{ $supportingDocs->count() }})</p>
                @foreach($supportingDocs as $doc)
                    <div class="flex items-center justify-between p-2 rounded-lg bg-blue-50 mb-1">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-xs text-gray-700">{{ Str::limit($doc->nama_file, 20) }}</span>
                        </div>
                        <button 
                            onclick="previewDocument('{{ asset('storage/' . $doc->path_file) }}', '{{ $doc->nama_file }}')"
                            class="text-blue-600 hover:text-blue-800 text-xs"
                        >
                            Lihat
                        </button>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
