<x-layouts.dosen>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900">Tambah Pengabdian Baru</h1>
            <a href="{{ route('dosen.pengabdian.index') }}" class="inline-flex items-center px-4 py-2 rounded-md bg-gray-600 text-white hover:bg-gray-700">
                Kembali
            </a>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <form method="POST" action="{{ route('dosen.pengabdian.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label for="judul" class="block text-sm font-medium text-gray-700">Judul Pengabdian</label>
                        <input type="text" name="judul" id="judul" value="{{ old('judul') }}" class="mt-1 block w-full rounded-md border-gray-300" required>
                        @error('judul')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tahun" class="block text-sm font-medium text-gray-700">Tahun</label>
                        <input type="number" name="tahun" id="tahun" value="{{ old('tahun', date('Y')) }}" min="2020" max="2030" class="mt-1 block w-full rounded-md border-gray-300" required>
                        @error('tahun')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tim_pelaksana" class="block text-sm font-medium text-gray-700">Tim Pelaksana</label>
                        <textarea name="tim_pelaksana" id="tim_pelaksana" rows="3" class="mt-1 block w-full rounded-md border-gray-300" placeholder="Masukkan nama-nama pelaksana, dipisahkan dengan koma" required>{{ old('tim_pelaksana') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Contoh: Dr. John Doe, Prof. Jane Smith, M.Sc. Bob Wilson</p>
                        @error('tim_pelaksana')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="lokasi" class="block text-sm font-medium text-gray-700">Lokasi</label>
                        <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi') }}" class="mt-1 block w-full rounded-md border-gray-300" placeholder="Contoh: Desa ABC, Kecamatan XYZ, Kabupaten DEF" required>
                        @error('lokasi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="mitra" class="block text-sm font-medium text-gray-700">Mitra</label>
                        <input type="text" name="mitra" id="mitra" value="{{ old('mitra') }}" class="mt-1 block w-full rounded-md border-gray-300" placeholder="Contoh: Pemerintah Desa ABC, Karang Taruna, dll" required>
                        @error('mitra')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- File Upload Section -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Upload Dokumen</h3>
                        
                        <!-- Proposal File (Required) -->
                        <div class="mb-6">
                            <label for="proposal_file" class="block text-sm font-medium text-gray-700">
                                File Proposal <span class="text-red-500">*</span>
                            </label>
                            <input type="file" name="proposal_file" id="proposal_file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept=".pdf,.doc,.docx" required>
                            <p class="mt-1 text-sm text-gray-500">Format: PDF, DOC, DOCX (Maksimal 10MB)</p>
                            @error('proposal_file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Supporting Documents (Optional) -->
                        <div class="mb-6">
                            <label for="dokumen_pendukung" class="block text-sm font-medium text-gray-700">
                                Dokumen Pendukung (Opsional)
                            </label>
                            <input type="file" name="dokumen_pendukung[]" id="dokumen_pendukung" multiple class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100" accept=".pdf,.doc,.docx">
                            <p class="mt-1 text-sm text-gray-500">Format: PDF, DOC, DOCX (Maksimal 10MB per file)</p>
                            @error('dokumen_pendukung.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Error Display -->
                    @if($errors->has('error'))
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                            {{ $errors->first('error') }}
                        </div>
                    @endif
                </div>

                <div class="flex justify-end space-x-3 mt-8">
                    <a href="{{ route('dosen.pengabdian.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">Batal</a>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-md">Simpan & Ajukan</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.dosen>
