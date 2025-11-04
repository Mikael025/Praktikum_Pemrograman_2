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
                            <dd class="mt-1 text-sm text-gray-900">
                                @if(is_array($penelitian->tim_peneliti))
                                    {{ implode(', ', $penelitian->tim_peneliti) }}
                                @else
                                    {{ $penelitian->tim_peneliti }}
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Sumber Dana</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $penelitian->sumber_dana }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                @php
                                    $statusColors = [
                                        'draft' => 'bg-gray-100 text-gray-700',
                                        'menunggu_verifikasi' => 'bg-yellow-100 text-yellow-700',
                                        'terverifikasi' => 'bg-green-100 text-green-700',
                                        'ditolak' => 'bg-red-100 text-red-700',
                                        'berjalan' => 'bg-blue-100 text-blue-700',
                                        'selesai' => 'bg-purple-100 text-purple-700',
                                    ];
                                    $statusLabels = [
                                        'draft' => 'Draft',
                                        'menunggu_verifikasi' => 'Menunggu Verifikasi',
                                        'terverifikasi' => 'Terverifikasi',
                                        'ditolak' => 'Ditolak',
                                        'berjalan' => 'Berjalan',
                                        'selesai' => 'Selesai',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs {{ $statusColors[$penelitian->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $statusLabels[$penelitian->status] ?? ucfirst($penelitian->status) }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Dokumen Pendukung</h3>
                    @if($penelitian->documents->count() > 0)
                        <div class="space-y-3">
                            @foreach($penelitian->documents as $document)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $document->nama_file }}</p>
                                        <p class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $document->jenis_dokumen)) }}</p>
                                    </div>
                                    <a href="{{ asset('storage/' . $document->path_file) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 text-sm">Download</a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">Belum ada dokumen yang diunggah.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.dosen>
