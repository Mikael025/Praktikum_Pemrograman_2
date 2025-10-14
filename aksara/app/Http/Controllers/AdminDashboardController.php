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
            
            // Statistik Penelitian - dengan try catch untuk handle jika tabel belum ada
            $penelitianStats = [
                'total_aktif' => 0,
                'menunggu_verifikasi' => 0,
                'selesai_diverifikasi' => 0,
                'ditolak' => 0,
            ];
            
            try {
                $penelitianStats = [
                    'total_aktif' => Penelitian::whereYear('created_at', $year)
                        ->whereIn('status', ['berjalan', 'terverifikasi', 'menunggu_verifikasi'])->count(),
                    'menunggu_verifikasi' => Penelitian::whereYear('created_at', $year)
                        ->where('status', 'menunggu_verifikasi')->count(),
                    'selesai_diverifikasi' => Penelitian::whereYear('created_at', $year)
                        ->where('status', 'terverifikasi')->count(),
                    'ditolak' => Penelitian::whereYear('created_at', $year)
                        ->where('status', 'ditolak')->count(),
                ];
            } catch (\Exception $e) {
                // Jika tabel belum ada, gunakan nilai default
            }
            
            // Statistik Pengabdian - dengan try catch untuk handle jika tabel belum ada
            $pengabdianStats = [
                'total_aktif' => 0,
                'menunggu_verifikasi' => 0,
                'selesai_diverifikasi' => 0,
                'ditolak' => 0,
            ];
            
            try {
                $pengabdianStats = [
                    'total_aktif' => Pengabdian::whereYear('created_at', $year)
                        ->whereIn('status', ['berjalan', 'terverifikasi', 'menunggu_verifikasi'])->count(),
                    'menunggu_verifikasi' => Pengabdian::whereYear('created_at', $year)
                        ->where('status', 'menunggu_verifikasi')->count(),
                    'selesai_diverifikasi' => Pengabdian::whereYear('created_at', $year)
                        ->where('status', 'terverifikasi')->count(),
                    'ditolak' => Pengabdian::whereYear('created_at', $year)
                        ->where('status', 'ditolak')->count(),
                ];
            } catch (\Exception $e) {
                // Jika tabel belum ada, gunakan nilai default
            }
            
            return view('dashboard-admin', compact('penelitianStats', 'pengabdianStats', 'year'));
        } catch (\Exception $e) {
            // Fallback jika ada error
            $penelitianStats = [
                'total_aktif' => 0,
                'menunggu_verifikasi' => 0,
                'selesai_diverifikasi' => 0,
                'ditolak' => 0,
            ];
            
            $pengabdianStats = [
                'total_aktif' => 0,
                'menunggu_verifikasi' => 0,
                'selesai_diverifikasi' => 0,
                'ditolak' => 0,
            ];
            
            $year = date('Y');
            
            return view('dashboard-admin', compact('penelitianStats', 'pengabdianStats', 'year'));
        }
    }
}
