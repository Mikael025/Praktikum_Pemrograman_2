<x-layouts.admin>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Detail Pengabdian Masyarakat</h1>
                <p class="mt-1 text-sm text-gray-600">Informasi lengkap pengabdian dan dokumen pendukung</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('pengabdian.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Kembali
                </a>
                <a href="{{ route('pengabdian.edit', $pengabdian) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                    Edit
                </a>
            </div>
        </div>

        <!-- Info Dosen -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dosen</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $pengabdian->user->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $pengabdian->user->email }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">NIDN</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $pengabdian->user->lecturerProfile->nidn ?? '—' }}</p>
                </div>
            </div>
        </div>

        <!-- Detail Pengabdian -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Detail Pengabdian</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Judul</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $pengabdian->judul }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tahun</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $pengabdian->tahun }}</p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Tim Pelaksana</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $pengabdian->tim_pelaksana }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Lokasi</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $pengabdian->lokasi }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Mitra</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $pengabdian->mitra }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <div class="mt-1">
                        <x-status-badge :status="$pengabdian->status" />
                    </div>
                </div>
                @if($pengabdian->catatan_verifikasi)
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Catatan Verifikasi</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $pengabdian->catatan_verifikasi }}</p>
                    @if($pengabdian->updated_at)
                    <p class="mt-1 text-xs text-gray-500">Terakhir diupdate: {{ $pengabdian->updated_at->format('d M Y, H:i') }} WIB</p>
                    @endif
                </div>
                @endif
            </div>
        </div>

        <!-- Update Catatan Verifikasi (untuk status revisi) -->
        @if(in_array($pengabdian->status, ['lolos_perlu_revisi', 'revisi_pra_final']))
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center mb-4">
                <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                <h2 class="text-lg font-semibold text-gray-900">Update Catatan Verifikasi</h2>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-6">
                <div class="flex">
                    <svg class="w-5 h-5 text-blue-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h3 class="text-sm font-medium text-blue-800">Informasi</h3>
                        <p class="text-sm text-blue-700 mt-1">
                            Gunakan form ini untuk memberikan feedback tambahan kepada dosen tanpa mengubah status. 
                            Dosen akan melihat timestamp terakhir catatan diupdate sebagai notifikasi ada perubahan.
                        </p>
                    </div>
                </div>
            </div>

            <form action="{{ route('pengabdian.update-catatan', $pengabdian) }}" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')
                
                <div>
                    <label for="catatan_verifikasi_update" class="block text-sm font-medium text-gray-700">
                        Catatan Verifikasi <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        id="catatan_verifikasi_update" 
                        name="catatan_verifikasi" 
                        rows="4" 
                        required
                        placeholder="Berikan feedback yang jelas dan spesifik (minimal 10 karakter)..."
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    >{{ old('catatan_verifikasi', $pengabdian->catatan_verifikasi) }}</textarea>
                    @error('catatan_verifikasi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Minimal 10 karakter untuk memastikan feedback berkualitas</p>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Catatan
                    </button>
                </div>
            </form>
        </div>
        @endif

        <!-- Dokumen yang Diupload -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Dokumen yang Diupload</h2>
                <div class="flex items-center space-x-3">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $pengabdian->documents->count() }} dokumen
                    </span>
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
            
            @if($pengabdian->documents->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Document Checklist -->
                    <div class="md:col-span-1">
                        <x-document-checklist :documents="$pengabdian->documents" type="pengabdian" />
                    </div>

                    <!-- Document Items -->
                    <div class="md:col-span-2">
                        <div class="space-y-4">
                            @foreach($pengabdian->documents as $document)
                                <div class="relative">
                                    <x-document-item-enhanced :document="$document" type="pengabdian" />
                                    
                                    <!-- Admin Verification Actions -->
                                    @if($document->verification_status === 'pending')
                                    <div class="mt-2 flex items-center space-x-2">
                                        <form action="{{ route('documents.verify', ['type' => 'pengabdian', 'document' => $document->id]) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Verifikasi
                                            </button>
                                        </form>
                                        <button onclick="confirmReject({{ $document->id }}, 'pengabdian')" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-red-600 hover:bg-red-700">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Tolak
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada dokumen</h3>
                    <p class="mt-1 text-sm text-gray-500">Dosen belum mengupload dokumen pendukung untuk pengabdian ini.</p>
                </div>
            @endif
        </div>

        <!-- Form Verifikasi Dokumen -->
        @if(in_array($pengabdian->status, ['diusulkan', 'lolos_perlu_revisi', 'lolos', 'revisi_pra_final']))
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center mb-4">
                <svg class="w-6 h-6 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h2 class="text-lg font-semibold text-gray-900">Verifikasi Dokumen</h2>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-6">
                <div class="flex">
                    <svg class="w-5 h-5 text-yellow-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <div>
                        <h3 class="text-sm font-medium text-yellow-800">Perhatian</h3>
                        <p class="text-sm text-yellow-700 mt-1">
                            Pastikan Anda telah memeriksa semua dokumen di atas sebelum melakukan verifikasi. 
                            Setelah diverifikasi, status pengabdian akan berubah dan tidak dapat diubah kembali.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form untuk Status Diusulkan -->
            @if($pengabdian->status === 'diusulkan')
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Form Tolak -->
                <form action="{{ route('pengabdian.tidak-lolos', $pengabdian) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-red-800 mb-3">Tolak Pengabdian</h3>
                        <div class="mb-4">
                            <label for="catatan_reject" class="block text-sm font-medium text-gray-700">Alasan Penolakan <span class="text-red-500">*</span></label>
                            <textarea id="catatan_reject" name="catatan" rows="3" required placeholder="Jelaskan alasan penolakan pengabdian..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"></textarea>
                        </div>
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Tolak Pengabdian
                        </button>
                    </div>
                </form>
                
                <!-- Form Lolos Perlu Revisi -->
                <form action="{{ route('pengabdian.lolos-perlu-revisi', $pengabdian) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-yellow-800 mb-3">Lolos Perlu Revisi</h3>
                        <div class="mb-4">
                            <label for="catatan_revisi" class="block text-sm font-medium text-gray-700">Catatan Revisi <span class="text-red-500">*</span></label>
                            <textarea id="catatan_revisi" name="catatan" rows="3" required placeholder="Berikan catatan revisi yang diperlukan..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500"></textarea>
                        </div>
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Lolos Perlu Revisi
                        </button>
                    </div>
                </form>
                
                <!-- Form Lolos -->
                <form action="{{ route('pengabdian.lolos', $pengabdian) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-green-800 mb-3">Lolos</h3>
                        <div class="mb-4">
                            <label for="catatan_lolos" class="block text-sm font-medium text-gray-700">Catatan (Opsional)</label>
                            <textarea id="catatan_lolos" name="catatan" rows="3" placeholder="Tambahkan catatan jika diperlukan..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 "></textarea>
                        </div>
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Lolos
                        </button>
                    </div>
                </form>
            </div>
            @endif

            <!-- Form untuk Status Lolos Perlu Revisi -->
            @if($pengabdian->status === 'lolos_perlu_revisi')
            <div class="max-w-md mx-auto">
                <form action="{{ route('pengabdian.lolos', $pengabdian) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-green-800 mb-3">Lolos</h3>
                        <div class="mb-4">
                            <label for="catatan_lolos" class="block text-sm font-medium text-gray-700">Catatan (Opsional)</label>
                            <textarea id="catatan_lolos" name="catatan" rows="3" placeholder="Tambahkan catatan jika diperlukan..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 "></textarea>
                        </div>
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Lolos
                        </button>
                    </div>
                </form>
            </div>
            @endif

            <!-- Form untuk Status Lolos -->
            @if($pengabdian->status === 'lolos')
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Form Revisi Pra-final -->
                <form action="{{ route('pengabdian.revisi-pra-final', $pengabdian) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-orange-800 mb-3">Revisi Pra-final</h3>
                        <div class="mb-4">
                            <label for="catatan_revisi_pra" class="block text-sm font-medium text-gray-700">Catatan Revisi <span class="text-red-500">*</span></label>
                            <textarea id="catatan_revisi_pra" name="catatan" rows="3" required placeholder="Berikan catatan revisi pra-final yang diperlukan..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 "></textarea>
                        </div>
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Revisi Pra-final
                        </button>
                    </div>
                </form>
                
                <!-- Form Selesai -->
                <form action="{{ route('pengabdian.selesai', $pengabdian) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="bg-emerald-50 border border-emerald-200  rounded-lg p-4">
                        <h3 class="text-sm font-medium text-emerald-800 mb-3">Selesai</h3>
                        <div class="mb-4">
                            <label for="catatan_selesai" class="block text-sm font-medium text-gray-700">Catatan (Opsional)</label>
                            <textarea id="catatan_selesai" name="catatan" rows="3" placeholder="Tambahkan catatan jika diperlukan..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 "></textarea>
                        </div>
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Selesai
                        </button>
                    </div>
                </form>
            </div>
            @endif

            <!-- Form untuk Status Revisi Pra-final -->
            @if($pengabdian->status === 'revisi_pra_final')
            <div class="max-w-md mx-auto">
                <form action="{{ route('pengabdian.selesai', $pengabdian) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="bg-emerald-50 border border-emerald-200  rounded-lg p-4">
                        <h3 class="text-sm font-medium text-emerald-800 mb-3">Selesai</h3>
                        <div class="mb-4">
                            <label for="catatan_selesai" class="block text-sm font-medium text-gray-700">Catatan (Opsional)</label>
                            <textarea id="catatan_selesai" name="catatan" rows="3" placeholder="Tambahkan catatan jika diperlukan..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 "></textarea>
                        </div>
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Selesai
                        </button>
                    </div>
                </form>
            </div>
            @endif
        </div>
        @endif

        <!-- Status History Timeline -->
        <x-status-timeline :history="$pengabdian->statusHistory" />
    </div>

    <!-- Modals -->
    <x-pdf-preview-modal />
    <x-version-history-modal />

    <script>
    function confirmReject(documentId, type) {
        const reason = prompt('Masukkan alasan penolakan dokumen:');
        if (reason && reason.trim() !== '') {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/documents/${type}/${documentId}/reject`;
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            
            const reasonInput = document.createElement('input');
            reasonInput.type = 'hidden';
            reasonInput.name = 'rejection_reason';
            reasonInput.value = reason;
            
            form.appendChild(csrfInput);
            form.appendChild(reasonInput);
            document.body.appendChild(form);
            form.submit();
        }
    }
    </script>
</x-layouts.admin>
