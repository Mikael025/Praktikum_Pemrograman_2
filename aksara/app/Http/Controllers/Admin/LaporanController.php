<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Penelitian;
use App\Models\Pengabdian;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

/**
 * Controller untuk laporan admin
 * 
 * Menyediakan fitur generate laporan penelitian dan pengabdian dengan
 * filter tahun, status, dan dosen. Mendukung export ke PDF dan CSV.
 */
class LaporanController extends Controller
{
    /**
     * Menampilkan halaman laporan dengan statistik dan filter
     * 
     * @param Request $request HTTP request dengan optional parameters: year, status, dosen_id
     * @return \Illuminate\View\View View laporan dengan data statistik
     */
    public function index(Request $request)
    {
        $year = $request->get('year');
        $status = $request->get('status');
        $dosen_id = $request->get('dosen_id');
        
        // Query penelitian
        $penelitianQuery = Penelitian::with('user');
        if ($year) {
            $penelitianQuery->where('tahun', $year);
        }
        if ($status) {
            $penelitianQuery->where('status', $status);
        }
        if ($dosen_id) {
            $penelitianQuery->where('user_id', $dosen_id);
        }
        
        // Query pengabdian
        $pengabdianQuery = Pengabdian::with('user');
        if ($year) {
            $pengabdianQuery->where('tahun', $year);
        }
        if ($status) {
            $pengabdianQuery->where('status', $status);
        }
        if ($dosen_id) {
            $pengabdianQuery->where('user_id', $dosen_id);
        }
        
        $penelitian = $penelitianQuery->latest()->get();
        $pengabdian = $pengabdianQuery->latest()->get();
        
        // Statistik keseluruhan
        $stats = [
            'total_penelitian' => $penelitian->count(),
            'total_pengabdian' => $pengabdian->count(),
            'penelitian_diusulkan' => $penelitian->where('status', 'diusulkan')->count(),
            'pengabdian_diusulkan' => $pengabdian->where('status', 'diusulkan')->count(),
            'penelitian_lolos' => $penelitian->whereIn('status', ['lolos', 'lolos_perlu_revisi', 'revisi_pra_final', 'selesai'])->count(),
            'pengabdian_lolos' => $pengabdian->whereIn('status', ['lolos', 'lolos_perlu_revisi', 'revisi_pra_final', 'selesai'])->count(),
            'penelitian_selesai' => $penelitian->where('status', 'selesai')->count(),
            'pengabdian_selesai' => $pengabdian->where('status', 'selesai')->count(),
        ];
        
        // Daftar dosen untuk filter
        $dosenList = User::where('role', 'dosen')->orderBy('name')->get();
        
        // Top dosen produktif
        $topDosen = User::where('role', 'dosen')
            ->withCount(['penelitian', 'pengabdian'])
            ->orderByRaw('(penelitian_count + pengabdian_count) DESC')
            ->limit(10)
            ->get();
        
        return view('admin.laporan.index', compact('penelitian', 'pengabdian', 'stats', 'dosenList', 'topDosen', 'year', 'status', 'dosen_id'));
    }
    
