<x-layouts.dosen>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Pengabdian Masyarakat Saya</h1>
            <a href="{{ route('dosen.pengabdian.create') }}" class="inline-flex items-center px-4 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
                Tambah Pengabdian Baru
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <x-filter-bar :years="range((int)date('Y'), (int)date('Y')-5)" :show-status="true" />
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Judul</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tahun</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tim Pelaksana</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Lokasi</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Mitra</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                        @forelse($pengabdian as $item)
                            <tr>
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $item->judul }}</td>
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $item->tahun }}</td>
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100">
                                    @if(is_array($item->tim_pelaksana))
                                        {{ implode(', ', $item->tim_pelaksana) }}
                                    @else
                                        {{ $item->tim_pelaksana }}
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $item->lokasi }}</td>
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $item->mitra }}</td>
                                <td class="px-4 py-3">
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
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs {{ $statusColors[$item->status] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ $statusLabels[$item->status] ?? ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right space-x-2">
                                    <a href="{{ route('dosen.pengabdian.show', $item) }}" class="text-indigo-600 hover:text-indigo-800">Lihat</a>
                                    <a href="{{ route('dosen.pengabdian.edit', $item) }}" class="text-yellow-600 hover:text-yellow-700">Edit</a>
                                    <button onclick="uploadDocument({{ $item->id }})" class="text-emerald-600 hover:text-emerald-700">Upload</button>
                                    <form method="POST" action="{{ route('dosen.pengabdian.destroy', $item) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengabdian ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                    Belum ada data pengabdian. <a href="{{ route('dosen.pengabdian.create') }}" class="text-indigo-600 hover:text-indigo-800">Tambah pengabdian baru</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Upload Dokumen -->
    <div id="uploadModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Upload Dokumen</h3>
                    <form id="uploadForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jenis Dokumen</label>
                                <select name="jenis_dokumen" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-gray-100" required>
                                    <option value="">Pilih jenis dokumen</option>
                                    <option value="proposal">Proposal</option>
                                    <option value="laporan_akhir">Laporan Akhir</option>
                                    <option value="sertifikat">Sertifikat</option>
                                    <option value="dokumen_pendukung">Dokumen Pendukung</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">File</label>
                                <input type="file" name="file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" accept=".pdf,.doc,.docx" required>
                            </div>
                        </div>
                        <div class="flex justify-end space-x-3 mt-6">
                            <button type="button" onclick="closeUploadModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">Batal</button>
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-md">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function uploadDocument(pengabdianId) {
            document.getElementById('uploadForm').action = `/dosen/pengabdian/${pengabdianId}/upload-document`;
            document.getElementById('uploadModal').classList.remove('hidden');
        }

        function closeUploadModal() {
            document.getElementById('uploadModal').classList.add('hidden');
        }
    </script>
</x-layouts.dosen>
