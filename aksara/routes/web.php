<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('public.home');

// Ni hapus?
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified', 'role:dosen'])->group(function () {
    Route::get('/dashboard-dosen', [App\Http\Controllers\DosenDashboardController::class, 'index'])->name('dashboard.dosen');
    
    // Penelitian routes
    Route::resource('dosen/penelitian', App\Http\Controllers\DosenPenelitianController::class)->names([
        'index' => 'dosen.penelitian.index',
        'create' => 'dosen.penelitian.create',
        'store' => 'dosen.penelitian.store',
        'show' => 'dosen.penelitian.show',
        'edit' => 'dosen.penelitian.edit',
        'update' => 'dosen.penelitian.update',
        'destroy' => 'dosen.penelitian.destroy',
    ]);
    
    // Pengabdian routes
    Route::resource('dosen/pengabdian', App\Http\Controllers\DosenPengabdianController::class)->names([
        'index' => 'dosen.pengabdian.index',
        'create' => 'dosen.pengabdian.create',
        'store' => 'dosen.pengabdian.store',
        'show' => 'dosen.pengabdian.show',
        'edit' => 'dosen.pengabdian.edit',
        'update' => 'dosen.pengabdian.update',
        'destroy' => 'dosen.pengabdian.destroy',
    ]);
    
    // Informasi/Berita dosen (read-only)
    Route::get('/dosen/informasi', [App\Http\Controllers\DosenInformasiController::class, 'index'])->name('dosen.informasi');
    Route::get('/dosen/informasi/{slug}', [App\Http\Controllers\DosenInformasiController::class, 'show'])->name('dosen.informasi.show');

});

Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/dashboard-admin', [App\Http\Controllers\AdminDashboardController::class, 'index'])->name('dashboard.admin');
    
    // Penelitian routes
    Route::resource('penelitian', App\Http\Controllers\AdminPenelitianController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);
    Route::post('penelitian/{penelitian}/tidak-lolos', [App\Http\Controllers\AdminPenelitianController::class, 'setTidakLolos'])->name('penelitian.tidak-lolos');
    Route::post('penelitian/{penelitian}/lolos-perlu-revisi', [App\Http\Controllers\AdminPenelitianController::class, 'setLolosPerluRevisi'])->name('penelitian.lolos-perlu-revisi');
    Route::post('penelitian/{penelitian}/lolos', [App\Http\Controllers\AdminPenelitianController::class, 'setLolos'])->name('penelitian.lolos');
    Route::post('penelitian/{penelitian}/revisi-pra-final', [App\Http\Controllers\AdminPenelitianController::class, 'setRevisiPraFinal'])->name('penelitian.revisi-pra-final');
    Route::post('penelitian/{penelitian}/selesai', [App\Http\Controllers\AdminPenelitianController::class, 'setSelesai'])->name('penelitian.selesai');
    
    // Pengabdian routes
    Route::resource('pengabdian', App\Http\Controllers\AdminPengabdianController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);
    Route::post('pengabdian/{pengabdian}/tidak-lolos', [App\Http\Controllers\AdminPengabdianController::class, 'setTidakLolos'])->name('pengabdian.tidak-lolos');
    Route::post('pengabdian/{pengabdian}/lolos-perlu-revisi', [App\Http\Controllers\AdminPengabdianController::class, 'setLolosPerluRevisi'])->name('pengabdian.lolos-perlu-revisi');
    Route::post('pengabdian/{pengabdian}/lolos', [App\Http\Controllers\AdminPengabdianController::class, 'setLolos'])->name('pengabdian.lolos');
    Route::post('pengabdian/{pengabdian}/revisi-pra-final', [App\Http\Controllers\AdminPengabdianController::class, 'setRevisiPraFinal'])->name('pengabdian.revisi-pra-final');
    Route::post('pengabdian/{pengabdian}/selesai', [App\Http\Controllers\AdminPengabdianController::class, 'setSelesai'])->name('pengabdian.selesai');

    // Informasi/Berita admin CRUD (resource, slug parameter)
    Route::resource('admin/informasi', App\Http\Controllers\AdminInformasiController::class)
        ->parameters(['informasi' => 'slug'])
        ->names('admin.informasi');
});

// auth routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Public informational pages (no auth)
Route::view('/visi-misi', 'public.visi-misi')->name('public.visimisi');
Route::view('/informasi-berita/umum', 'public.news-umum')->name('public.news.umum');
Route::view('/informasi-berita/penelitian', 'public.news-penelitian')->name('public.news.penelitian');
Route::view('/informasi-berita/pengabdian', 'public.news-pengabdian')->name('public.news.pengabdian');
Route::view('/unduh', 'public.downloads')->name('public.downloads');
