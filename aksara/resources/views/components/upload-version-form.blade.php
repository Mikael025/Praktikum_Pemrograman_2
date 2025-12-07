@props(['document', 'type' => 'penelitian'])

<div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
    <div class="flex items-start">
        <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
        </svg>
        <div class="flex-1">
            <h4 class="text-sm font-semibold text-blue-900 mb-2">Upload Versi Baru</h4>
            <p class="text-xs text-blue-700 mb-3">
                Versi saat ini: v{{ $document->version }} • 
                Status: 
                @if($document->isVerified())
                    <span class="font-semibold text-green-700">Terverifikasi</span>
                @elseif($document->isRejected())
                    <span class="font-semibold text-red-700">Ditolak</span>
                @else
                    <span class="font-semibold text-yellow-700">Pending</span>
                @endif
            </p>

            @if($document->isRejected() && $document->rejection_reason)
                <div class="mb-3 p-2 bg-red-50 border border-red-200 rounded text-xs text-red-800">
                    <strong>Alasan Penolakan:</strong> {{ $document->rejection_reason }}
                </div>
            @endif

            <form action="{{ route('documents.upload-version', ['type' => $type, 'document' => $document->id]) }}" 
                  method="POST" 
                  enctype="multipart/form-data"
                  class="space-y-3">
                @csrf
                
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">File Baru</label>
                    <input type="file" 
                           name="file" 
                           accept=".pdf,.doc,.docx" 
                           required
                           class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Catatan Perubahan (Opsional)</label>
                    <textarea name="change_notes" 
                              rows="2" 
                              placeholder="Jelaskan perubahan yang dilakukan..."
                              class="block w-full text-xs border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                <button type="submit" 
                        class="inline-flex items-center px-3 py-1.5 border border-transparent rounded-md shadow-sm text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    Upload Versi {{ $document->version + 1 }}
                </button>
            </form>
        </div>
    </div>
</div>
