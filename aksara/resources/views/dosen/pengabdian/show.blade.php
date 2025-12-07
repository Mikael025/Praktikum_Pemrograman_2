<x-layouts.dosen>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900">Detail Pengabdian</h1>
            <div class="flex space-x-3">
                <a href="{{ route('dosen.pengabdian.edit', $pengabdian) }}" class="inline-flex items-center px-4 py-2 rounded-md bg-yellow-600 text-white hover:bg-yellow-700">
                    Edit
                </a>
                <a href="{{ route('dosen.pengabdian.index') }}" class="inline-flex items-center px-4 py-2 rounded-md bg-gray-600 text-white hover:bg-gray-700">
                    Kembali
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pengabdian</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Judul</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $pengabdian->judul }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tahun</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $pengabdian->tahun }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tim Pelaksana</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $pengabdian->tim_pelaksana }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Lokasi</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $pengabdian->lokasi }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Mitra</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $pengabdian->mitra }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <x-status-badge :status="$pengabdian->status" />
                            </dd>
                        </div>
                    </dl>
                </div>

                <div class="md:col-span-2">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Dokumen Pendukung</h3>
                        @if($pengabdian->documents->count() > 0)
                            <a href="{{ route('pengabdian.documents.download-zip', $pengabdian->id) }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Download Semua (ZIP)
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                <!-- Document Checklist -->
                <div class="md:col-span-1">
                    <x-document-checklist :documents="$pengabdian->documents" type="pengabdian" />
                </div>

                <!-- Document Items -->
                <div class="md:col-span-2">
                    @if($pengabdian->documents->count() > 0)
                        <div class="space-y-4">
                            @foreach($pengabdian->documents as $document)
                                <x-document-item-enhanced :document="$document" type="pengabdian" />
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 bg-gray-50 rounded-lg">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada dokumen</h3>
                            <p class="mt-1 text-sm text-gray-500">Belum ada dokumen yang diunggah.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Status History Timeline -->
        <x-status-timeline :history="$pengabdian->statusHistory" />
    </div>

    <!-- Modals -->
    <x-pdf-preview-modal />
    <x-version-history-modal />
</x-layouts.dosen>
