<?php

namespace App\Http\Controllers;

use App\Models\Penelitian;
use App\Models\User;
use App\Http\Requests\AdminPenelitianRequest;
use App\Exceptions\WorkflowException;
use App\Exceptions\InvalidStatusTransitionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

            $penelitian->update([
                'status' => 'tidak_lolos',
                'catatan_verifikasi' => $request->catatan
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

            $penelitian->update([
                'status' => 'lolos_perlu_revisi',
                'catatan_verifikasi' => $request->catatan
            ]);

            DB::commit();

            return redirect()->route('penelitian.index')
                ->with('success', 'Penelitian lolos dengan catatan revisi.');

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

            $penelitian->update([
                'status' => 'lolos',
                'catatan_verifikasi' => $request->catatan
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

            $penelitian->update([
                'status' => 'revisi_pra_final',
                'catatan_verifikasi' => $request->catatan
            ]);

            DB::commit();

            return redirect()->route('penelitian.index')
                ->with('success', 'Penelitian memerlukan revisi pra-final.');

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

            $penelitian->update([
                'status' => 'selesai',
                'catatan_verifikasi' => $request->catatan
            ]);

            DB::commit();

            return redirect()->route('penelitian.index')
                ->with('success', 'Penelitian telah selesai.');

        } catch (WorkflowException $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}
