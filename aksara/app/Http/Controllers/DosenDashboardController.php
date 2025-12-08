<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Penelitian;
use App\Models\Pengabdian;
use Illuminate\Support\Facades\Auth;

class DosenDashboardController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $year = $request->get('year');
        
        // Query builder for penelitian
        $penelitianQuery = $user->penelitian();
        if ($year) {
            $penelitianQuery->where('tahun', $year);
        }
        
        // Query builder for pengabdian
        $pengabdianQuery = $user->pengabdian();
        if ($year) {
            $pengabdianQuery->where('tahun', $year);
        }
        
        // ACTION REQUIRED DATA
        $totalPenelitian = $user->penelitian()->count();
        $totalPengabdian = $user->pengabdian()->count();
        
        // Statistik Penelitian dengan status baru
        $penelitianStats = [
            'diusulkan' => (clone $penelitianQuery)->where('status', 'diusulkan')->count(),
            'tidak_lolos' => (clone $penelitianQuery)->where('status', 'tidak_lolos')->count(),
            'lolos' => (clone $penelitianQuery)->whereIn('status', ['lolos_perlu_revisi', 'lolos', 'revisi_pra_final'])->count(),
            'selesai' => (clone $penelitianQuery)->where('status', 'selesai')->count(),
        ];


        // Statistik Pengabdian dengan status baru
        $pengabdianStats = [
            'diusulkan' => (clone $pengabdianQuery)->where('status', 'diusulkan')->count(),
            'tidak_lolos' => (clone $pengabdianQuery)->where('status', 'tidak_lolos')->count(),
            'lolos' => (clone $pengabdianQuery)->whereIn('status', ['lolos_perlu_revisi', 'lolos', 'revisi_pra_final'])->count(),
            'selesai' => (clone $pengabdianQuery)->where('status', 'selesai')->count(),
        ];

        // ENHANCED STATS
        $enhancedStats = [
            'total_kegiatan' => $totalPenelitian + $totalPengabdian,
            'success_rate' => $totalPenelitian + $totalPengabdian > 0 
                ? round((($penelitianStats['lolos'] + $penelitianStats['selesai'] + $pengabdianStats['lolos'] + $pengabdianStats['selesai']) / ($totalPenelitian + $totalPengabdian)) * 100, 1)
                : 0,
            'completion_rate' => $totalPenelitian + $totalPengabdian > 0
                ? round((($penelitianStats['selesai'] + $pengabdianStats['selesai']) / ($totalPenelitian + $totalPengabdian)) * 100, 1)
                : 0,
            'total_penelitian' => $totalPenelitian,
            'total_pengabdian' => $totalPengabdian,
        ];


        $actionRequired = [
            // Items that need revision
            'needs_revision' => $user->penelitian()
                ->whereIn('status', ['lolos_perlu_revisi', 'revisi_pra_final'])
                ->count() + 
                $user->pengabdian()
                ->whereIn('status', ['lolos_perlu_revisi', 'revisi_pra_final'])
                ->count(),
            
            // Items missing documents
            'missing_documents' => $user->penelitian()
                ->where('status', '!=', 'tidak_lolos')
                ->whereDoesntHave('documents')
                ->count() +
                $user->pengabdian()
                ->where('status', '!=', 'tidak_lolos')
                ->whereDoesntHave('documents')
                ->count(),
            
            // Items waiting for verification
            'pending_verification' => $user->penelitian()
                ->where('status', 'diusulkan')
                ->count() +
                $user->pengabdian()
                ->where('status', 'diusulkan')
                ->count(),
        ];
        
        // RECENT ACTIVITIES (last 10 updates)
        $recentActivities = collect()
            ->merge($user->penelitian()->latest('updated_at')->take(5)->get()->map(function($item) {
                return [
                    'type' => 'penelitian',
                    'title' => $item->judul,
                    'status' => $item->status,
                    'updated_at' => $item->updated_at,
                    'id' => $item->id,
                ];
            }))
            ->merge($user->pengabdian()->latest('updated_at')->take(5)->get()->map(function($item) {
                return [
                    'type' => 'pengabdian',
                    'title' => $item->judul,
                    'status' => $item->status,
                    'updated_at' => $item->updated_at,
                    'id' => $item->id,
                ];
            }))
            ->sortByDesc('updated_at')
            ->take(10);
        
        return view('dashboard-dosen', compact(
            'year', 
            'actionRequired', 
            'recentActivities',
            'enhancedStats',
            'penelitianStats',
            'pengabdianStats'
        ));
    }
}
