<x-layouts.dosen>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Perbandingan Data Kegiatan</h2>
                    <p class="mt-1 text-sm text-gray-600">Visualisasi data penelitian dan pengabdian Anda</p>
                </div>
                <a href="{{ route('dosen.laporan.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    ← Kembali
                </a>
            </div>

            <!-- Chart: Kegiatan per Tahun -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Jumlah Kegiatan per Tahun</h3>
                    <canvas id="kegiatanPerTahunChart" height="80"></canvas>
                </div>
            </div>

            <!-- Charts Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Chart: Tingkat Keberhasilan -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Tingkat Keberhasilan per Tahun</h3>
                        <canvas id="keberhasilanChart" height="120"></canvas>
                    </div>
                </div>

                <!-- Chart: Distribusi Status -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Distribusi Status Kegiatan</h3>
                        <canvas id="statusChart" height="120"></canvas>
                    </div>
                </div>
            </div>

            <!-- Chart: Penelitian vs Pengabdian -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Perbandingan Penelitian vs Pengabdian</h3>
                    <canvas id="jenisKegiatanChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const years = @json(array_keys($penelitianPerTahun));
        const penelitianData = @json(array_values($penelitianPerTahun));
        const pengabdianData = @json(array_values($pengabdianPerTahun));
        const lolosData = @json(array_values($lolosPerTahun));
        const selesaiData = @json(array_values($selesaiPerTahun));
        const statusData = @json($statusData);

        // Chart 1: Kegiatan per Tahun
        new Chart(document.getElementById('kegiatanPerTahunChart'), {
            type: 'bar',
            data: {
                labels: years,
                datasets: [
                    {
                        label: 'Penelitian',
                        data: penelitianData,
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Pengabdian',
                        data: pengabdianData,
                        backgroundColor: 'rgba(16, 185, 129, 0.8)',
                        borderColor: 'rgba(16, 185, 129, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });

        // Chart 2: Tingkat Keberhasilan
        new Chart(document.getElementById('keberhasilanChart'), {
            type: 'line',
            data: {
                labels: years,
                datasets: [
                    {
                        label: 'Lolos',
                        data: lolosData,
                        backgroundColor: 'rgba(34, 197, 94, 0.2)',
                        borderColor: 'rgba(34, 197, 94, 1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Selesai',
                        data: selesaiData,
                        backgroundColor: 'rgba(99, 102, 241, 0.2)',
                        borderColor: 'rgba(99, 102, 241, 1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });

        // Chart 3: Distribusi Status (Pie)
        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: ['Diusulkan', 'Tidak Lolos', 'Lolos', 'Selesai'],
                datasets: [{
                    data: [
                        statusData.diusulkan,
                        statusData.tidak_lolos,
                        statusData.lolos,
                        statusData.selesai
                    ],
                    backgroundColor: [
                        'rgba(156, 163, 175, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(251, 191, 36, 0.8)',
                        'rgba(34, 197, 94, 0.8)'
                    ],
                    borderColor: [
                        'rgba(156, 163, 175, 1)',
                        'rgba(239, 68, 68, 1)',
                        'rgba(251, 191, 36, 1)',
                        'rgba(34, 197, 94, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'right'
                    }
                }
            }
        });

        // Chart 4: Jenis Kegiatan (Bar Horizontal)
        const totalPenelitian = penelitianData.reduce((a, b) => a + b, 0);
        const totalPengabdian = pengabdianData.reduce((a, b) => a + b, 0);

        new Chart(document.getElementById('jenisKegiatanChart'), {
            type: 'bar',
            data: {
                labels: ['Total Kegiatan'],
                datasets: [
                    {
                        label: 'Penelitian',
                        data: [totalPenelitian],
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Pengabdian',
                        data: [totalPengabdian],
                        backgroundColor: 'rgba(16, 185, 129, 0.8)',
                        borderColor: 'rgba(16, 185, 129, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });
    </script>
</x-layouts.dosen>
