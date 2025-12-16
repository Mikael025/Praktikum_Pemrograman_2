<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Informasi;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InformasiController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->query('k');
        $informasi = Informasi::query()
            ->published()
            ->visibleTo('dosen')
            ->when($category, fn($q) => $q->category($category))
            ->orderByDesc('published_at')
            ->paginate(10)
            ->withQueryString();

        return view('dosen.informasi.index', compact('informasi', 'category'));
    }

    public function show(string $slug): View
    {
        $informasi = Informasi::published()->visibleTo('dosen')->where('slug', $slug)->firstOrFail();
        return view('dosen.informasi.show', compact('informasi'));
    }
}


