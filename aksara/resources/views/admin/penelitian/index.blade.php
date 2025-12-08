<x-layouts.admin>
    @php($isAdmin = auth()->user() && auth()->user()->role === 'admin')

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-bold text-gray-900">Statistik Penelitian</h1>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <x-filter-bar :years="range((int)date('Y'), (int)date('Y')-5)" :show-status="true" />
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Statistik Penelitian -->
        <section class="space-y-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <x-stat-card title="Jumlah Penelitian Diusulkan" :value="$penelitianStats['diusulkan']" color="blue" />
                <x-stat-card title="Jumlah Penelitian Tidak Lolos" :value="$penelitianStats['tidak_lolos']" color="red" />
                <x-stat-card title="Jumlah Penelitian Lolos" :value="$penelitianStats['lolos']" color="green" />
                <x-stat-card title="Jumlah Penelitian Selesai" :value="$penelitianStats['selesai']" color="emerald" />
            </div>
        </section>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dosen</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sumber Dana</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($penelitian as $item)
                        <tr>
                            <td class="px-4 py-3 text-gray-900">
                                <div class="max-w-xs truncate" title="{{ $item->judul }}">
                                    {{ $item->judul }}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-900">{{ $item->tahun }}</td>
                            <td class="px-4 py-3 text-gray-900">{{ $item->user->name }}</td>
                            <td class="px-4 py-3 text-gray-900">{{ $item->sumber_dana }}</td>
                            <td class="px-4 py-3">
                                <x-status-badge :status="$item->status" />
                            </td>
                            <td class="px-4 py-3 text-right space-x-2">
                                <a href="{{ route('penelitian.show', $item) }}" class="text-indigo-600 hover:text-indigo-800">Lihat</a>
                                <a href="{{ route('penelitian.edit', $item) }}" class="text-yellow-600 hover:text-yellow-700">Edit</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                Belum ada data penelitian.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($penelitian->hasPages())
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $penelitian->links() }}
            </div>
            @endif
        </div>
    </div>

</x-layouts.admin>


