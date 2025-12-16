<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penelitian;
use App\Models\StatusHistory;
use App\Models\User;
use App\Http\Requests\AdminPenelitianRequest;
use App\Exceptions\WorkflowException;
use App\Exceptions\InvalidStatusTransitionException;
use App\Services\StatusWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Controller untuk verifikasi dan manajemen penelitian oleh admin
 * 
 * Menggunakan StatusWorkflowService untuk mengelola transisi status penelitian
 * dengan workflow yang konsisten dan ter-log dengan baik.
 */
class PenelitianController extends Controller
{
    protected StatusWorkflowService $workflowService;

    /**
     * Constructor dengan dependency injection StatusWorkflowService
     * 
     * @param StatusWorkflowService $workflowService Service untuk workflow management
     */
    public function __construct(StatusWorkflowService $workflowService)
    {
        $this->workflowService = $workflowService;
    }

    /**
     * Menampilkan daftar semua penelitian dengan filter dan statistik
     * 
     * @param Request $request HTTP request dengan optional parameters: year, status, search
     * @return \Illuminate\View\View View daftar penelitian dengan statistik per status
     */
    public function index(Request $request)
    {
        $query = Penelitian::with('user');
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
                  ->orWhere('sumber_dana', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Statistik Penelitian dengan status baru
        $penelitianStats = [
            'diusulkan' => (clone $query)->where('status', 'diusulkan')->count(),
            'tidak_lolos' => (clone $query)->where('status', 'tidak_lolos')->count(),
            'lolos' => (clone $query)->whereIn('status', ['lolos_perlu_revisi', 'lolos', 'revisi_pra_final'])->count(),
            'selesai' => (clone $query)->where('status', 'selesai')->count(),
        ];
        
        $penelitian = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('admin.penelitian.index', compact('penelitian', 'penelitianStats'));
    }
    
    /**
     * Menampilkan detail penelitian dengan dokumen dan history status
     * 
     * @param Penelitian $penelitian Model penelitian (route model binding)
     * @return \Illuminate\View\View View detail penelitian lengkap
     */
    public function show(Penelitian $penelitian)
    {
        $penelitian->load(['user', 'documents', 'statusHistory.changedBy']);
        return view('admin.penelitian.show', compact('penelitian'));
    }
    
    /**
     * Menampilkan form edit untuk penelitian
     * 
     * @param Penelitian $penelitian Model penelitian (route model binding)
     * @return \Illuminate\View\View View form edit
     */
    public function edit(Penelitian $penelitian)
    {
        $penelitian->load('user');
        return view('admin.penelitian.edit', compact('penelitian'));
    }
    
    /**
     * Update data penelitian (bukan status)
     * 
     * @param AdminPenelitianRequest $request Validated request
     * @param Penelitian $penelitian Model penelitian (route model binding)
     * @return \Illuminate\Http\RedirectResponse Redirect dengan status message
     */
    public function update(AdminPenelitianRequest $request, Penelitian $penelitian)
    {
        // Admin dapat mengubah status tanpa validasi workflow
        // Catatan verifikasi wajib diisi (sudah divalidasi di request)
        $penelitian->update($request->validated());
        
        return redirect()->route('admin.penelitian.index')
            ->with('success', 'Penelitian berhasil diperbarui.');
    }
    
    public function destroy(Penelitian $penelitian)
    {
        $penelitian->delete();
        
        return redirect()->route('admin.penelitian.index')
            ->with('success', 'Penelitian berhasil dihapus.');
    }
    
    /**
     * Set status penelitian menjadi 'tidak_lolos' (rejected)
     * 
     * Menggunakan StatusWorkflowService untuk memastikan transisi valid dan tercatat.
     * 
     * @param Request $request HTTP request berisi catatan_verifikasi
     * @param Penelitian $penelitian Model penelitian (route model binding)
     * @return \Illuminate\Http\RedirectResponse Redirect dengan status message
     */
    public function setTidakLolos(Request $request, Penelitian $penelitian)
    {
        try {
            $request->validate([
                'catatan' => 'required|string|max:500'
            ]);

            $this->workflowService->transitionStatus(
                model: $penelitian,
                targetStatus: 'tidak_lolos',
                notes: $request->catatan
            );

            return redirect()->route('admin.penelitian.index')
                ->with('success', $this->workflowService->getSuccessMessage('tidak_lolos', 'Penelitian'));

        } catch (WorkflowException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Set status penelitian menjadi 'lolos_perlu_revisi' (accepted with minor revision)
     * 
     * @param Request $request HTTP request berisi catatan_verifikasi
     * @param Penelitian $penelitian Model penelitian (route model binding)
     * @return \Illuminate\Http\RedirectResponse Redirect dengan status message
     */
    public function setLolosPerluRevisi(Request $request, Penelitian $penelitian)
    {
        try {
            $request->validate([
                'catatan' => 'required|string|max:500'
            ]);

            $this->workflowService->transitionStatus(
                model: $penelitian,
                targetStatus: 'lolos_perlu_revisi',
                notes: $request->catatan
            );

            return redirect()->route('admin.penelitian.index')
                ->with('success', $this->workflowService->getSuccessMessage('lolos_perlu_revisi', 'Penelitian'));

        } catch (WorkflowException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Set status penelitian menjadi 'lolos' (accepted without revision)
     * 
     * @param Request $request HTTP request berisi catatan_verifikasi
     * @param Penelitian $penelitian Model penelitian (route model binding)
     * @return \Illuminate\Http\RedirectResponse Redirect dengan status message
     */
    public function setLolos(Request $request, Penelitian $penelitian)
    {
        try {
            $request->validate([
                'catatan' => 'nullable|string|max:500'
            ]);

            $this->workflowService->transitionStatus(
                model: $penelitian,
                targetStatus: 'lolos',
                notes: $request->catatan
            );

            return redirect()->route('admin.penelitian.index')
                ->with('success', $this->workflowService->getSuccessMessage('lolos', 'Penelitian'));

        } catch (WorkflowException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Set status penelitian menjadi 'revisi_pra_final' (pre-final revision)
     * 
     * @param Request $request HTTP request berisi catatan_verifikasi
     * @param Penelitian $penelitian Model penelitian (route model binding)
     * @return \Illuminate\Http\RedirectResponse Redirect dengan status message
     */
    public function setRevisiPraFinal(Request $request, Penelitian $penelitian)
    {
        try {
            $request->validate([
                'catatan' => 'required|string|max:500'
            ]);

            $this->workflowService->transitionStatus(
                model: $penelitian,
                targetStatus: 'revisi_pra_final',
                notes: $request->catatan
            );

            return redirect()->route('admin.penelitian.index')
                ->with('success', $this->workflowService->getSuccessMessage('revisi_pra_final', 'Penelitian'));

        } catch (WorkflowException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Set status penelitian menjadi 'selesai' (completed)
     * 
     * @param Request $request HTTP request berisi catatan_verifikasi
     * @param Penelitian $penelitian Model penelitian (route model binding)
     * @return \Illuminate\Http\RedirectResponse Redirect dengan status message
     */
    public function setSelesai(Request $request, Penelitian $penelitian)
    {
        try {
            $request->validate([
                'catatan' => 'nullable|string|max:500'
            ]);

            $this->workflowService->transitionStatus(
                model: $penelitian,
                targetStatus: 'selesai',
                notes: $request->catatan
            );

            return redirect()->route('admin.penelitian.index')
                ->with('success', $this->workflowService->getSuccessMessage('selesai', 'Penelitian'));

        } catch (WorkflowException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Update catatan verifikasi tanpa mengubah status
     * 
     * Berguna untuk menambahkan notes atau feedback tanpa transisi status.
     * 
     * @param Request $request HTTP request berisi catatan_verifikasi
     * @param Penelitian $penelitian Model penelitian (route model binding)
     * @return \Illuminate\Http\RedirectResponse Redirect dengan status message
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

            $this->workflowService->updateNotes(
                model: $penelitian,
                notes: $request->catatan_verifikasi
            );

            return redirect()->back()
                ->with('success', 'Catatan verifikasi berhasil diperbarui.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}
