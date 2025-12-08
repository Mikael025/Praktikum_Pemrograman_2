<x-layouts.dosen>
    <div class="space-y-6">
        <h1 class="text-xl font-bold text-gray-900">Statistik Pengabdian Masyarakat Saya</h1>

        <div class="bg-white rounded-lg shadow p-4">
            <x-filter-bar :years="range((int)date('Y'), (int)date('Y')-5)" :show-status="true" />
        </div>

        <!-- Statistik Pengabdian -->
        <section class="space-y-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <x-stat-card title="Jumlah Pengabdian Diusulkan" :value="$pengabdianStats['diusulkan']" color="blue" />
                <x-stat-card title="Jumlah Pengabdian Tidak Lolos" :value="$pengabdianStats['tidak_lolos']" color="red" />
                <x-stat-card title="Jumlah Pengabdian Lolos" :value="$pengabdianStats['lolos']" color="green" />
                <x-stat-card title="Jumlah Pengabdian Selesai" :value="$pengabdianStats['selesai']" color="emerald" />
            </div>
        </section>


        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <div class="flex items-center justify-between py-4 pl-4">
                    <a href="{{ route('dosen.pengabdian.create') }}" class="inline-flex items-center px-4 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
                        Tambah Pengabdian Baru
                    </a>
                </div>

                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tim Pelaksana</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lokasi</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mitra</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($pengabdian as $item)
                            <tr>
                                <td class="px-4 py-3 text-gray-900">{{ $item->judul }}</td>
                                <td class="px-4 py-3 text-gray-900">{{ $item->tahun }}</td>
                                <td class="px-4 py-3 text-gray-900">{{ $item->tim_pelaksana }}</td>
                                <td class="px-4 py-3 text-gray-900">{{ $item->lokasi }}</td>
                                <td class="px-4 py-3 text-gray-900">{{ $item->mitra }}</td>
                                <td class="px-4 py-3">
                                    <x-status-badge :status="$item->status" />
                                </td>
                                <td class="px-4 py-3 text-right space-x-2">
                                    <a href="{{ route('dosen.pengabdian.show', $item) }}" class="text-indigo-600 hover:text-indigo-800">Lihat</a>
                                    
                                    @if(in_array($item->status, ['lolos', 'revisi_pra_final']))
                                        <a href="{{ route('dosen.pengabdian.edit', $item) }}" class="text-green-600 hover:text-green-700 font-medium">Finalisasi</a>
                                    @elseif($item->canBeEditedByDosen())
                                        <a href="{{ route('dosen.pengabdian.edit', $item) }}" class="text-yellow-600 hover:text-yellow-700">Edit</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                    Belum ada data pengabdian. <a href="{{ route('dosen.pengabdian.create') }}" class="text-indigo-600 hover:text-indigo-800">Tambah pengabdian baru</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pagination -->
        <div class="mt-4">
            {{ $pengabdian->links() }}
        </div>
    </div>

    <!-- Error Display -->
    @if($errors->has('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
            {{ $errors->first('error') }}
        </div>
    @endif
</x-layouts.dosen>
