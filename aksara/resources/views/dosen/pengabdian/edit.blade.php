<x-layouts.dosen>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900">Edit Pengabdian</h1>
            <div class="flex space-x-3">
                <a href="{{ route('dosen.pengabdian.show', $pengabdian) }}" class="inline-flex items-center px-4 py-2 rounded-md bg-gray-600 text-white hover:bg-gray-700">
                    Lihat Detail
                </a>
                <a href="{{ route('dosen.pengabdian.index') }}" class="inline-flex items-center px-4 py-2 rounded-md bg-gray-600 text-white hover:bg-gray-700">
                    Kembali
                </a>
            </div>
        </div>

        <!-- Error Display -->
        @if($errors->has('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
                {{ $errors->first('error') }}
            </div>
        @endif

        <!-- Status Info -->
        <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded mb-4">
            <strong>Status Saat Ini:</strong> 
            <x-status-badge :status="$pengabdian->status" />
            @if($pengabdian->catatan_verifikasi)
                <div class="mt-2">
                    <strong>Catatan Admin:</strong> {{ $pengabdian->catatan_verifikasi }}
                </div>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <form method="POST" action="{{ route('dosen.pengabdian.update', $pengabdian) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="space-y-6">
                    <div>
                        <label for="judul" class="block text-sm font-medium text-gray-700">Judul Pengabdian</label>
                        <input type="text" name="judul" id="judul" value="{{ old('judul', $pengabdian->judul) }}" class="mt-1 block w-full rounded-md border-gray-300" required>
                        @error('judul')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tahun" class="block text-sm font-medium text-gray-700">Tahun</label>
                        <input type="number" name="tahun" id="tahun" value="{{ old('tahun', $pengabdian->tahun) }}" min="2020" max="2030" class="mt-1 block w-full rounded-md border-gray-300" required>
                        @error('tahun')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tim_pelaksana" class="block text-sm font-medium text-gray-700">Tim Pelaksana</label>
                        <textarea name="tim_pelaksana" id="tim_pelaksana" rows="3" class="mt-1 block w-full rounded-md border-gray-300" placeholder="Masukkan nama-nama pelaksana, dipisahkan dengan koma" required>{{ old('tim_pelaksana', $pengabdian->tim_pelaksana) }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Contoh: Dr. John Doe, Prof. Jane Smith, M.Sc. Bob Wilson</p>
                        @error('tim_pelaksana')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="lokasi" class="block text-sm font-medium text-gray-700">Lokasi</label>
                        <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi', $pengabdian->lokasi) }}" class="mt-1 block w-full rounded-md border-gray-300" placeholder="Contoh: Desa ABC, Kecamatan XYZ, Kabupaten DEF" required>
                        @error('lokasi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="mitra" class="block text-sm font-medium text-gray-700">Mitra</label>
                        <input type="text" name="mitra" id="mitra" value="{{ old('mitra', $pengabdian->mitra) }}" class="mt-1 block w-full rounded-md border-gray-300" placeholder="Contoh: Pemerintah Desa ABC, Karang Taruna, dll" required>
                        @error('mitra')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Upload Dokumen Section -->
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Upload Dokumen</h3>
                        
                        <!-- Proposal File -->
                        <div class="mb-4">
                            <label for="proposal_file" class="block text-sm font-medium text-gray-700">
                                File Proposal 
                                @if($pengabdian->requiresProposal())
                                    <span class="text-red-500">*</span>
                                @endif
                            </label>
                            @php
                                $proposalDoc = $pengabdian->documents()->where('jenis_dokumen', 'proposal')->first();
                            @endphp
                            @if($proposalDoc)
                                <div class="mt-2 mb-2 flex items-center justify-between p-2 bg-green-50 border border-green-200 rounded-md">
                                    <span class="text-sm text-green-700">File sudah diupload: {{ $proposalDoc->nama_file }}</span>
                                    @if($pengabdian->status !== 'selesai')
                                        <form action="{{ route('dosen.pengabdian.delete-document', [$pengabdian, $proposalDoc]) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus dokumen ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 flex items-center gap-1 text-xs">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                            <input type="file" name="proposal_file" id="proposal_file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept=".pdf,.doc,.docx">
                            <p class="mt-1 text-sm text-gray-500">Format: PDF, DOC, DOCX (maksimal 10MB)</p>
                            @error('proposal_file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Laporan Akhir File (conditional) -->
                        @if($pengabdian->requiresFinalDocuments())
                            <div class="mb-4">
                                <label for="laporan_akhir_file" class="block text-sm font-medium text-gray-700">
                                    File Laporan Akhir 
                                    @if(!$pengabdian->documents()->where('jenis_dokumen', 'laporan_akhir')->exists())
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>
                                @php
                                    $laporanDoc = $pengabdian->documents()->where('jenis_dokumen', 'laporan_akhir')->first();
                                @endphp
                                @if($laporanDoc)
                                    <div class="mt-2 mb-2 flex items-center justify-between p-2 bg-green-50 border border-green-200 rounded-md">
                                        <span class="text-sm text-green-700">File sudah diupload: {{ $laporanDoc->nama_file }}</span>
                                        @if($pengabdian->status !== 'selesai')
                                            <form action="{{ route('dosen.pengabdian.delete-document', [$pengabdian, $laporanDoc]) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus dokumen ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 flex items-center gap-1 text-xs">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @endif
                                <input type="file" name="laporan_akhir_file" id="laporan_akhir_file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept=".pdf,.doc,.docx">
                                <p class="mt-1 text-sm text-gray-500">Format: PDF, DOC, DOCX (maksimal 10MB)</p>
                                @error('laporan_akhir_file')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        <!-- Dokumen Pendukung (optional) -->
                        <div class="mb-4">
                            <label for="dokumen_pendukung" class="block text-sm font-medium text-gray-700">Dokumen Pendukung (Opsional)</label>
                            @if($pengabdian->documents()->where('jenis_dokumen', 'dokumen_pendukung')->exists())
                                <div class="mt-2 mb-2 space-y-2">
                                    <p class="text-sm font-medium text-gray-700">
                                        Dokumen pendukung yang sudah diupload:
                                    </p>
                                    @foreach($pengabdian->documents()->where('jenis_dokumen', 'dokumen_pendukung')->get() as $doc)
                                        <div class="flex items-center justify-between p-2 bg-green-50 border border-green-200 rounded-md">
                                            <span class="text-sm text-green-700">{{ $doc->nama_file }}</span>
                                            @if($pengabdian->status !== 'selesai')
                                                <form action="{{ route('dosen.pengabdian.delete-document', [$pengabdian, $doc]) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus dokumen ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 flex items-center gap-1 text-xs">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <input type="file" name="dokumen_pendukung[]" id="dokumen_pendukung" multiple class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept=".pdf,.doc,.docx">
                            <p class="mt-1 text-sm text-gray-500">Format: PDF, DOC, DOCX (maksimal 10MB per file)</p>
                            @error('dokumen_pendukung.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-8">
                    <a href="{{ route('dosen.pengabdian.show', $pengabdian) }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">Batal</a>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-md">Update</button>
                </div>
            </form>
        </div>

        <!-- Upload Versi Baru Dokumen -->
        @php
            $proposalDoc = $pengabdian->documents()->where('jenis_dokumen', 'proposal')->first();
            $laporanDoc = $pengabdian->documents()->where('jenis_dokumen', 'laporan_akhir')->first();
        @endphp

        @if($proposalDoc || $laporanDoc)
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center mb-4">
                <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                <h2 class="text-lg font-semibold text-gray-900">Upload Versi Baru Dokumen</h2>
            </div>
            <p class="text-sm text-gray-600 mb-6">Gunakan form di bawah untuk mengupload versi baru dari dokumen yang sudah ada. Versi lama akan disimpan dalam riwayat.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if($proposalDoc)
                    <x-upload-version-form :document="$proposalDoc" type="pengabdian" />
                @endif

                @if($laporanDoc)
                    <x-upload-version-form :document="$laporanDoc" type="pengabdian" />
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Modals -->
    <x-pdf-preview-modal />
    <x-version-history-modal />
</x-layouts.dosen>
