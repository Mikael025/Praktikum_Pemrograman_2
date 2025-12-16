<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengabdian;
use App\Models\StatusHistory;
use App\Models\User;
use App\Http\Requests\AdminPengabdianRequest;
use App\Exceptions\WorkflowException;
use App\Exceptions\InvalidStatusTransitionException;
use App\Services\StatusWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Controller untuk verifikasi dan manajemen pengabdian masyarakat oleh admin
 * 
 * Menggunakan StatusWorkflowService untuk mengelola transisi status pengabdian
 * dengan workflow yang konsisten dan ter-log dengan baik.
 */
class PengabdianController extends Controller
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
        
        return view('admin.pengabdian.index', compact('pengabdian', 'pengabdianStats'));
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
        
        return redirect()->route('admin.pengabdian.index')
            ->with('success', 'Pengabdian berhasil diperbarui.');
    }
    
    public function destroy(Pengabdian $pengabdian)
    {
        $pengabdian->delete();
        
        return redirect()->route('admin.pengabdian.index')
            ->with('success', 'Pengabdian berhasil dihapus.');
    }
    
    /**
     * Set status to tidak_lolos
     * 
     * @param Request $request
     * @param Pengabdian $pengabdian
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setTidakLolos(Request $request, Pengabdian $pengabdian)
    {
        try {
            // Validate catatan required untuk penolakan
            $request->validate([
                'catatan' => 'required|string|max:500'
            ]);

            // Use service untuk handle status transition
            $this->workflowService->transitionStatus(
                model: $pengabdian,
                targetStatus: 'tidak_lolos',
                notes: $request->catatan
            );

            return redirect()->route('admin.pengabdian.index')
                ->with('success', $this->workflowService->getSuccessMessage('tidak_lolos', 'Pengabdian'));

        } catch (WorkflowException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Set status to lolos_perlu_revisi
     * 
     * @param Request $request
     * @param Pengabdian $pengabdian
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setLolosPerluRevisi(Request $request, Pengabdian $pengabdian)
    {
        try {
            // Validate catatan required untuk feedback revisi
            $request->validate([
                'catatan' => 'required|string|max:500'
            ]);

            // Use service untuk handle status transition
            $this->workflowService->transitionStatus(
                model: $pengabdian,
                targetStatus: 'lolos_perlu_revisi',
                notes: $request->catatan
            );

            return redirect()->route('admin.pengabdian.index')
                ->with('success', $this->workflowService->getSuccessMessage('lolos_perlu_revisi', 'Pengabdian'));

        } catch (WorkflowException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Set status to lolos
     * 
     * @param Request $request
     * @param Pengabdian $pengabdian
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setLolos(Request $request, Pengabdian $pengabdian)
    {
        try {
            // Validate catatan optional untuk approval
            $request->validate([
                'catatan' => 'nullable|string|max:500'
            ]);

            // Use service untuk handle status transition
            $this->workflowService->transitionStatus(
                model: $pengabdian,
                targetStatus: 'lolos',
                notes: $request->catatan
            );

            return redirect()->route('admin.pengabdian.index')
                ->with('success', $this->workflowService->getSuccessMessage('lolos', 'Pengabdian'));

        } catch (WorkflowException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Set status to revisi_pra_final
     * 
     * @param Request $request
     * @param Pengabdian $pengabdian
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setRevisiPraFinal(Request $request, Pengabdian $pengabdian)
    {
        try {
            // Validate catatan required untuk feedback revisi pra-final
            $request->validate([
                'catatan' => 'required|string|max:500'
            ]);

            // Use service untuk handle status transition
            $this->workflowService->transitionStatus(
                model: $pengabdian,
                targetStatus: 'revisi_pra_final',
                notes: $request->catatan
            );

            return redirect()->route('admin.pengabdian.index')
                ->with('success', $this->workflowService->getSuccessMessage('revisi_pra_final', 'Pengabdian'));

        } catch (WorkflowException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Set status to selesai
     * 
     * @param Request $request
     * @param Pengabdian $pengabdian
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setSelesai(Request $request, Pengabdian $pengabdian)
    {
        try {
            // Validate catatan optional untuk completion
            $request->validate([
                'catatan' => 'nullable|string|max:500'
            ]);

            // Use service untuk handle status transition
            $this->workflowService->transitionStatus(
                model: $pengabdian,
                targetStatus: 'selesai',
                notes: $request->catatan
            );

            return redirect()->route('admin.pengabdian.index')
                ->with('success', $this->workflowService->getSuccessMessage('selesai', 'Pengabdian'));

        } catch (WorkflowException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Update catatan verifikasi without changing status
     * Untuk memberikan feedback tambahan pada status revisi
     * 
     * @param Request $request
     * @param Pengabdian $pengabdian
     * @return \Illuminate\Http\RedirectResponse
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

            // Use service untuk update notes tanpa change status
            $this->workflowService->updateNotes(
                model: $pengabdian,
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
