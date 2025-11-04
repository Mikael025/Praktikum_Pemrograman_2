<?php

namespace App\Http\Controllers;

use App\Models\Penelitian;
use App\Models\Pengabdian;
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
                $penelitianStats = [
                    'diusulkan' => Penelitian::whereYear('created_at', $year)
                        ->where('status', 'diusulkan')->count(),
                    'tidak_lolos' => Penelitian::whereYear('created_at', $year)
                        ->where('status', 'tidak_lolos')->count(),
                    'lolos' => Penelitian::whereYear('created_at', $year)
                        ->whereIn('status', ['lolos_perlu_revisi', 'lolos', 'revisi_pra_final'])->count(),
                    'selesai' => Penelitian::whereYear('created_at', $year)
                        ->where('status', 'selesai')->count(),
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
                $pengabdianStats = [
                    'diusulkan' => Pengabdian::whereYear('created_at', $year)
                        ->where('status', 'diusulkan')->count(),
                    'tidak_lolos' => Pengabdian::whereYear('created_at', $year)
                        ->where('status', 'tidak_lolos')->count(),
                    'lolos' => Pengabdian::whereYear('created_at', $year)
                        ->whereIn('status', ['lolos_perlu_revisi', 'lolos', 'revisi_pra_final'])->count(),
                    'selesai' => Pengabdian::whereYear('created_at', $year)
                        ->where('status', 'selesai')->count(),
                ];
            } catch (\Exception $e) {
                // Jika tabel belum ada, gunakan nilai default
            }
            
            return view('dashboard-admin', compact('penelitianStats', 'pengabdianStats', 'year'));
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
            
            return view('dashboard-admin', compact('penelitianStats', 'pengabdianStats', 'year'));
        }
    }
}
