<?php

namespace App\Http\Controllers;

use App\Models\Pengabdian;
use App\Models\User;
use App\Http\Requests\AdminPengabdianRequest;
use Illuminate\Http\Request;

class AdminPengabdianController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengabdian::with('user');
        
        // Filter tahun
        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }
        
        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%")
                  ->orWhere('mitra', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        $pengabdian = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('pengabdian.index', compact('pengabdian'));
    }
    
    public function show(Pengabdian $pengabdian)
    {
        $pengabdian->load(['user', 'documents']);
        return view('admin.pengabdian.show', compact('pengabdian'));
    }
    
    public function edit(Pengabdian $pengabdian)
    {
        $pengabdian->load('user');
        return view('admin.pengabdian.edit', compact('pengabdian'));
    }
    
    public function update(AdminPengabdianRequest $request, Pengabdian $pengabdian)
    {
        $pengabdian->update($request->validated());
        
        return redirect()->route('pengabdian.index')
            ->with('success', 'Pengabdian berhasil diperbarui.');
    }
    
    public function destroy(Pengabdian $pengabdian)
    {
        $pengabdian->delete();
        
        return redirect()->route('pengabdian.index')
            ->with('success', 'Pengabdian berhasil dihapus.');
    }
    
    public function verify(Request $request, Pengabdian $pengabdian)
    {
        $request->validate([
            'catatan' => 'nullable|string|max:500'
        ]);
        
        $pengabdian->update([
            'status' => 'terverifikasi',
            'catatan_verifikasi' => $request->catatan
        ]);
        
        return redirect()->route('pengabdian.index')
            ->with('success', 'Pengabdian berhasil diverifikasi.');
    }
    
    public function reject(Request $request, Pengabdian $pengabdian)
    {
        $request->validate([
            'catatan' => 'required|string|max:500'
        ]);
        
        $pengabdian->update([
            'status' => 'ditolak',
            'catatan_verifikasi' => $request->catatan
        ]);
        
        return redirect()->route('pengabdian.index')
            ->with('success', 'Pengabdian berhasil ditolak.');
    }
}
