<x-layouts.dosen>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Edit Pengabdian</h1>
            <div class="flex space-x-3">
                <a href="{{ route('dosen.pengabdian.show', $pengabdian) }}" class="inline-flex items-center px-4 py-2 rounded-md bg-gray-600 text-white hover:bg-gray-700">
                    Lihat Detail
                </a>
                <a href="{{ route('dosen.pengabdian.index') }}" class="inline-flex items-center px-4 py-2 rounded-md bg-gray-600 text-white hover:bg-gray-700">
                    Kembali
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <form method="POST" action="{{ route('dosen.pengabdian.update', $pengabdian) }}">
                @csrf
                @method('PUT')
                <div class="space-y-6">
                    <div>
                        <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Judul Pengabdian</label>
                        <input type="text" name="judul" id="judul" value="{{ old('judul', $pengabdian->judul) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-gray-100" required>
                        @error('judul')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tahun" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tahun</label>
                        <input type="number" name="tahun" id="tahun" value="{{ old('tahun', $pengabdian->tahun) }}" min="2020" max="2030" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-gray-100" required>
                        @error('tahun')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tim_pelaksana" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tim Pelaksana</label>
                        <textarea name="tim_pelaksana" id="tim_pelaksana" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-gray-100" placeholder="Masukkan nama-nama pelaksana, dipisahkan dengan koma" required>{{ old('tim_pelaksana', is_array($pengabdian->tim_pelaksana) ? implode(', ', $pengabdian->tim_pelaksana) : $pengabdian->tim_pelaksana) }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Contoh: Dr. John Doe, Prof. Jane Smith, M.Sc. Bob Wilson</p>
                        @error('tim_pelaksana')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="lokasi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Lokasi</label>
                        <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi', $pengabdian->lokasi) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-gray-100" placeholder="Contoh: Desa ABC, Kecamatan XYZ, Kabupaten DEF" required>
                        @error('lokasi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="mitra" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mitra</label>
                        <input type="text" name="mitra" id="mitra" value="{{ old('mitra', $pengabdian->mitra) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-gray-100" placeholder="Contoh: Pemerintah Desa ABC, Karang Taruna, dll" required>
                        @error('mitra')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
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
