<x-layouts.admin>
    @php($isAdmin = auth()->user() && auth()->user()->role === 'admin')

    <div class="space-y-6">
        <!-- Welcome -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h1 class="text-2xl md:text-3xl font-semibold text-gray-900 dark:text-white">Selamat Datang, {{ auth()->user()->name ?? 'Admin' }}</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-300">Ringkasan aktivitas terbaru Anda ditampilkan di bawah.</p>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <x-filter-bar :years="range((int)date('Y'), (int)date('Y')-5)" />
        </div>

        <!-- Statistik Penelitian -->
        <section class="space-y-3">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Statistik Kegiatan Penelitian</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <x-stat-card title="Total Penelitian Aktif" :value="$penelitianStats['total_aktif']" />
                @if($isAdmin)
                    <x-stat-card title="Dokumen Menunggu Verifikasi" :value="$penelitianStats['menunggu_verifikasi']" />
                    <x-stat-card title="Dokumen Selesai Diverifikasi" :value="$penelitianStats['selesai_diverifikasi']" />
                @endif
                <x-stat-card title="Jumlah Dokumen Ditolak" :value="$penelitianStats['ditolak']" />
            </div>
        </section>

        <!-- Statistik Pengabdian -->
        <section class="space-y-3">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Statistik Kegiatan Pengabdian Masyarakat</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <x-stat-card title="Total Pengabdian Aktif" :value="$pengabdianStats['total_aktif']" />
                @if($isAdmin)
                    <x-stat-card title="Dokumen Menunggu Verifikasi" :value="$pengabdianStats['menunggu_verifikasi']" />
                    <x-stat-card title="Dokumen Selesai Diverifikasi" :value="$pengabdianStats['selesai_diverifikasi']" />
                @endif
                <x-stat-card title="Jumlah Dokumen Ditolak" :value="$pengabdianStats['ditolak']" />
            </div>
        </section>
    </div>

</x-layouts.admin>