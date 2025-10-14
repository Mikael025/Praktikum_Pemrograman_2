<x-layouts.dosen>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Tambah Penelitian Baru</h1>
            <a href="{{ route('dosen.penelitian.index') }}" class="inline-flex items-center px-4 py-2 rounded-md bg-gray-600 text-white hover:bg-gray-700">
                Kembali
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <form method="POST" action="{{ route('dosen.penelitian.store') }}">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Judul Penelitian</label>
                        <input type="text" name="judul" id="judul" value="{{ old('judul') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-gray-100" required>
                        @error('judul')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tahun" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tahun</label>
                        <input type="number" name="tahun" id="tahun" value="{{ old('tahun', date('Y')) }}" min="2020" max="2030" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-gray-100" required>
                        @error('tahun')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tim_peneliti" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tim Peneliti</label>
                        <textarea name="tim_peneliti" id="tim_peneliti" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-gray-100" placeholder="Masukkan nama-nama peneliti, dipisahkan dengan koma" required>{{ old('tim_peneliti') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Contoh: Dr. John Doe, Prof. Jane Smith, M.Sc. Bob Wilson</p>
                        @error('tim_peneliti')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sumber_dana" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sumber Dana</label>
                        <input type="text" name="sumber_dana" id="sumber_dana" value="{{ old('sumber_dana') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-gray-100" placeholder="Contoh: Hibah Penelitian Dasar, LPPM, dll" required>
                        @error('sumber_dana')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-8">
                    <a href="{{ route('dosen.penelitian.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">Batal</a>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-md">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.dosen>
