<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInformasiRequest;
use App\Http\Requests\UpdateInformasiRequest;
use App\Models\Informasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

/**
 * Controller untuk manajemen informasi/berita oleh admin
 * 
 * Menyediakan CRUD operations untuk informasi dan berita yang ditampilkan
 * di portal publik dan dashboard dosen.
 */
class InformasiController extends Controller
{
    /**
     * Menampilkan daftar semua informasi dengan pagination dan filter kategori
     * 
     * @param Request $request HTTP request dengan optional query parameter 'k' untuk filter kategori
     * @return View View daftar informasi
     */
    public function index(Request $request): View
    {
        $category = $request->query('k');
        $informasi = Informasi::query()
            ->when($category, fn($q) => $q->category($category))
            ->orderByDesc('published_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.informasi.index', compact('informasi', 'category'));
    }

    /**
     * Menampilkan form untuk membuat informasi baru
     * 
     * @return View View form create informasi
     */
    public function create(): View
    {
        $informasi = new Informasi();
        return view('admin.informasi.create', compact('informasi'));
    }

    /**
     * Menyimpan informasi baru ke database
     * 
     * @param StoreInformasiRequest $request Validated request berisi data informasi
     * @return RedirectResponse Redirect ke index dengan status message
     */
    public function store(StoreInformasiRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('informasi', 'public');
        }
        // Set default values if not provided
        if (empty($data['visibility'])) {
            $data['visibility'] = 'semua'; // visible to all roles
        }
        if (empty($data['published_at'])) {
            $data['published_at'] = now(); // publish immediately
        }
        Informasi::create($data);
        return redirect()->route('admin.informasi.index')->with('status', 'Informasi created');
    }

    /**
     * Menampilkan detail informasi berdasarkan slug
     * 
     * @param string $slug Slug unik informasi
     * @return View View detail informasi
     */
    public function show(string $slug): View
    {
        $informasi = Informasi::where('slug', $slug)->firstOrFail();
        return view('admin.informasi.show', compact('informasi'));
    }

    /**
     * Menampilkan form edit untuk informasi
     * 
     * @param string $slug Slug unik informasi
     * @return View View form edit informasi
     */
    public function edit(string $slug): View
    {
        $informasi = Informasi::where('slug', $slug)->firstOrFail();
        return view('admin.informasi.edit', compact('informasi'));
    }

    /**
     * Update informasi yang sudah ada
     * 
     * @param UpdateInformasiRequest $request Validated request berisi data update
     * @param string $slug Slug unik informasi yang akan diupdate
     * @return RedirectResponse Redirect ke index dengan status message
     */
    public function update(UpdateInformasiRequest $request, string $slug): RedirectResponse
    {
        $informasi = Informasi::where('slug', $slug)->firstOrFail();
        $data = $request->validated();
        if (empty($data['slug'])) {
            $data['slug'] = $informasi->slug; // keep existing if not provided
        }
        if ($request->hasFile('image')) {
            if ($informasi->image_path) {
                Storage::disk('public')->delete($informasi->image_path);
            }
            $data['image_path'] = $request->file('image')->store('informasi', 'public');
        }
        // Ensure visibility and published_at are set
        if (empty($data['visibility'])) {
            $data['visibility'] = $informasi->visibility ?: 'semua';
        }
        if (empty($data['published_at'])) {
            $data['published_at'] = $informasi->published_at ?: now();
        }
        $informasi->update($data);
        return redirect()->route('admin.informasi.index')->with('status', 'Informasi updated');
    }

    /**
     * Menghapus informasi dari database
     * 
     * @param string $slug Slug unik informasi yang akan dihapus
     * @return RedirectResponse Redirect ke index dengan status message
     */
    public function destroy(string $slug): RedirectResponse
    {
        $informasi = Informasi::where('slug', $slug)->firstOrFail();
        $informasi->delete();
        return redirect()->route('admin.informasi.index')->with('status', 'Informasi deleted');
    }
}


