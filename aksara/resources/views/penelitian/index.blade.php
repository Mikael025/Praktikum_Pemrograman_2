<x-layouts.admin>
    @php($isAdmin = auth()->user() && auth()->user()->role === 'admin')

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Penelitian</h1>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <x-filter-bar :years="range((int)date('Y'), (int)date('Y')-5)" :show-status="true" />
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Judul</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tahun</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Dosen</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Sumber Dana</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                        @forelse($penelitian as $item)
                        <tr>
                            <td class="px-4 py-3 text-gray-900 dark:text-gray-100">
                                <div class="max-w-xs truncate" title="{{ $item->judul }}">
                                    {{ $item->judul }}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $item->tahun }}</td>
                            <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $item->user->name }}</td>
                            <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $item->sumber_dana }}</td>
                            <td class="px-4 py-3">
                                <x-status-badge :status="$item->status" />
                            </td>
                            <td class="px-4 py-3 text-right space-x-2">
                                <a href="{{ route('penelitian.show', $item) }}" class="text-indigo-600 hover:text-indigo-800">Lihat</a>
                                <a href="{{ route('penelitian.edit', $item) }}" class="text-yellow-600 hover:text-yellow-700">Edit</a>
                                <form action="{{ route('penelitian.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus penelitian ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700">Hapus</button>
                                </form>
                                @if($item->status === 'menunggu_verifikasi')
                                    <a href="{{ route('penelitian.show', $item) }}" class="inline-flex items-center px-3 py-1 text-sm font-medium text-emerald-700 bg-emerald-100 border border-emerald-300 rounded-md hover:bg-emerald-200 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Verifikasi Dokumen
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                Belum ada data penelitian.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($penelitian->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $penelitian->links() }}
            </div>
            @endif
        </div>
    </div>

</x-layouts.admin>


