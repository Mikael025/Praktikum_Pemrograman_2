<x-layouts.admin>
    @php($isAdmin = auth()->user() && auth()->user()->role === 'admin')

    <div class="space-y-6">
        <!-- Welcome -->
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-2xl md:text-3xl font-semibold text-gray-900">Selamat Datang, {{ auth()->user()->name ?? 'Admin' }}</h1>
            <p class="mt-2 text-gray-600">Ringkasan aktivitas terbaru Anda ditampilkan di bawah.</p>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-4">
            <x-filter-bar :years="range((int)date('Y'), (int)date('Y')-5)" />
        </div>

        <!-- Statistik Penelitian -->
        <section class="space-y-3">
            <h2 class="text-lg font-semibold text-gray-900">Statistik Kegiatan Penelitian</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <x-stat-card title="Jumlah Penelitian Diusulkan" :value="$penelitianStats['diusulkan']" />
                <x-stat-card title="Jumlah Penelitian Tidak Lolos" :value="$penelitianStats['tidak_lolos']" />
                <x-stat-card title="Jumlah Penelitian Lolos" :value="$penelitianStats['lolos']" />
                <x-stat-card title="Jumlah Penelitian Selesai" :value="$penelitianStats['selesai']" />
            </div>
        </section>

        <!-- Statistik Pengabdian -->
        <section class="space-y-3">
            <h2 class="text-lg font-semibold text-gray-900">Statistik Kegiatan Pengabdian Masyarakat</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <x-stat-card title="Jumlah Pengabdian Diusulkan" :value="$pengabdianStats['diusulkan']" />
                <x-stat-card title="Jumlah Pengabdian Tidak Lolos" :value="$pengabdianStats['tidak_lolos']" />
                <x-stat-card title="Jumlah Pengabdian Lolos" :value="$pengabdianStats['lolos']" />
                <x-stat-card title="Jumlah Pengabdian Selesai" :value="$pengabdianStats['selesai']" />
            </div>
        </section>
    </div>

</x-layouts.admin>