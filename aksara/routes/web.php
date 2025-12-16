<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Dosen;
use App\Http\Controllers\Public\PublicController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'home'])->name('public.home');

// Redirect dashboard berdasarkan role
Route::get('/dashboard', function () {
    $user = Illuminate\Support\Facades\Auth::user();
    if ($user->role === 'admin') {
        return redirect()->route('dashboard.admin');
    } else {
        return redirect()->route('dashboard.dosen');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified', 'role:dosen'])->group(function () {
    Route::get('/dashboard-dosen', [Dosen\DashboardController::class, 'index'])->name('dashboard.dosen');
    
    // Penelitian routes
    Route::resource('dosen/penelitian', Dosen\PenelitianController::class)->names([
        'index' => 'dosen.penelitian.index',
        'create' => 'dosen.penelitian.create',
        'store' => 'dosen.penelitian.store',
        'show' => 'dosen.penelitian.show',
        'edit' => 'dosen.penelitian.edit',
        'update' => 'dosen.penelitian.update',
        'destroy' => 'dosen.penelitian.destroy',
    ]);
    Route::delete('dosen/penelitian/{penelitian}/documents/{document}', [Dosen\PenelitianController::class, 'deleteDocument'])->name('dosen.penelitian.delete-document');
    
    // Pengabdian routes
    Route::resource('dosen/pengabdian', Dosen\PengabdianController::class)->names([
        'index' => 'dosen.pengabdian.index',
        'create' => 'dosen.pengabdian.create',
        'store' => 'dosen.pengabdian.store',
        'show' => 'dosen.pengabdian.show',
        'edit' => 'dosen.pengabdian.edit',
        'update' => 'dosen.pengabdian.update',
        'destroy' => 'dosen.pengabdian.destroy',
    ]);
    Route::delete('dosen/pengabdian/{pengabdian}/documents/{document}', [Dosen\PengabdianController::class, 'deleteDocument'])->name('dosen.pengabdian.delete-document');
    
    // Informasi/Berita dosen (read-only)
    Route::get('/dosen/informasi', [Dosen\InformasiController::class, 'index'])->name('dosen.informasi');
    Route::get('/dosen/informasi/{slug}', [Dosen\InformasiController::class, 'show'])->name('dosen.informasi.show');
    
    // Laporan dosen
    Route::get('/dosen/laporan', [Dosen\LaporanController::class, 'index'])->name('dosen.laporan.index');
    Route::get('/dosen/laporan/export-pdf', [Dosen\LaporanController::class, 'exportPdf'])->name('dosen.laporan.export-pdf');
    Route::get('/dosen/laporan/export-csv', [Dosen\LaporanController::class, 'exportCsv'])->name('dosen.laporan.export-csv');
    Route::get('/dosen/laporan/perbandingan', [Dosen\LaporanController::class, 'perbandingan'])->name('dosen.laporan.perbandingan');

    // Document Management - Dosen
    Route::post('/documents/{type}/{document}/upload-version', [App\Http\Controllers\DocumentController::class, 'uploadVersion'])->name('documents.upload-version');
    Route::get('/documents/{type}/{document}/versions', [App\Http\Controllers\DocumentController::class, 'getVersionHistory'])->name('documents.versions');
    Route::get('/penelitian/{id}/documents/download-zip', [App\Http\Controllers\DocumentController::class, 'downloadAllAsZip'])->name('penelitian.documents.download-zip');
    Route::get('/pengabdian/{id}/documents/download-zip', [App\Http\Controllers\DocumentController::class, 'downloadAllAsZip'])->name('pengabdian.documents.download-zip');
});

Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/dashboard-admin', [Admin\DashboardController::class, 'index'])->name('dashboard.admin');
    Route::get('/api/top-researchers', [Admin\DashboardController::class, 'topResearchers'])->name('api.top-researchers');
    Route::get('/api/submission-heatmap', [Admin\DashboardController::class, 'submissionHeatmap'])->name('api.submission-heatmap');
    
    // Penelitian routes
    Route::resource('penelitian', Admin\PenelitianController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);
    Route::post('penelitian/{penelitian}/tidak-lolos', [Admin\PenelitianController::class, 'setTidakLolos'])->name('penelitian.tidak-lolos');
    Route::post('penelitian/{penelitian}/lolos-perlu-revisi', [Admin\PenelitianController::class, 'setLolosPerluRevisi'])->name('penelitian.lolos-perlu-revisi');
    Route::post('penelitian/{penelitian}/lolos', [Admin\PenelitianController::class, 'setLolos'])->name('penelitian.lolos');
    Route::post('penelitian/{penelitian}/revisi-pra-final', [Admin\PenelitianController::class, 'setRevisiPraFinal'])->name('penelitian.revisi-pra-final');
    Route::post('penelitian/{penelitian}/selesai', [Admin\PenelitianController::class, 'setSelesai'])->name('penelitian.selesai');
    Route::patch('penelitian/{penelitian}/catatan-verifikasi', [Admin\PenelitianController::class, 'updateCatatanVerifikasi'])->name('penelitian.update-catatan');
    
    // Pengabdian routes
    Route::resource('pengabdian', Admin\PengabdianController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);
    Route::post('pengabdian/{pengabdian}/tidak-lolos', [Admin\PengabdianController::class, 'setTidakLolos'])->name('pengabdian.tidak-lolos');
    Route::post('pengabdian/{pengabdian}/lolos-perlu-revisi', [Admin\PengabdianController::class, 'setLolosPerluRevisi'])->name('pengabdian.lolos-perlu-revisi');
    Route::post('pengabdian/{pengabdian}/lolos', [Admin\PengabdianController::class, 'setLolos'])->name('pengabdian.lolos');
    Route::post('pengabdian/{pengabdian}/revisi-pra-final', [Admin\PengabdianController::class, 'setRevisiPraFinal'])->name('pengabdian.revisi-pra-final');
    Route::post('pengabdian/{pengabdian}/selesai', [Admin\PengabdianController::class, 'setSelesai'])->name('pengabdian.selesai');
    Route::patch('pengabdian/{pengabdian}/catatan-verifikasi', [Admin\PengabdianController::class, 'updateCatatanVerifikasi'])->name('pengabdian.update-catatan');

    // Informasi/Berita admin CRUD (resource, slug parameter)
    Route::resource('admin/informasi', Admin\InformasiController::class)
        ->parameters(['informasi' => 'slug'])
        ->names('admin.informasi');
    
    // Laporan admin
    Route::get('/admin/laporan', [Admin\LaporanController::class, 'index'])->name('admin.laporan.index');
    Route::get('/admin/laporan/export-pdf', [Admin\LaporanController::class, 'exportPdf'])->name('admin.laporan.export-pdf');
    Route::get('/admin/laporan/export-csv', [Admin\LaporanController::class, 'exportCsv'])->name('admin.laporan.export-csv');
    Route::get('/admin/laporan/perbandingan', [Admin\LaporanController::class, 'perbandingan'])->name('admin.laporan.perbandingan');

    // Document Management - Admin
    Route::post('/documents/{type}/{document}/verify', [App\Http\Controllers\DocumentController::class, 'verify'])->name('documents.verify');
    Route::post('/documents/{type}/{document}/reject', [App\Http\Controllers\DocumentController::class, 'reject'])->name('documents.reject');
});

