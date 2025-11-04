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
                        <textarea name="tim_pelaksana" id="tim_pelaksana" rows="3" class="mt-1 block w-full rounded-md border-gray-300" placeholder="Masukkan nama-nama pelaksana, dipisahkan dengan koma" required>{{ old('tim_pelaksana', is_array($pengabdian->tim_pelaksana) ? implode(', ', $pengabdian->tim_pelaksana) : $pengabdian->tim_pelaksana) }}</textarea>
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
                            @if($pengabdian->documents()->where('jenis_dokumen', 'proposal')->exists())
                                <div class="mt-2 p-3 bg-green-50 border border-green-200 rounded-md">
                                    <p class="text-sm text-green-700">
                                        <strong>File sudah diupload:</strong> 
                                        {{ $pengabdian->documents()->where('jenis_dokumen', 'proposal')->first()->nama_file }}
                                    </p>
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
                                @if($pengabdian->documents()->where('jenis_dokumen', 'laporan_akhir')->exists())
                                    <div class="mt-2 p-3 bg-green-50 border border-green-200 rounded-md">
                                        <p class="text-sm text-green-700">
                                            <strong>File sudah diupload:</strong> 
                                            {{ $pengabdian->documents()->where('jenis_dokumen', 'laporan_akhir')->first()->nama_file }}
                                        </p>
                                    </div>
                                @endif
                                <input type="file" name="laporan_akhir_file" id="laporan_akhir_file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept=".pdf,.doc,.docx">
                                <p class="mt-1 text-sm text-gray-500">Format: PDF, DOC, DOCX (maksimal 10MB)</p>
                                @error('laporan_akhir_file')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Sertifikat File (conditional) -->
                            <div class="mb-4">
                                <label for="sertifikat_file" class="block text-sm font-medium text-gray-700">
                                    File Sertifikat 
                                    @if(!$pengabdian->documents()->where('jenis_dokumen', 'sertifikat')->exists())
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>
                                @if($pengabdian->documents()->where('jenis_dokumen', 'sertifikat')->exists())
                                    <div class="mt-2 p-3 bg-green-50 border border-green-200 rounded-md">
                                        <p class="text-sm text-green-700">
                                            <strong>File sudah diupload:</strong> 
                                            {{ $pengabdian->documents()->where('jenis_dokumen', 'sertifikat')->first()->nama_file }}
                                        </p>
                                    </div>
                                @endif
                                <input type="file" name="sertifikat_file" id="sertifikat_file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept=".pdf,.doc,.docx">
                                <p class="mt-1 text-sm text-gray-500">Format: PDF, DOC, DOCX (maksimal 10MB)</p>
                                @error('sertifikat_file')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        <!-- Dokumen Pendukung (optional) -->
                        <div class="mb-4">
                            <label for="dokumen_pendukung" class="block text-sm font-medium text-gray-700">Dokumen Pendukung (Opsional)</label>
                            @if($pengabdian->documents()->where('jenis_dokumen', 'dokumen_pendukung')->exists())
                                <div class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-md">
                                    <p class="text-sm text-blue-700 mb-2">
                                        <strong>Dokumen pendukung yang sudah diupload:</strong>
                                    </p>
                                    <ul class="text-sm text-blue-600 list-disc list-inside">
                                        @foreach($pengabdian->documents()->where('jenis_dokumen', 'dokumen_pendukung')->get() as $doc)
                                            <li>{{ $doc->nama_file }}</li>
                                        @endforeach
                                    </ul>
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
    </div>
</x-layouts.dosen>
