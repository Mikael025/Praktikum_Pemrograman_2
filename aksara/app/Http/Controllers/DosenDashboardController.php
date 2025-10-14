<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penelitian;
use App\Models\Pengabdian;

class DosenDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Statistik Penelitian
        $penelitianUsulan = $user->penelitian()->count();
        $penelitianBelumLengkap = $user->penelitian()->where('status', 'draft')->count();
        $penelitianSeleksi = $user->penelitian()->where('status', 'menunggu_verifikasi')->count();
        $penelitianLolos = $user->penelitian()->where('status', 'terverifikasi')->count();
        
        // Statistik Pengabdian
        $pengabdianUsulan = $user->pengabdian()->count();
        $pengabdianBelumLengkap = $user->pengabdian()->where('status', 'draft')->count();
        $pengabdianSeleksi = $user->pengabdian()->where('status', 'menunggu_verifikasi')->count();
        $pengabdianLolos = $user->pengabdian()->where('status', 'terverifikasi')->count();
        
        return view('dashboard-dosen', compact(
            'penelitianUsulan',
            'penelitianBelumLengkap', 
            'penelitianSeleksi',
            'penelitianLolos',
            'pengabdianUsulan',
            'pengabdianBelumLengkap',
            'pengabdianSeleksi',
            'pengabdianLolos'
        ));
    }
}
