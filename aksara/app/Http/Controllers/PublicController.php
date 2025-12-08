<?php

namespace App\Http\Controllers;

use App\Models\Informasi;
use Illuminate\View\View;

class PublicController extends Controller
{
    /**
     * Show the public home page with featured informasi/berita
     */
    public function home(): View
    {
        // Get 3 most recent published and visible berita
        $featuredBerita = Informasi::published()
            ->visibleTo('guest')
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        return view('welcome', compact('featuredBerita'));
    }

    /**
     * Show news by category (umum, penelitian, pengabdian)
     */
    public function newsByCategory(string $category): View
    {
        $validCategories = ['umum', 'penelitian', 'pengabdian'];
        
        if (!in_array($category, $validCategories)) {
            abort(404);
        }

        $informasi = Informasi::published()
            ->visibleTo('guest')
            ->where('category', $category)
            ->orderByDesc('published_at')
            ->paginate(10);

        $categoryLabel = match($category) {
            'penelitian' => 'Penelitian',
            'pengabdian' => 'Pengabdian Masyarakat',
            default => 'Umum'
        };

        return view('public.news-category', compact('informasi', 'category', 'categoryLabel'));
    }
}

