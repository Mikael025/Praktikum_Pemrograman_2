<x-layouts.admin>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Edit Penelitian</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Edit informasi penelitian</p>
            </div>
            <a href="{{ route('penelitian.show', $penelitian) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                Kembali
            </a>
        </div>

        <!-- Form -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <form action="{{ route('penelitian.update', $penelitian) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Judul Penelitian <span class="text-red-500">*</span></label>
                        <input type="text" id="judul" name="judul" value="{{ old('judul', $penelitian->judul) }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @error('judul')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tahun" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tahun <span class="text-red-500">*</span></label>
                        <input type="number" id="tahun" name="tahun" value="{{ old('tahun', $penelitian->tahun) }}" min="2020" max="{{ date('Y') + 5 }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @error('tahun')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status <span class="text-red-500">*</span></label>
                        <select id="status" name="status" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="draft" {{ old('status', $penelitian->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="menunggu_verifikasi" {{ old('status', $penelitian->status) === 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                            <option value="terverifikasi" {{ old('status', $penelitian->status) === 'terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
                            <option value="ditolak" {{ old('status', $penelitian->status) === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            <option value="berjalan" {{ old('status', $penelitian->status) === 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                            <option value="selesai" {{ old('status', $penelitian->status) === 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="tim_peneliti" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tim Peneliti <span class="text-red-500">*</span></label>
                        <textarea id="tim_peneliti" name="tim_peneliti" rows="3" required
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('tim_peneliti', $penelitian->tim_peneliti) }}</textarea>
                        @error('tim_peneliti')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="sumber_dana" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sumber Dana <span class="text-red-500">*</span></label>
                        <input type="text" id="sumber_dana" name="sumber_dana" value="{{ old('sumber_dana', $penelitian->sumber_dana) }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @error('sumber_dana')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="catatan_verifikasi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Catatan Verifikasi</label>
                        <textarea id="catatan_verifikasi" name="catatan_verifikasi" rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('catatan_verifikasi', $penelitian->catatan_verifikasi) }}</textarea>
                        @error('catatan_verifikasi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('penelitian.show', $penelitian) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
