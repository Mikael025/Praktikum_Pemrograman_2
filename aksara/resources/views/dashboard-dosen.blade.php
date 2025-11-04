<x-layouts.dosen>
    <div class="space-y-6">
        <!-- Welcome -->
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-2xl md:text-3xl font-semibold text-gray-900">Selamat Datang, {{ auth()->user()->name ?? 'Dosen' }}</h1>
            <p class="mt-2 text-gray-600">Ringkasan aktivitas penelitian dan pengabdian Anda ditampilkan di bawah.</p>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-4">
            <x-filter-bar :years="range((int)date('Y'), (int)date('Y')-5)" />
        </div>

        <!-- Statistik Penelitian -->
        <section class="space-y-3">
            <h2 class="text-lg font-semibold text-gray-900">Statistik Penelitian Saya</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <x-stat-card title="Jumlah Penelitian Diusulkan" :value="$penelitianStats['diusulkan']" color="blue" />
                <x-stat-card title="Jumlah Penelitian Tidak Lolos" :value="$penelitianStats['tidak_lolos']" color="red" />
                <x-stat-card title="Jumlah Penelitian Lolos" :value="$penelitianStats['lolos']" color="green" />
                <x-stat-card title="Jumlah Penelitian Selesai" :value="$penelitianStats['selesai']" color="emerald" />
            </div>
        </section>

        <!-- Statistik Pengabdian -->
        <section class="space-y-3">
            <h2 class="text-lg font-semibold text-gray-900">Statistik Pengabdian Masyarakat Saya</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <x-stat-card title="Jumlah Pengabdian Diusulkan" :value="$pengabdianStats['diusulkan']" color="blue" />
                <x-stat-card title="Jumlah Pengabdian Tidak Lolos" :value="$pengabdianStats['tidak_lolos']" color="red" />
                <x-stat-card title="Jumlah Pengabdian Lolos" :value="$pengabdianStats['lolos']" color="green" />
                <x-stat-card title="Jumlah Pengabdian Selesai" :value="$pengabdianStats['selesai']" color="emerald" />
            </div>
        </section>
    </div>
</x-layouts.dosen>


