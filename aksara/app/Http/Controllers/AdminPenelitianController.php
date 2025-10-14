<?php

namespace App\Http\Controllers;

use App\Models\Penelitian;
use App\Models\User;
use App\Http\Requests\AdminPenelitianRequest;
use Illuminate\Http\Request;

class AdminPenelitianController extends Controller
{
    public function index(Request $request)
    {
        $query = Penelitian::with('user');
        
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
                  ->orWhere('sumber_dana', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        $penelitian = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('penelitian.index', compact('penelitian'));
    }
    
    public function show(Penelitian $penelitian)
    {
        $penelitian->load(['user', 'documents']);
        return view('admin.penelitian.show', compact('penelitian'));
    }
    
    public function edit(Penelitian $penelitian)
    {
        $penelitian->load('user');
        return view('admin.penelitian.edit', compact('penelitian'));
    }
    
    public function update(AdminPenelitianRequest $request, Penelitian $penelitian)
    {
        $penelitian->update($request->validated());
        
        return redirect()->route('penelitian.index')
            ->with('success', 'Penelitian berhasil diperbarui.');
    }
    
    public function destroy(Penelitian $penelitian)
    {
        $penelitian->delete();
        
        return redirect()->route('penelitian.index')
            ->with('success', 'Penelitian berhasil dihapus.');
    }
    
    public function verify(Request $request, Penelitian $penelitian)
    {
        $request->validate([
            'catatan' => 'nullable|string|max:500'
        ]);
        
        $penelitian->update([
            'status' => 'terverifikasi',
            'catatan_verifikasi' => $request->catatan
        ]);
        
        return redirect()->route('penelitian.index')
            ->with('success', 'Penelitian berhasil diverifikasi.');
    }
    
    public function reject(Request $request, Penelitian $penelitian)
    {
        $request->validate([
            'catatan' => 'required|string|max:500'
        ]);
        
        $penelitian->update([
            'status' => 'ditolak',
            'catatan_verifikasi' => $request->catatan
        ]);
        
        return redirect()->route('penelitian.index')
            ->with('success', 'Penelitian berhasil ditolak.');
    }
}
