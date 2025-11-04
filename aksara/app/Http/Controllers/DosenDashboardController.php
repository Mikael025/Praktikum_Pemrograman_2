<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Penelitian;
use App\Models\Pengabdian;
use Illuminate\Support\Facades\Auth;

class DosenDashboardController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Statistik Penelitian dengan status baru
        $penelitianStats = [
            'diusulkan' => $user->penelitian()->where('status', 'diusulkan')->count(),
            'tidak_lolos' => $user->penelitian()->where('status', 'tidak_lolos')->count(),
            'lolos' => $user->penelitian()->whereIn('status', ['lolos_perlu_revisi', 'lolos', 'revisi_pra_final'])->count(),
            'selesai' => $user->penelitian()->where('status', 'selesai')->count(),
        ];
        
        // Statistik Pengabdian dengan status baru
        $pengabdianStats = [
            'diusulkan' => $user->pengabdian()->where('status', 'diusulkan')->count(),
            'tidak_lolos' => $user->pengabdian()->where('status', 'tidak_lolos')->count(),
            'lolos' => $user->pengabdian()->whereIn('status', ['lolos_perlu_revisi', 'lolos', 'revisi_pra_final'])->count(),
            'selesai' => $user->pengabdian()->where('status', 'selesai')->count(),
        ];
        
        return view('dashboard-dosen', compact('penelitianStats', 'pengabdianStats'));
    }
}