// Admin profile routes
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/admin/profile', [App\Http\Controllers\ProfileAdminController::class, 'edit'])->name('profile.admin.edit');
    Route::patch('/admin/profile', [App\Http\Controllers\ProfileAdminController::class, 'update'])->name('profile.admin.update');
    Route::delete('/admin/profile', [App\Http\Controllers\ProfileAdminController::class, 'destroy'])->name('profile.admin.destroy');
});

// Dosen profile routes
Route::middleware(['auth', 'verified', 'role:dosen'])->group(function () {
    Route::get('/dosen/profile', [App\Http\Controllers\ProfileDosenController::class, 'edit'])->name('profile.dosen.edit');
    Route::patch('/dosen/profile', [App\Http\Controllers\ProfileDosenController::class, 'update'])->name('profile.dosen.update');
    Route::delete('/dosen/profile', [App\Http\Controllers\ProfileDosenController::class, 'destroy'])->name('profile.dosen.destroy');
});

require __DIR__.'/auth.php';

// Public informational pages (no auth)
Route::view('/visi-misi', 'public.visi-misi')->name('public.visimisi');
Route::get('/informasi-berita', function () {
    return redirect()->route('public.news', ['category' => 'semua']);
})->name('public.news.index');
Route::get('/informasi-berita/{category}', [PublicController::class, 'newsByCategory'])->name('public.news');
Route::get('/informasi-berita/{category}/{slug}', [PublicController::class, 'newsDetail'])->name('public.news.detail');
Route::get('/unduh', [PublicController::class, 'downloads'])->name('public.downloads');
Route::get('/unduh/{type}/{id}', [PublicController::class, 'downloadDocument'])->name('public.download.document');

// Backward compatibility routes
Route::redirect('/informasi-berita/umum', '/informasi-berita/umum', 301)->name('public.news.umum');
Route::redirect('/informasi-berita/penelitian', '/informasi-berita/penelitian', 301)->name('public.news.penelitian');
Route::redirect('/informasi-berita/pengabdian', '/informasi-berita/pengabdian', 301)->name('public.news.pengabdian');
