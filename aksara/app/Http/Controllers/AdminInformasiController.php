<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInformasiRequest;
use App\Http\Requests\UpdateInformasiRequest;
use App\Models\Informasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;

class AdminInformasiController extends Controller
{
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

    public function create(): View
    {
        $informasi = new Informasi();
        return view('admin.informasi.create', compact('informasi'));
    }

    public function store(StoreInformasiRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        Informasi::create($data);
        return redirect()->route('admin.informasi.index')->with('status', 'Informasi created');
    }

    public function show(string $slug): View
    {
        $informasi = Informasi::where('slug', $slug)->firstOrFail();
        return view('admin.informasi.show', compact('informasi'));
    }

    public function edit(string $slug): View
    {
        $informasi = Informasi::where('slug', $slug)->firstOrFail();
        return view('admin.informasi.edit', compact('informasi'));
    }

    public function update(UpdateInformasiRequest $request, string $slug): RedirectResponse
    {
        $informasi = Informasi::where('slug', $slug)->firstOrFail();
        $data = $request->validated();
        if (empty($data['slug'])) {
            $data['slug'] = $informasi->slug; // keep existing if not provided
        }
        $informasi->update($data);
        return redirect()->route('admin.informasi.index')->with('status', 'Informasi updated');
    }

    public function destroy(string $slug): RedirectResponse
    {
        $informasi = Informasi::where('slug', $slug)->firstOrFail();
        $informasi->delete();
        return redirect()->route('admin.informasi.index')->with('status', 'Informasi deleted');
    }
}


