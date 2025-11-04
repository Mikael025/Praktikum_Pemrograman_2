<?php

namespace App\Http\Controllers;

use App\Models\Pengabdian;
use App\Models\User;
use App\Http\Requests\AdminPengabdianRequest;
use App\Exceptions\WorkflowException;
use App\Exceptions\InvalidStatusTransitionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    
    /**
     * Set status to tidak_lolos
     */
    public function setTidakLolos(Request $request, Pengabdian $pengabdian)
    {
        try {
            if (!$pengabdian->canTransitionTo('tidak_lolos')) {
                throw new InvalidStatusTransitionException($pengabdian->status, 'tidak_lolos');
            }

            $request->validate([
                'catatan' => 'required|string|max:500'
            ]);

            DB::beginTransaction();

            $pengabdian->update([
                'status' => 'tidak_lolos',
                'catatan_verifikasi' => $request->catatan
            ]);

            DB::commit();

            return redirect()->route('pengabdian.index')
                ->with('success', 'Pengabdian berhasil ditolak.');

        } catch (WorkflowException $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Set status to lolos_perlu_revisi
     */
    public function setLolosPerluRevisi(Request $request, Pengabdian $pengabdian)
    {
        try {
            if (!$pengabdian->canTransitionTo('lolos_perlu_revisi')) {
                throw new InvalidStatusTransitionException($pengabdian->status, 'lolos_perlu_revisi');
            }

            $request->validate([
                'catatan' => 'required|string|max:500'
            ]);

            DB::beginTransaction();

            $pengabdian->update([
                'status' => 'lolos_perlu_revisi',
                'catatan_verifikasi' => $request->catatan
            ]);

            DB::commit();

            return redirect()->route('pengabdian.index')
                ->with('success', 'Pengabdian lolos dengan catatan revisi.');

        } catch (WorkflowException $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Set status to lolos
     */
    public function setLolos(Request $request, Pengabdian $pengabdian)
    {
        try {
            if (!$pengabdian->canTransitionTo('lolos')) {
                throw new InvalidStatusTransitionException($pengabdian->status, 'lolos');
            }

            $request->validate([
                'catatan' => 'nullable|string|max:500'
            ]);

            DB::beginTransaction();

            $pengabdian->update([
                'status' => 'lolos',
                'catatan_verifikasi' => $request->catatan
            ]);

            DB::commit();

            return redirect()->route('pengabdian.index')
                ->with('success', 'Pengabdian berhasil disetujui.');

        } catch (WorkflowException $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Set status to revisi_pra_final
     */
    public function setRevisiPraFinal(Request $request, Pengabdian $pengabdian)
    {
        try {
            if (!$pengabdian->canTransitionTo('revisi_pra_final')) {
                throw new InvalidStatusTransitionException($pengabdian->status, 'revisi_pra_final');
            }

            $request->validate([
                'catatan' => 'required|string|max:500'
            ]);

            DB::beginTransaction();

            $pengabdian->update([
                'status' => 'revisi_pra_final',
                'catatan_verifikasi' => $request->catatan
            ]);

            DB::commit();

            return redirect()->route('pengabdian.index')
                ->with('success', 'Pengabdian memerlukan revisi pra-final.');

        } catch (WorkflowException $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Set status to selesai
     */
    public function setSelesai(Request $request, Pengabdian $pengabdian)
    {
        try {
            if (!$pengabdian->canTransitionTo('selesai')) {
                throw new InvalidStatusTransitionException($pengabdian->status, 'selesai');
            }

            $request->validate([
                'catatan' => 'nullable|string|max:500'
            ]);

            DB::beginTransaction();

            $pengabdian->update([
                'status' => 'selesai',
                'catatan_verifikasi' => $request->catatan
            ]);

            DB::commit();

            return redirect()->route('pengabdian.index')
                ->with('success', 'Pengabdian telah selesai.');

        } catch (WorkflowException $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}
