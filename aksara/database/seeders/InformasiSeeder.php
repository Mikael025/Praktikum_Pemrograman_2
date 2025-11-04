<?php

namespace Database\Seeders;

use App\Models\Informasi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InformasiSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'title' => 'Sosialisasi Hibah Penelitian 2025',
                'content' => 'Detail sosialisasi hibah penelitian tahun 2025...'
                    . "\n\nPersyaratan dan timeline terlampir.",
                'category' => 'penelitian',
                'visibility' => 'semua',
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Pengumpulan Laporan Kemajuan Pengabdian',
                'content' => 'Batas waktu pengumpulan laporan kemajuan kegiatan pengabdian...'
                    . "\n\nSilakan unggah berkas pada sistem.",
                'category' => 'pengabdian',
                'visibility' => 'dosen',
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => 'Workshop Penulisan Artikel Terindeks',
                'content' => 'Workshop untuk peningkatan kualitas publikasi...'
                    . "\n\nKuota terbatas.",
                'category' => 'penelitian',
                'visibility' => 'semua',
                'published_at' => now()->subDays(1),
            ],
            [
                'title' => 'Bakti Sosial Desa Binaan',
                'content' => 'Agenda bakti sosial bersama tim pengabdian masyarakat...'
                    . "\n\nTerbuka untuk dosen dan mahasiswa.",
                'category' => 'pengabdian',
                'visibility' => 'semua',
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Pemeliharaan Sistem Aksara',
                'content' => 'Akan dilakukan maintenance sistem pada akhir pekan ini...'
                    . "\n\nMohon maaf atas ketidaknyamanan.",
                'category' => 'umum',
                'visibility' => 'semua',
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'Informasi Khusus Admin: Rekap Triwulan',
                'content' => 'Laporan rekap triwulan untuk admin program...'
                    . "\n\nHarap ditinjau sebelum rapat.",
                'category' => 'umum',
                'visibility' => 'admin',
                'published_at' => now()->subDays(7),
            ],
        ];

        foreach ($items as $item) {
            Informasi::updateOrCreate(
                ['slug' => Str::slug($item['title'])],
                $item + ['slug' => Str::slug($item['title'])]
            );
        }
    }
}


