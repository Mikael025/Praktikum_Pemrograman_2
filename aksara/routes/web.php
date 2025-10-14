<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('public.home');

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
    Route::post('dosen/penelitian/{penelitian}/upload-document', [App\Http\Controllers\DosenPenelitianController::class, 'uploadDocument'])->name('dosen.penelitian.upload-document');
    
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
    Route::post('dosen/pengabdian/{pengabdian}/upload-document', [App\Http\Controllers\DosenPengabdianController::class, 'uploadDocument'])->name('dosen.pengabdian.upload-document');
    
    // Informasi/Berita placeholder
    Route::get('/dosen/informasi', function () {
        return view('dosen.informasi');
    })->name('dosen.informasi');
});

Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/dashboard-admin', [App\Http\Controllers\AdminDashboardController::class, 'index'])->name('dashboard.admin');
    
    // Penelitian routes
    Route::resource('penelitian', App\Http\Controllers\AdminPenelitianController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);
    Route::post('penelitian/{penelitian}/verify', [App\Http\Controllers\AdminPenelitianController::class, 'verify'])->name('penelitian.verify');
    Route::post('penelitian/{penelitian}/reject', [App\Http\Controllers\AdminPenelitianController::class, 'reject'])->name('penelitian.reject');
    
    // Pengabdian routes
    Route::resource('pengabdian', App\Http\Controllers\AdminPengabdianController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);
    Route::post('pengabdian/{pengabdian}/verify', [App\Http\Controllers\AdminPengabdianController::class, 'verify'])->name('pengabdian.verify');
    Route::post('pengabdian/{pengabdian}/reject', [App\Http\Controllers\AdminPengabdianController::class, 'reject'])->name('pengabdian.reject');
});

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