    /**
     * Export laporan penelitian dan pengabdian ke format PDF
     * 
     * @param Request $request HTTP request dengan optional parameters: year, status, dosen_id
     * @return \Illuminate\Http\Response Response dengan file PDF untuk download
     */
    public function exportPdf(Request $request)
    {
        $year = $request->get('year');
        $status = $request->get('status');
        $dosen_id = $request->get('dosen_id');
        
        // Query penelitian
        $penelitianQuery = Penelitian::with('user');
        if ($year) {
            $penelitianQuery->where('tahun', $year);
        }
        if ($status) {
            $penelitianQuery->where('status', $status);
        }
        if ($dosen_id) {
            $penelitianQuery->where('user_id', $dosen_id);
        }
        
        // Query pengabdian
        $pengabdianQuery = Pengabdian::with('user');
        if ($year) {
            $pengabdianQuery->where('tahun', $year);
        }
        if ($status) {
            $pengabdianQuery->where('status', $status);
        }
        if ($dosen_id) {
            $pengabdianQuery->where('user_id', $dosen_id);
        }
        
        $penelitian = $penelitianQuery->latest()->get();
        $pengabdian = $pengabdianQuery->latest()->get();
        
        // Statistik
        $stats = [
            'total_penelitian' => $penelitian->count(),
            'total_pengabdian' => $pengabdian->count(),
            'penelitian_diusulkan' => $penelitian->where('status', 'diusulkan')->count(),
            'pengabdian_diusulkan' => $pengabdian->where('status', 'diusulkan')->count(),
            'penelitian_lolos' => $penelitian->whereIn('status', ['lolos', 'lolos_perlu_revisi', 'revisi_pra_final', 'selesai'])->count(),
            'pengabdian_lolos' => $pengabdian->whereIn('status', ['lolos', 'lolos_perlu_revisi', 'revisi_pra_final', 'selesai'])->count(),
            'penelitian_selesai' => $penelitian->where('status', 'selesai')->count(),
            'pengabdian_selesai' => $pengabdian->where('status', 'selesai')->count(),
        ];
        
        $selectedDosen = $dosen_id ? User::find($dosen_id) : null;
        
        $pdf = Pdf::loadView('admin.laporan.pdf', compact('penelitian', 'pengabdian', 'stats', 'year', 'status', 'selectedDosen'));
        
        $filename = 'Laporan_Admin_' . date('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }
    
    /**
     * Menampilkan halaman perbandingan data penelitian dan pengabdian antar tahun
     * 
     * @param Request $request HTTP request
     * @return \Illuminate\View\View View perbandingan dengan grafik tren tahunan
     */
    public function perbandingan(Request $request)
    {
        // Get data untuk perbandingan tahunan
        $years = Penelitian::selectRaw('DISTINCT tahun')
            ->orderBy('tahun')
            ->pluck('tahun');
        
        $penelitianPerTahun = [];
        $pengabdianPerTahun = [];
        $lolosPerTahun = [];
        $selesaiPerTahun = [];
        $dosenAktifPerTahun = [];
        
        foreach ($years as $year) {
            $penelitianCount = Penelitian::where('tahun', $year)->count();
            $pengabdianCount = Pengabdian::where('tahun', $year)->count();
            $lolosCount = Penelitian::where('tahun', $year)
                ->whereIn('status', ['lolos', 'lolos_perlu_revisi', 'revisi_pra_final', 'selesai'])
                ->count() +
                Pengabdian::where('tahun', $year)
                ->whereIn('status', ['lolos', 'lolos_perlu_revisi', 'revisi_pra_final', 'selesai'])
                ->count();
            $selesaiCount = Penelitian::where('tahun', $year)
                ->where('status', 'selesai')
                ->count() +
                Pengabdian::where('tahun', $year)
                ->where('status', 'selesai')
                ->count();
            
            // Hitung jumlah dosen yang aktif di tahun ini
            $dosenAktif = User::where('role', 'dosen')
                ->whereHas('penelitian', function($q) use ($year) {
                    $q->where('tahun', $year);
                })
                ->orWhereHas('pengabdian', function($q) use ($year) {
                    $q->where('tahun', $year);
                })
                ->count();
                
            $penelitianPerTahun[$year] = $penelitianCount;
            $pengabdianPerTahun[$year] = $pengabdianCount;
            $lolosPerTahun[$year] = $lolosCount;
            $selesaiPerTahun[$year] = $selesaiCount;
            $dosenAktifPerTahun[$year] = $dosenAktif;
        }
        
        // Data perbandingan per status
        $statusData = [
            'diusulkan' => Penelitian::where('status', 'diusulkan')->count() +
                          Pengabdian::where('status', 'diusulkan')->count(),
            'tidak_lolos' => Penelitian::where('status', 'tidak_lolos')->count() +
                            Pengabdian::where('status', 'tidak_lolos')->count(),
            'lolos' => Penelitian::whereIn('status', ['lolos', 'lolos_perlu_revisi'])->count() +
                      Pengabdian::whereIn('status', ['lolos', 'lolos_perlu_revisi'])->count(),
            'selesai' => Penelitian::where('status', 'selesai')->count() +
                        Pengabdian::where('status', 'selesai')->count(),
        ];
        
        // Top 5 dosen paling produktif
        $topDosen = User::where('role', 'dosen')
            ->withCount(['penelitian', 'pengabdian'])
            ->orderByRaw('(penelitian_count + pengabdian_count) DESC')
            ->limit(5)
            ->get();
        
        return view('admin.laporan.perbandingan', compact(
            'penelitianPerTahun', 
            'pengabdianPerTahun', 
            'lolosPerTahun', 
            'selesaiPerTahun',
            'dosenAktifPerTahun',
            'statusData',
            'topDosen',
            'years'
        ));
    }
    
    /**
     * Export laporan penelitian dan pengabdian ke format CSV
     * 
     * @param Request $request HTTP request dengan optional parameters: year, status, dosen_id
     * @return \Symfony\Component\HttpFoundation\StreamedResponse Response dengan file CSV untuk download
     */
    public function exportCsv(Request $request)
    {
        $year = $request->get('year');
        $status = $request->get('status');
        $dosen_id = $request->get('dosen_id');
        
        // Query penelitian
        $penelitianQuery = Penelitian::with('user');
        if ($year) {
            $penelitianQuery->where('tahun', $year);
        }
        if ($status) {
            $penelitianQuery->where('status', $status);
        }
        if ($dosen_id) {
            $penelitianQuery->where('user_id', $dosen_id);
        }
        
        // Query pengabdian
        $pengabdianQuery = Pengabdian::with('user');
        if ($year) {
            $pengabdianQuery->where('tahun', $year);
        }
        if ($status) {
            $pengabdianQuery->where('status', $status);
        }
        if ($dosen_id) {
            $pengabdianQuery->where('user_id', $dosen_id);
        }
        
        $penelitian = $penelitianQuery->latest()->get();
        $pengabdian = $pengabdianQuery->latest()->get();
        
        $filename = 'Laporan_Admin_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($penelitian, $pengabdian) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, ['Jenis', 'Dosen', 'Judul', 'Tahun', 'Status', 'Sumber Dana', 'Tim']);
            
            // Data Penelitian
            foreach ($penelitian as $item) {
                fputcsv($file, [
                    'Penelitian',
                    $item->user->name ?? '-',
                    $item->judul,
                    $item->tahun,
                    str_replace('_', ' ', ucfirst($item->status)),
                    $item->sumber_dana ?? '-',
                    is_array($item->tim_peneliti) ? implode(', ', $item->tim_peneliti) : '-'
                ]);
            }
            
            // Data Pengabdian
            foreach ($pengabdian as $item) {
                fputcsv($file, [
                    'Pengabdian',
                    $item->user->name ?? '-',
                    $item->judul,
                    $item->tahun,
                    str_replace('_', ' ', ucfirst($item->status)),
                    $item->sumber_dana ?? '-',
                    is_array($item->tim_pelaksana) ? implode(', ', $item->tim_pelaksana) : '-'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
