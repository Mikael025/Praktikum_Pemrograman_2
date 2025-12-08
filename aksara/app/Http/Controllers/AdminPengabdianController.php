<?php

namespace App\Http\Controllers;

use App\Models\Pengabdian;
use App\Models\StatusHistory;
use App\Models\User;
use App\Http\Requests\AdminPengabdianRequest;
use App\Exceptions\WorkflowException;
use App\Exceptions\InvalidStatusTransitionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminPengabdianController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengabdian::with('user');
        $year = $request->get('year');

        
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
        
        // Statistik Pengabdian dengan status baru
        $pengabdianStats = [
            'diusulkan' => (clone $query)->where('status', 'diusulkan')->count(),
            'tidak_lolos' => (clone $query)->where('status', 'tidak_lolos')->count(),
            'lolos' => (clone $query)->whereIn('status', ['lolos_perlu_revisi', 'lolos', 'revisi_pra_final'])->count(),
            'selesai' => (clone $query)->where('status', 'selesai')->count(),
        ];

        $pengabdian = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('pengabdian.index', compact('pengabdian', 'pengabdianStats'));
    }
    
    public function show(Pengabdian $pengabdian)
    {
        $pengabdian->load(['user', 'documents', 'statusHistory.changedBy']);
        return view('admin.pengabdian.show', compact('pengabdian'));
    }
    
    public function edit(Pengabdian $pengabdian)
    {
        $pengabdian->load('user');
        return view('admin.pengabdian.edit', compact('pengabdian'));
    }
    
    public function update(AdminPengabdianRequest $request, Pengabdian $pengabdian)
    {
        // Admin dapat mengubah status tanpa validasi workflow
        // Catatan verifikasi wajib diisi (sudah divalidasi di request)
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

            $oldStatus = $pengabdian->status;
            
            $pengabdian->update([
                'status' => 'tidak_lolos',
                'catatan_verifikasi' => $request->catatan
            ]);

            StatusHistory::create([
                'statusable_type' => Pengabdian::class,
                'statusable_id' => $pengabdian->id,
                'old_status' => $oldStatus,
                'new_status' => 'tidak_lolos',
                'changed_by_user_id' => Auth::id(),
                'notes' => $request->catatan
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

            $oldStatus = $pengabdian->status;
            
            $pengabdian->update([
                'status' => 'lolos_perlu_revisi',
                'catatan_verifikasi' => $request->catatan
            ]);

            StatusHistory::create([
                'statusable_type' => Pengabdian::class,
                'statusable_id' => $pengabdian->id,
                'old_status' => $oldStatus,
                'new_status' => 'lolos_perlu_revisi',
                'changed_by_user_id' => Auth::id(),
                'notes' => $request->catatan
            ]);

            DB::commit();

            return redirect()->route('pengabdian.index')
                ->with('success', 'Pengabdian lolos dengan revisi.');

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

            $oldStatus = $pengabdian->status;
            
            $pengabdian->update([
                'status' => 'lolos',
                'catatan_verifikasi' => $request->catatan ?? 'Lolos'
            ]);

            StatusHistory::create([
                'statusable_type' => Pengabdian::class,
                'statusable_id' => $pengabdian->id,
                'old_status' => $oldStatus,
                'new_status' => 'lolos',
                'changed_by_user_id' => Auth::id(),
                'notes' => $request->catatan ?? 'Lolos'
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

            $oldStatus = $pengabdian->status;
            
            $pengabdian->update([
                'status' => 'revisi_pra_final',
                'catatan_verifikasi' => $request->catatan
            ]);

            StatusHistory::create([
                'statusable_type' => Pengabdian::class,
                'statusable_id' => $pengabdian->id,
                'old_status' => $oldStatus,
                'new_status' => 'revisi_pra_final',
                'changed_by_user_id' => Auth::id(),
                'notes' => $request->catatan
            ]);

            DB::commit();

            return redirect()->route('pengabdian.index')
                ->with('success', 'Pengabdian diminta revisi pra-final.');

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

            $oldStatus = $pengabdian->status;
            
            $pengabdian->update([
                'status' => 'selesai',
                'catatan_verifikasi' => 'Pengabdian selesai'
            ]);

            StatusHistory::create([
                'statusable_type' => Pengabdian::class,
                'statusable_id' => $pengabdian->id,
                'old_status' => $oldStatus,
                'new_status' => 'selesai',
                'changed_by_user_id' => Auth::id(),
                'notes' => 'Pengabdian selesai'
            ]);

            DB::commit();

            return redirect()->route('pengabdian.index')
                ->with('success', 'Pengabdian berhasil diselesaikan.');

        } catch (WorkflowException $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Update catatan verifikasi without changing status
     * Untuk memberikan feedback tambahan pada status revisi
     */
    public function updateCatatanVerifikasi(Request $request, Pengabdian $pengabdian)
    {
        try {
            // Validate that catatan has minimum quality
            $request->validate([
                'catatan_verifikasi' => 'required|string|min:10|max:500'
            ], [
                'catatan_verifikasi.required' => 'Catatan verifikasi wajib diisi.',
                'catatan_verifikasi.min' => 'Catatan verifikasi minimal 10 karakter untuk memastikan feedback berkualitas.',
                'catatan_verifikasi.max' => 'Catatan verifikasi maksimal 500 karakter.'
            ]);

            DB::beginTransaction();

            $oldCatatan = $pengabdian->catatan_verifikasi;
            
            $pengabdian->update([
                'catatan_verifikasi' => $request->catatan_verifikasi
            ]);

            // Log for audit trail
            Log::info('Catatan verifikasi updated', [
                'admin_id' => Auth::id(),
                'admin_name' => Auth::user()->name,
                'pengabdian_id' => $pengabdian->id,
                'pengabdian_judul' => $pengabdian->judul,
                'status' => $pengabdian->status,
                'old_catatan' => $oldCatatan,
                'new_catatan' => $request->catatan_verifikasi,
                'timestamp' => now()
            ]);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Catatan verifikasi berhasil diperbarui.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}
