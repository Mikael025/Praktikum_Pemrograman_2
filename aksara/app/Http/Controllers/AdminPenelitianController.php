<?php

namespace App\Http\Controllers;

use App\Models\Penelitian;
use App\Models\StatusHistory;
use App\Models\User;
use App\Http\Requests\AdminPenelitianRequest;
use App\Exceptions\WorkflowException;
use App\Exceptions\InvalidStatusTransitionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $penelitian->load(['user', 'documents', 'statusHistory.changedBy']);
        return view('admin.penelitian.show', compact('penelitian'));
    }
    
    public function edit(Penelitian $penelitian)
    {
        $penelitian->load('user');
        return view('admin.penelitian.edit', compact('penelitian'));
    }
    
    public function update(AdminPenelitianRequest $request, Penelitian $penelitian)
    {
        // Admin dapat mengubah status tanpa validasi workflow
        // Catatan verifikasi wajib diisi (sudah divalidasi di request)
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
    
    /**
     * Set status to tidak_lolos
     */
    public function setTidakLolos(Request $request, Penelitian $penelitian)
    {
        try {
            if (!$penelitian->canTransitionTo('tidak_lolos')) {
                throw new InvalidStatusTransitionException($penelitian->status, 'tidak_lolos');
            }

            $request->validate([
                'catatan' => 'required|string|max:500'
            ]);

            DB::beginTransaction();

            $oldStatus = $penelitian->status;
            
            $penelitian->update([
                'status' => 'tidak_lolos',
                'catatan_verifikasi' => $request->catatan
            ]);

            StatusHistory::create([
                'statusable_type' => Penelitian::class,
                'statusable_id' => $penelitian->id,
                'old_status' => $oldStatus,
                'new_status' => 'tidak_lolos',
                'changed_by_user_id' => Auth::id(),
                'notes' => $request->catatan
            ]);

            DB::commit();

            return redirect()->route('penelitian.index')
                ->with('success', 'Penelitian berhasil ditolak.');

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
    public function setLolosPerluRevisi(Request $request, Penelitian $penelitian)
    {
        try {
            if (!$penelitian->canTransitionTo('lolos_perlu_revisi')) {
                throw new InvalidStatusTransitionException($penelitian->status, 'lolos_perlu_revisi');
            }

            $request->validate([
                'catatan' => 'required|string|max:500'
            ]);

            DB::beginTransaction();

            $oldStatus = $penelitian->status;
            
            $penelitian->update([
                'status' => 'lolos_perlu_revisi',
                'catatan_verifikasi' => $request->catatan
            ]);

            StatusHistory::create([
                'statusable_type' => Penelitian::class,
                'statusable_id' => $penelitian->id,
                'old_status' => $oldStatus,
                'new_status' => 'lolos_perlu_revisi',
                'changed_by_user_id' => Auth::id(),
                'notes' => $request->catatan
            ]);

            DB::commit();

            return redirect()->route('penelitian.index')
                ->with('success', 'Penelitian lolos dengan revisi.');

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
    public function setLolos(Request $request, Penelitian $penelitian)
    {
        try {
            if (!$penelitian->canTransitionTo('lolos')) {
                throw new InvalidStatusTransitionException($penelitian->status, 'lolos');
            }

            $request->validate([
                'catatan' => 'nullable|string|max:500'
            ]);

            DB::beginTransaction();

            $oldStatus = $penelitian->status;
            
            $penelitian->update([
                'status' => 'lolos',
                'catatan_verifikasi' => $request->catatan ?? 'Lolos'
            ]);

            StatusHistory::create([
                'statusable_type' => Penelitian::class,
                'statusable_id' => $penelitian->id,
                'old_status' => $oldStatus,
                'new_status' => 'lolos',
                'changed_by_user_id' => Auth::id(),
                'notes' => $request->catatan ?? 'Lolos'
            ]);

            DB::commit();

            return redirect()->route('penelitian.index')
                ->with('success', 'Penelitian berhasil disetujui.');

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
    public function setRevisiPraFinal(Request $request, Penelitian $penelitian)
    {
        try {
            if (!$penelitian->canTransitionTo('revisi_pra_final')) {
                throw new InvalidStatusTransitionException($penelitian->status, 'revisi_pra_final');
            }

            $request->validate([
                'catatan' => 'required|string|max:500'
            ]);

            DB::beginTransaction();

            $oldStatus = $penelitian->status;
            
            $penelitian->update([
                'status' => 'revisi_pra_final',
                'catatan_verifikasi' => $request->catatan
            ]);

            StatusHistory::create([
                'statusable_type' => Penelitian::class,
                'statusable_id' => $penelitian->id,
                'old_status' => $oldStatus,
                'new_status' => 'revisi_pra_final',
                'changed_by_user_id' => Auth::id(),
                'notes' => $request->catatan
            ]);

            DB::commit();

            return redirect()->route('penelitian.index')
                ->with('success', 'Penelitian diminta revisi pra-final.');

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
    public function setSelesai(Request $request, Penelitian $penelitian)
    {
        try {
            if (!$penelitian->canTransitionTo('selesai')) {
                throw new InvalidStatusTransitionException($penelitian->status, 'selesai');
            }

            $request->validate([
                'catatan' => 'nullable|string|max:500'
            ]);

            DB::beginTransaction();

            $oldStatus = $penelitian->status;
            
            $penelitian->update([
                'status' => 'selesai',
                'catatan_verifikasi' => 'Penelitian selesai'
            ]);

            StatusHistory::create([
                'statusable_type' => Penelitian::class,
                'statusable_id' => $penelitian->id,
                'old_status' => $oldStatus,
                'new_status' => 'selesai',
                'changed_by_user_id' => Auth::id(),
                'notes' => 'Penelitian selesai'
            ]);

            DB::commit();

            return redirect()->route('penelitian.index')
                ->with('success', 'Penelitian berhasil diselesaikan.');

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
    public function updateCatatanVerifikasi(Request $request, Penelitian $penelitian)
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

            $oldCatatan = $penelitian->catatan_verifikasi;
            
            $penelitian->update([
                'catatan_verifikasi' => $request->catatan_verifikasi
            ]);

            // Log for audit trail
            Log::info('Catatan verifikasi updated', [
                'admin_id' => auth()->id(),
                'admin_name' => auth()->user()->name,
                'penelitian_id' => $penelitian->id,
                'penelitian_judul' => $penelitian->judul,
                'status' => $penelitian->status,
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
