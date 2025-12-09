<?php

namespace App\Http\Controllers;

use App\Models\Penelitian;
use App\Models\Pengabdian;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        try {
            $year = $request->get('year', date('Y'));
            
            // Statistik Penelitian - dengan status baru
            $penelitianStats = [
                'diusulkan' => 0,
                'tidak_lolos' => 0,
                'lolos' => 0,
                'selesai' => 0,
            ];
            
            try {
                $penelitianQuery = $year ? Penelitian::where('tahun', $year) : Penelitian::query();
                $penelitianStats = [
                    'diusulkan' => (clone $penelitianQuery)->where('status', 'diusulkan')->count(),
                    'tidak_lolos' => (clone $penelitianQuery)->where('status', 'tidak_lolos')->count(),
                    'lolos' => (clone $penelitianQuery)->whereIn('status', ['lolos_perlu_revisi', 'lolos', 'revisi_pra_final'])->count(),
                    'selesai' => (clone $penelitianQuery)->where('status', 'selesai')->count(),
                ];
            } catch (\Exception $e) {
                // Jika tabel belum ada, gunakan nilai default
            }
            
            // Statistik Pengabdian - dengan status baru
            $pengabdianStats = [
                'diusulkan' => 0,
                'tidak_lolos' => 0,
                'lolos' => 0,
                'selesai' => 0,
            ];
            
            try {
                $pengabdianQuery = $year ? Pengabdian::where('tahun', $year) : Pengabdian::query();
                $pengabdianStats = [
                    'diusulkan' => (clone $pengabdianQuery)->where('status', 'diusulkan')->count(),
                    'tidak_lolos' => (clone $pengabdianQuery)->where('status', 'tidak_lolos')->count(),
                    'lolos' => (clone $pengabdianQuery)->whereIn('status', ['lolos_perlu_revisi', 'lolos', 'revisi_pra_final'])->count(),
                    'selesai' => (clone $pengabdianQuery)->where('status', 'selesai')->count(),
                ];
            } catch (\Exception $e) {
                // Jika tabel belum ada, gunakan nilai default
            }
            
            // VERIFICATION QUEUE - Items awaiting verification
            $verificationQueue = collect()
                ->merge(
                    Penelitian::where('status', 'diusulkan')
                        ->with('user')
                        ->latest('created_at')
                        ->get()
                        ->map(function($item) {
                            return [
                                'id' => $item->id,
                                'type' => 'penelitian',
                                'title' => $item->judul,
                                'dosen' => $item->user->name ?? 'N/A',
                                'submitted_at' => $item->created_at,
                                'days_pending' => $item->created_at->diffInDays(now()),
                            ];
                        })
                )
                ->merge(
                    Pengabdian::where('status', 'diusulkan')
                        ->with('user')
                        ->latest('created_at')
                        ->get()
                        ->map(function($item) {
                            return [
                                'id' => $item->id,
                                'type' => 'pengabdian',
                                'title' => $item->judul,
                                'dosen' => $item->user->name ?? 'N/A',
                                'submitted_at' => $item->created_at,
                                'days_pending' => $item->created_at->diffInDays(now()),
                            ];
                        })
                )
                ->sortByDesc('submitted_at')
                ->values();
            
            // ENHANCED SYSTEM OVERVIEW
            $totalPenelitian = Penelitian::count();
            $totalPengabdian = Pengabdian::count();
            $totalDosen = User::where('role', 'dosen')->count();
            
            $enhancedStats = [
                'total_dosen' => $totalDosen,
                'total_kegiatan' => $totalPenelitian + $totalPengabdian,
                'pending_reviews' => $penelitianStats['diusulkan'] + $pengabdianStats['diusulkan'],
                'avg_success_rate' => $totalPenelitian + $totalPengabdian > 0
                    ? round((($penelitianStats['lolos'] + $penelitianStats['selesai'] + $pengabdianStats['lolos'] + $pengabdianStats['selesai']) / ($totalPenelitian + $totalPengabdian)) * 100, 1)
                    : 0,
                'total_penelitian' => $totalPenelitian,
                'total_pengabdian' => $totalPengabdian,
                'penelitian_selesai' => $penelitianStats['selesai'],
                'pengabdian_selesai' => $pengabdianStats['selesai'],
            ];
            
            // ALERTS & WARNINGS
            $alerts = [];
            
            // Items pending > 7 days
            $oldPending = $verificationQueue->filter(function($item) {
                return $item['days_pending'] > 7;
            })->count();
            
            if ($oldPending > 0) {
                $alerts[] = [
                    'type' => 'warning',
                    'message' => "$oldPending kegiatan menunggu verifikasi lebih dari 7 hari",
                    'action' => 'Segera tinjau untuk menghindari penundaan',
                ];
            }
            
            // Missing documents
            $missingDocs = Penelitian::where('status', '!=', 'tidak_lolos')
                ->whereDoesntHave('documents')
                ->count() +
                Pengabdian::where('status', '!=', 'tidak_lolos')
                ->whereDoesntHave('documents')
                ->count();
                
            if ($missingDocs > 0) {
                $alerts[] = [
                    'type' => 'info',
                    'message' => "$missingDocs kegiatan belum memiliki dokumen lengkap",
                    'action' => 'Hubungi dosen terkait untuk melengkapi dokumen',
                ];
            }
            
            // RECENT ADMIN ACTIONS (simulated from recent updates)
            $recentActions = collect()
                ->merge(
                    Penelitian::whereIn('status', ['lolos', 'tidak_lolos', 'lolos_perlu_revisi', 'selesai'])
                        ->with('user')
                        ->latest('updated_at')
                        ->take(5)
                        ->get()
                        ->map(function($item) {
                            return [
                                'type' => 'penelitian',
                                'action' => $this->getActionFromStatus($item->status),
                                'title' => $item->judul,
                                'dosen' => $item->user->name ?? 'N/A',
                                'timestamp' => $item->updated_at,
                                'id' => $item->id,
                            ];
                        })
                )
                ->merge(
                    Pengabdian::whereIn('status', ['lolos', 'tidak_lolos', 'lolos_perlu_revisi', 'selesai'])
                        ->with('user')
                        ->latest('updated_at')
                        ->take(5)
                        ->get()
                        ->map(function($item) {
                            return [
                                'type' => 'pengabdian',
                                'action' => $this->getActionFromStatus($item->status),
                                'title' => $item->judul,
                                'dosen' => $item->user->name ?? 'N/A',
                                'timestamp' => $item->updated_at,
                                'id' => $item->id,
                            ];
                        })
                )
                ->sortByDesc('timestamp')
                ->take(10)
                ->values();
            
            return view('dashboard-admin', compact(
                'penelitianStats', 
                'pengabdianStats', 
                'year',
                'verificationQueue',
                'enhancedStats',
                'alerts',
                'recentActions'
            ));
        } catch (\Exception $e) {
            // Fallback jika ada error
            $penelitianStats = [
                'diusulkan' => 0,
                'tidak_lolos' => 0,
                'lolos' => 0,
                'selesai' => 0,
            ];
            
            $pengabdianStats = [
                'diusulkan' => 0,
                'tidak_lolos' => 0,
                'lolos' => 0,
                'selesai' => 0,
            ];
            
            $year = date('Y');
            $verificationQueue = collect();
            $enhancedStats = [
                'total_dosen' => 0,
                'total_kegiatan' => 0,
                'pending_reviews' => 0,
                'avg_success_rate' => 0,
            ];
            $alerts = [];
            $recentActions = collect();
            
            return view('dashboard-admin', compact(
                'penelitianStats', 
                'pengabdianStats', 
                'year',
                'verificationQueue',
                'enhancedStats',
                'alerts',
                'recentActions'
            ));
        }
    }
    
    /**
     * Get top performing researchers
     */
    public function topResearchers(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $limit = $request->get('limit', 10);

        $researchers = User::where('role', 'dosen')
            ->withCount([
                'penelitian as total_penelitian' => function($query) use ($year) {
                    if ($year) $query->where('tahun', $year);
                },
                'penelitian as completed_penelitian' => function($query) use ($year) {
                    $query->where('status', 'selesai');
                    if ($year) $query->where('tahun', $year);
                },
                'pengabdian as total_pengabdian' => function($query) use ($year) {
                    if ($year) $query->where('tahun', $year);
                },
                'pengabdian as completed_pengabdian' => function($query) use ($year) {
                    $query->where('status', 'selesai');
                    if ($year) $query->where('tahun', $year);
                },
            ])
            ->get()
            ->map(function($user) {
                $total = $user->total_penelitian + $user->total_pengabdian;
                $completed = $user->completed_penelitian + $user->completed_pengabdian;
                
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'total_activities' => $total,
                    'completed_activities' => $completed,
                    'completion_rate' => $total > 0 ? round(($completed / $total) * 100, 1) : 0,
                    'productivity_score' => ($total * 0.4) + ($completed * 0.6),
                ];
            })
            ->filter(function($researcher) {
                return $researcher['total_activities'] > 0;
            })
            ->sortByDesc('productivity_score')
            ->take($limit)
            ->values();

        return response()->json($researchers);
    }
    
    /**
     * Get submission heatmap data
     */
    public function submissionHeatmap(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $startDate = "$year-01-01";
        $endDate = "$year-12-31";

        // Get all submissions grouped by date
        $penelitianSubmissions = Penelitian::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date');

        $pengabdianSubmissions = Pengabdian::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date');

        // Merge and format data
        $heatmapData = [];
        $allDates = $penelitianSubmissions->keys()->merge($pengabdianSubmissions->keys())->unique();

        foreach ($allDates as $date) {
            $heatmapData[] = [
                'date' => $date,
                'count' => ($penelitianSubmissions->get($date, 0) + $pengabdianSubmissions->get($date, 0)),
                'penelitian' => $penelitianSubmissions->get($date, 0),
                'pengabdian' => $pengabdianSubmissions->get($date, 0),
            ];
        }

        return response()->json([
            'data' => $heatmapData,
            'year' => $year,
            'total_submissions' => array_sum(array_column($heatmapData, 'count')),
        ]);
    }
    
    private function getActionFromStatus($status)
    {
        return match($status) {
            'lolos' => 'Menyetujui',
            'tidak_lolos' => 'Menolak',
            'lolos_perlu_revisi' => 'Meminta Revisi',
            'selesai' => 'Menyelesaikan',
            default => 'Memperbarui'
        };
    }
}
