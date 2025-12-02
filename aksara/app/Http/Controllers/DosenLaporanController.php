<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Penelitian;
use App\Models\Pengabdian;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class DosenLaporanController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $year = $request->get('year');
        $status = $request->get('status');
        
        // Query penelitian
        $penelitianQuery = $user->penelitian();
        if ($year) {
            $penelitianQuery->where('tahun', $year);
        }
        if ($status) {
            $penelitianQuery->where('status', $status);
        }
        
        // Query pengabdian
        $pengabdianQuery = $user->pengabdian();
        if ($year) {
            $pengabdianQuery->where('tahun', $year);
        }
        if ($status) {
            $pengabdianQuery->where('status', $status);
        }
        
        $penelitian = $penelitianQuery->latest()->get();
        $pengabdian = $pengabdianQuery->latest()->get();
        
        // Statistik
        $stats = [
            'total_penelitian' => $penelitian->count(),
            'total_pengabdian' => $pengabdian->count(),
            'penelitian_lolos' => $penelitian->whereIn('status', ['lolos', 'lolos_perlu_revisi', 'revisi_pra_final', 'selesai'])->count(),
            'pengabdian_lolos' => $pengabdian->whereIn('status', ['lolos', 'lolos_perlu_revisi', 'revisi_pra_final', 'selesai'])->count(),
            'penelitian_selesai' => $penelitian->where('status', 'selesai')->count(),
            'pengabdian_selesai' => $pengabdian->where('status', 'selesai')->count(),
        ];
        
        return view('dosen.laporan.index', compact('penelitian', 'pengabdian', 'stats', 'year', 'status'));
    }
    
    public function exportPdf(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $year = $request->get('year');
        $status = $request->get('status');
        
        // Query penelitian
        $penelitianQuery = $user->penelitian();
        if ($year) {
            $penelitianQuery->where('tahun', $year);
        }
        if ($status) {
            $penelitianQuery->where('status', $status);
        }
        
        // Query pengabdian
        $pengabdianQuery = $user->pengabdian();
        if ($year) {
            $pengabdianQuery->where('tahun', $year);
        }
        if ($status) {
            $pengabdianQuery->where('status', $status);
        }
        
        $penelitian = $penelitianQuery->latest()->get();
        $pengabdian = $pengabdianQuery->latest()->get();
        
        // Statistik
        $stats = [
            'total_penelitian' => $penelitian->count(),
            'total_pengabdian' => $pengabdian->count(),
            'penelitian_lolos' => $penelitian->whereIn('status', ['lolos', 'lolos_perlu_revisi', 'revisi_pra_final', 'selesai'])->count(),
            'pengabdian_lolos' => $pengabdian->whereIn('status', ['lolos', 'lolos_perlu_revisi', 'revisi_pra_final', 'selesai'])->count(),
            'penelitian_selesai' => $penelitian->where('status', 'selesai')->count(),
            'pengabdian_selesai' => $pengabdian->where('status', 'selesai')->count(),
        ];
        
        $pdf = Pdf::loadView('dosen.laporan.pdf', compact('user', 'penelitian', 'pengabdian', 'stats', 'year', 'status'));
        
        $filename = 'Laporan_Kegiatan_' . $user->name . '_' . date('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }
    
    public function perbandingan(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Get data untuk perbandingan tahunan
        $years = Penelitian::where('user_id', $user->id)
            ->selectRaw('DISTINCT tahun')
            ->orderBy('tahun')
            ->pluck('tahun');
        
        $penelitianPerTahun = [];
        $pengabdianPerTahun = [];
        $lolosPerTahun = [];
        $selesaiPerTahun = [];
        
        foreach ($years as $year) {
            $penelitianCount = Penelitian::where('user_id', $user->id)
                ->where('tahun', $year)
                ->count();
            $pengabdianCount = Pengabdian::where('user_id', $user->id)
                ->where('tahun', $year)
                ->count();
            $lolosCount = Penelitian::where('user_id', $user->id)
                ->where('tahun', $year)
                ->whereIn('status', ['lolos', 'lolos_perlu_revisi', 'revisi_pra_final', 'selesai'])
                ->count() +
                Pengabdian::where('user_id', $user->id)
                ->where('tahun', $year)
                ->whereIn('status', ['lolos', 'lolos_perlu_revisi', 'revisi_pra_final', 'selesai'])
                ->count();
            $selesaiCount = Penelitian::where('user_id', $user->id)
                ->where('tahun', $year)
                ->where('status', 'selesai')
                ->count() +
                Pengabdian::where('user_id', $user->id)
                ->where('tahun', $year)
                ->where('status', 'selesai')
                ->count();
                
            $penelitianPerTahun[$year] = $penelitianCount;
            $pengabdianPerTahun[$year] = $pengabdianCount;
            $lolosPerTahun[$year] = $lolosCount;
            $selesaiPerTahun[$year] = $selesaiCount;
        }
        
        // Data perbandingan per status
        $statusData = [
            'diusulkan' => Penelitian::where('user_id', $user->id)->where('status', 'diusulkan')->count() +
                          Pengabdian::where('user_id', $user->id)->where('status', 'diusulkan')->count(),
            'tidak_lolos' => Penelitian::where('user_id', $user->id)->where('status', 'tidak_lolos')->count() +
                            Pengabdian::where('user_id', $user->id)->where('status', 'tidak_lolos')->count(),
            'lolos' => Penelitian::where('user_id', $user->id)->whereIn('status', ['lolos', 'lolos_perlu_revisi'])->count() +
                      Pengabdian::where('user_id', $user->id)->whereIn('status', ['lolos', 'lolos_perlu_revisi'])->count(),
            'selesai' => Penelitian::where('user_id', $user->id)->where('status', 'selesai')->count() +
                        Pengabdian::where('user_id', $user->id)->where('status', 'selesai')->count(),
        ];
        
        return view('dosen.laporan.perbandingan', compact(
            'penelitianPerTahun', 
            'pengabdianPerTahun', 
            'lolosPerTahun', 
            'selesaiPerTahun',
            'statusData',
            'years'
        ));
    }
    
    public function exportCsv(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $year = $request->get('year');
        $status = $request->get('status');
        
        // Query penelitian
        $penelitianQuery = $user->penelitian();
        if ($year) {
            $penelitianQuery->where('tahun', $year);
        }
        if ($status) {
            $penelitianQuery->where('status', $status);
        }
        
        // Query pengabdian
        $pengabdianQuery = $user->pengabdian();
        if ($year) {
            $pengabdianQuery->where('tahun', $year);
        }
        if ($status) {
            $pengabdianQuery->where('status', $status);
        }
        
        $penelitian = $penelitianQuery->latest()->get();
        $pengabdian = $pengabdianQuery->latest()->get();
        
        $filename = 'Laporan_Kegiatan_' . $user->name . '_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($penelitian, $pengabdian) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, ['Jenis', 'Judul', 'Tahun', 'Status', 'Sumber Dana', 'Tim']);
            
            // Data Penelitian
            foreach ($penelitian as $item) {
                fputcsv($file, [
                    'Penelitian',
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
