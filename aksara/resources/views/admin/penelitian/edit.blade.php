<x-layouts.admin>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Edit Penelitian</h1>
                <p class="mt-1 text-sm text-gray-600">Edit informasi penelitian</p>
            </div>
            <a href="{{ route('penelitian.show', $penelitian) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                Kembali
            </a>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('penelitian.update', $penelitian) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="judul" class="block text-sm font-medium text-gray-700">Judul Penelitian <span class="text-red-500">*</span></label>
                        <input type="text" id="judul" name="judul" value="{{ old('judul', $penelitian->judul) }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @error('judul')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tahun" class="block text-sm font-medium text-gray-700">Tahun <span class="text-red-500">*</span></label>
                        <input type="number" id="tahun" name="tahun" value="{{ old('tahun', $penelitian->tahun) }}" min="2020" max="{{ date('Y') + 5 }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @error('tahun')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                        <select id="status" name="status" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="diusulkan" {{ old('status', $penelitian->status) === 'diusulkan' ? 'selected' : '' }}>Diusulkan</option>
                            <option value="tidak_lolos" {{ old('status', $penelitian->status) === 'tidak_lolos' ? 'selected' : '' }}>Tidak Lolos</option>
                            <option value="lolos_perlu_revisi" {{ old('status', $penelitian->status) === 'lolos_perlu_revisi' ? 'selected' : '' }}>Lolos Perlu Revisi</option>
                            <option value="lolos" {{ old('status', $penelitian->status) === 'lolos' ? 'selected' : '' }}>Lolos</option>
                            <option value="revisi_pra_final" {{ old('status', $penelitian->status) === 'revisi_pra_final' ? 'selected' : '' }}>Revisi Pra Final</option>
                            <option value="selesai" {{ old('status', $penelitian->status) === 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="catatan_verifikasi" class="block text-sm font-medium text-gray-700">Catatan Verifikasi <span class="text-red-500">*</span></label>
                        <textarea id="catatan_verifikasi" name="catatan_verifikasi" rows="3" required
                                  placeholder="Jelaskan alasan perubahan status (minimal 10 karakter)"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('catatan_verifikasi', $penelitian->catatan_verifikasi) }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Catatan wajib diisi saat mengubah status penelitian</p>
                        @error('catatan_verifikasi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="tim_peneliti" class="block text-sm font-medium text-gray-700">Tim Peneliti <span class="text-red-500">*</span></label>
                        <textarea id="tim_peneliti" name="tim_peneliti" rows="3" required
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('tim_peneliti', $penelitian->tim_peneliti) }}</textarea>
                        @error('tim_peneliti')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="sumber_dana" class="block text-sm font-medium text-gray-700">Sumber Dana <span class="text-red-500">*</span></label>
                        <input type="text" id="sumber_dana" name="sumber_dana" value="{{ old('sumber_dana', $penelitian->sumber_dana) }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @error('sumber_dana')
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
