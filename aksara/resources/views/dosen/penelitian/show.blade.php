<x-layouts.dosen>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900">Detail Penelitian</h1>
            <div class="flex space-x-3">
                <a href="{{ route('dosen.penelitian.edit', $penelitian) }}" class="inline-flex items-center px-4 py-2 rounded-md bg-yellow-600 text-white hover:bg-yellow-700">
                    Edit
                </a>
                <a href="{{ route('dosen.penelitian.index') }}" class="inline-flex items-center px-4 py-2 rounded-md bg-gray-600 text-white hover:bg-gray-700">
                    Kembali
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Penelitian</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Judul</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $penelitian->judul }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tahun</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $penelitian->tahun }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tim Peneliti</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $penelitian->tim_peneliti }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Sumber Dana</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $penelitian->sumber_dana }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <x-status-badge :status="$penelitian->status" />
                            </dd>
                        </div>
                    </dl>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Progress Penyelesaian</h3>
                    <x-progress-indicator :progress="$penelitian->calculateProgress()" :showDetails="true" />
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Document Checklist -->
            <div class="md:col-span-1">
                <x-document-checklist :documents="$penelitian->documents" type="penelitian" />
            </div>

            <!-- Document List -->
            <div class="md:col-span-2 space-y-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Dokumen Pendukung</h3>
                    @if($penelitian->documents->count() > 0)
                        <a href="{{ route('penelitian.documents.download-zip', $penelitian->id) }}" 
                           class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Download All (ZIP)
                        </a>
                    @endif
                </div>

                @if($penelitian->documents->count() > 0)
                    <div class="space-y-4">
                        @foreach($penelitian->documents as $document)
                            <x-document-item-enhanced :document="$document" type="penelitian" />
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 bg-white rounded-lg border border-gray-200">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">Belum ada dokumen yang diunggah.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Status History Timeline -->
        <x-status-timeline :history="$penelitian->statusHistory" />

        <!-- PDF Preview Modal -->
        <x-pdf-preview-modal />

        <!-- Version History Modal -->
        <x-version-history-modal />
    </div>
</x-layouts.dosen>
