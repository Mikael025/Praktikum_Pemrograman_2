<x-layouts.admin>
    @php($isAdmin = auth()->user() && auth()->user()->role === 'admin')

    <div class="space-y-6">
        <!-- Welcome -->
        <div class="bg-white rounded-2xl shadow-md p-6 border border-slate-100">
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900">Selamat Datang, {{ auth()->user()->name ?? 'Admin' }}</h1>
            <p class="mt-2 text-slate-600">Dashboard administrasi untuk mengelola kegiatan penelitian dan pengabdian masyarakat.</p>
        </div>

        <!-- Alerts & Warnings -->
        @if(count($alerts) > 0)
        <div class="space-y-3">
            @foreach($alerts as $alert)
            <div class="bg-{{ $alert['type'] === 'warning' ? 'red' : 'blue' }}-50 border-l-4 border-{{ $alert['type'] === 'warning' ? 'red' : 'blue' }}-400 p-4 rounded-lg shadow">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-{{ $alert['type'] === 'warning' ? 'red' : 'blue' }}-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            @if($alert['type'] === 'warning')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            @endif
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-{{ $alert['type'] === 'warning' ? 'red' : 'blue' }}-800">{{ $alert['message'] }}</p>
                        <p class="mt-1 text-sm text-{{ $alert['type'] === 'warning' ? 'red' : 'blue' }}-700">{{ $alert['action'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Quick Admin Actions -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
            <a href="{{ route('penelitian.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-md hover:shadow-lg p-4 flex flex-col items-center justify-center transition-all transform hover:scale-105">
                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span class="font-semibold text-xs text-center">Kelola Penelitian</span>
            </a>
            <a href="{{ route('pengabdian.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-md hover:shadow-lg p-4 flex flex-col items-center justify-center transition-all transform hover:scale-105">
                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span class="font-semibold text-xs text-center">Kelola Pengabdian</span>
            </a>
            <a href="{{ route('admin.informasi.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-md hover:shadow-lg p-4 flex flex-col items-center justify-center transition-all transform hover:scale-105">
                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
                <span class="font-semibold text-xs text-center">Buat Berita</span>
            </a>
            <a href="{{ route('admin.laporan.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-md hover:shadow-lg p-4 flex flex-col items-center justify-center transition-all transform hover:scale-105">
                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="font-semibold text-xs text-center">Lihat Laporan</span>
            </a>
            <a href="{{ route('admin.laporan.perbandingan') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-md hover:shadow-lg p-4 flex flex-col items-center justify-center transition-all transform hover:scale-105">
                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span class="font-semibold text-xs text-center">Analitik</span>
            </a>
        </div>

        <!-- Enhanced System Overview -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 text-white rounded-2xl shadow-lg p-6 border border-indigo-400/20">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-indigo-100 text-sm font-medium">Total Dosen</p>
                        <p class="text-3xl font-bold mt-2">{{ $enhancedStats['total_dosen'] }}</p>
                        <p class="text-indigo-100 text-xs mt-1">Terdaftar di sistem</p>
                    </div>
                    <div class="bg-indigo-400/30 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-slate-400 to-slate-500 text-white rounded-2xl shadow-lg p-6 border border-slate-300/20">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-100 text-sm font-medium">Total Kegiatan</p>
                        <p class="text-3xl font-bold mt-2">{{ $enhancedStats['total_kegiatan'] }}</p>
                        <p class="text-slate-100 text-xs mt-1">{{ $enhancedStats['total_penelitian'] }} Penelitian, {{ $enhancedStats['total_pengabdian'] }} Pengabdian</p>
                    </div>
                    <div class="bg-slate-300/30 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-2xl shadow-lg p-6 border border-green-400/20">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Selesai</p>
                        <p class="text-3xl font-bold mt-2">{{ $enhancedStats['penelitian_selesai'] + $enhancedStats['pengabdian_selesai'] }}</p>
                        <p class="text-green-100 text-xs mt-1">Penelitian & Pengabdian</p>
                    </div>
                    <div class="bg-green-400/30 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-amber-500 to-amber-600 text-white rounded-2xl shadow-lg p-6 border border-amber-400/20">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-amber-100 text-sm font-medium">Tingkat Keberhasilan</p>
                        <p class="text-3xl font-bold mt-2">{{ $enhancedStats['avg_success_rate'] }}%</p>
                        <p class="text-amber-100 text-xs mt-1">Lolos & Selesai</p>
                    </div>
                    <div class="bg-amber-400/30 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Verification Queue Widget -->
        <div class="bg-white rounded-2xl shadow-md border border-slate-100">
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Antrian Verifikasi</h2>
                    <p class="text-sm text-slate-600 mt-1">Kegiatan yang menunggu persetujuan Anda</p>
                </div>
                @if($verificationQueue->count() > 0)
                <span class="bg-red-100 text-red-800 text-xs font-semibold px-3 py-1 rounded-full">
                    {{ $verificationQueue->count() }} pending
                </span>
                @endif
            </div>
            <div class="p-6">
                @if($verificationQueue->count() > 0)
                <div class="space-y-3">
                    @foreach($verificationQueue->take(10) as $item)
                    <div class="flex items-start gap-4 p-4 border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                        <div class="flex-shrink-0">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full {{ $item['type'] === 'penelitian' ? 'bg-indigo-100 text-indigo-700' : 'bg-emerald-100 text-emerald-700' }}">
                                {{ $item['type'] === 'penelitian' ? 'P' : 'PM' }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-slate-900 truncate">{{ $item['title'] }}</h4>
                            <p class="text-xs text-slate-600 mt-1">Dosen: {{ $item['dosen'] }}</p>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $item['type'] === 'penelitian' ? 'bg-indigo-100 text-indigo-700' : 'bg-emerald-100 text-emerald-700' }}">
                                    {{ ucfirst($item['type']) }}
                                </span>
                                @if($item['days_pending'] > 7)
                                <span class="text-xs px-2 py-0.5 rounded-full bg-red-100 text-red-700">
                                    {{ $item['days_pending'] }} hari tertunda
                                </span>
                                @else
                                <span class="text-xs text-slate-500">
                                    {{ $item['submitted_at']->diffForHumans() }}
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="flex-shrink-0 flex gap-2">
                            <a href="{{ route($item['type'] . '.show', $item['id']) }}" class="inline-flex items-center px-3 py-2 border border-slate-300 shadow-sm text-xs font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50">
                                Tinjau
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="mt-2 text-sm text-slate-500">Tidak ada kegiatan yang menunggu verifikasi</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Recent Admin Actions -->
        <div class="bg-white rounded-2xl shadow-md border border-slate-100">
            <div class="px-6 py-4 border-b border-slate-200">
                <h2 class="text-lg font-semibold text-slate-900">Aktivitas Admin Terkini</h2>
                <p class="text-sm text-slate-600 mt-1">Riwayat tindakan verifikasi terbaru</p>
            </div>
            <div class="p-6">
                @if($recentActions->count() > 0)
                <div class="space-y-3">
                    @foreach($recentActions as $action)
                    <div class="flex items-start gap-3 p-3 hover:bg-slate-50 rounded-lg transition-colors">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-8 h-8 rounded-full bg-{{ 
                                $action['action'] === 'Menyetujui' ? 'emerald' : 
                                ($action['action'] === 'Menolak' ? 'red' : 'amber')
                            }}-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-{{ 
                                    $action['action'] === 'Menyetujui' ? 'emerald' : 
                                    ($action['action'] === 'Menolak' ? 'red' : 'amber')
                                }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($action['action'] === 'Menyetujui')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    @elseif($action['action'] === 'Menolak')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    @endif
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-slate-900">
                                <span class="font-medium">{{ $action['action'] }}</span> 
                                {{ $action['type'] }} oleh 
                                <span class="font-medium">{{ $action['dosen'] }}</span>
                            </p>
                            <p class="text-xs text-slate-600 truncate mt-1">{{ $action['title'] }}</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $action['type'] === 'penelitian' ? 'bg-indigo-100 text-indigo-700' : 'bg-emerald-100 text-emerald-700' }}">
                                    {{ ucfirst($action['type']) }}
                                </span>
                            </div>
                        </div>
                        <div class="flex-shrink-0 text-right">
                            <p class="text-xs text-slate-500">{{ $action['timestamp']->diffForHumans() }}</p>
                            <a href="{{ route($action['type'] . '.show', $action['id']) }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                                Lihat →
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-center text-slate-500 py-8">Belum ada aktivitas admin</p>
                @endif
            </div>
        </div>
    </div>
</x-layouts.admin>