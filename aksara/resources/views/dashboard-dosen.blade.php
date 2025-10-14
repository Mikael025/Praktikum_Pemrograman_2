<x-layouts.dosen>
    <div class="space-y-6">
        <!-- Welcome -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h1 class="text-2xl md:text-3xl font-semibold text-gray-900 dark:text-white">Selamat Datang, {{ auth()->user()->name ?? 'Dosen' }}</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-300">Ringkasan aktivitas penelitian dan pengabdian Anda ditampilkan di bawah.</p>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <x-filter-bar :years="range((int)date('Y'), (int)date('Y')-5)" />
        </div>

        <!-- Statistik Penelitian -->
        <section class="space-y-3">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Statistik Kegiatan Penelitian</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <x-stat-card title="Usulan Dokumen Penelitian" :value="$penelitianUsulan" color="blue" />
                <x-stat-card title="Dokumen Belum Lengkap" :value="$penelitianBelumLengkap" color="yellow" />
                <x-stat-card title="Seleksi Dokumen Penelitian" :value="$penelitianSeleksi" color="orange" />
                <x-stat-card title="Dokumen Lolos Penelitian" :value="$penelitianLolos" color="green" />
            </div>
        </section>

        <!-- Statistik Pengabdian -->
        <section class="space-y-3">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Statistik Kegiatan Pengabdian Masyarakat</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <x-stat-card title="Usulan Dokumen Pengabdian" :value="$pengabdianUsulan" color="blue" />
                <x-stat-card title="Dokumen Belum Lengkap" :value="$pengabdianBelumLengkap" color="yellow" />
                <x-stat-card title="Seleksi Dokumen Pengabdian" :value="$pengabdianSeleksi" color="orange" />
                <x-stat-card title="Dokumen Lolos Pengabdian" :value="$pengabdianLolos" color="green" />
            </div>
        </section>
    </div>
</x-layouts.dosen>


