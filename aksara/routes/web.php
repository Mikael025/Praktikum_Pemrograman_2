<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\PublicController::class, 'home'])->name('public.home');

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
    Route::delete('dosen/penelitian/{penelitian}/documents/{document}', [App\Http\Controllers\DosenPenelitianController::class, 'deleteDocument'])->name('dosen.penelitian.delete-document');
    
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
    Route::delete('dosen/pengabdian/{pengabdian}/documents/{document}', [App\Http\Controllers\DosenPengabdianController::class, 'deleteDocument'])->name('dosen.pengabdian.delete-document');
    
    // Informasi/Berita dosen (read-only)
    Route::get('/dosen/informasi', [App\Http\Controllers\DosenInformasiController::class, 'index'])->name('dosen.informasi');
    Route::get('/dosen/informasi/{slug}', [App\Http\Controllers\DosenInformasiController::class, 'show'])->name('dosen.informasi.show');
    
    // Laporan dosen
    Route::get('/dosen/laporan', [App\Http\Controllers\DosenLaporanController::class, 'index'])->name('dosen.laporan.index');
    Route::get('/dosen/laporan/export-pdf', [App\Http\Controllers\DosenLaporanController::class, 'exportPdf'])->name('dosen.laporan.export-pdf');
    Route::get('/dosen/laporan/export-csv', [App\Http\Controllers\DosenLaporanController::class, 'exportCsv'])->name('dosen.laporan.export-csv');
    Route::get('/dosen/laporan/perbandingan', [App\Http\Controllers\DosenLaporanController::class, 'perbandingan'])->name('dosen.laporan.perbandingan');

    // Document Management - Dosen
    Route::post('/documents/{type}/{document}/upload-version', [App\Http\Controllers\DocumentController::class, 'uploadVersion'])->name('documents.upload-version');
    Route::get('/documents/{type}/{document}/versions', [App\Http\Controllers\DocumentController::class, 'getVersionHistory'])->name('documents.versions');
    Route::get('/penelitian/{id}/documents/download-zip', [App\Http\Controllers\DocumentController::class, 'downloadAllAsZip'])->name('penelitian.documents.download-zip');
    Route::get('/pengabdian/{id}/documents/download-zip', [App\Http\Controllers\DocumentController::class, 'downloadAllAsZip'])->name('pengabdian.documents.download-zip');
});

Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/dashboard-admin', [App\Http\Controllers\AdminDashboardController::class, 'index'])->name('dashboard.admin');
    Route::get('/api/top-researchers', [App\Http\Controllers\AdminDashboardController::class, 'topResearchers'])->name('api.top-researchers');
    Route::get('/api/submission-heatmap', [App\Http\Controllers\AdminDashboardController::class, 'submissionHeatmap'])->name('api.submission-heatmap');
    
    // Penelitian routes
    Route::resource('penelitian', App\Http\Controllers\AdminPenelitianController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);
    Route::post('penelitian/{penelitian}/tidak-lolos', [App\Http\Controllers\AdminPenelitianController::class, 'setTidakLolos'])->name('penelitian.tidak-lolos');
    Route::post('penelitian/{penelitian}/lolos-perlu-revisi', [App\Http\Controllers\AdminPenelitianController::class, 'setLolosPerluRevisi'])->name('penelitian.lolos-perlu-revisi');
    Route::post('penelitian/{penelitian}/lolos', [App\Http\Controllers\AdminPenelitianController::class, 'setLolos'])->name('penelitian.lolos');
    Route::post('penelitian/{penelitian}/revisi-pra-final', [App\Http\Controllers\AdminPenelitianController::class, 'setRevisiPraFinal'])->name('penelitian.revisi-pra-final');
    Route::post('penelitian/{penelitian}/selesai', [App\Http\Controllers\AdminPenelitianController::class, 'setSelesai'])->name('penelitian.selesai');
    Route::patch('penelitian/{penelitian}/catatan-verifikasi', [App\Http\Controllers\AdminPenelitianController::class, 'updateCatatanVerifikasi'])->name('penelitian.update-catatan');
    
    // Pengabdian routes
    Route::resource('pengabdian', App\Http\Controllers\AdminPengabdianController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);
    Route::post('pengabdian/{pengabdian}/tidak-lolos', [App\Http\Controllers\AdminPengabdianController::class, 'setTidakLolos'])->name('pengabdian.tidak-lolos');
    Route::post('pengabdian/{pengabdian}/lolos-perlu-revisi', [App\Http\Controllers\AdminPengabdianController::class, 'setLolosPerluRevisi'])->name('pengabdian.lolos-perlu-revisi');
    Route::post('pengabdian/{pengabdian}/lolos', [App\Http\Controllers\AdminPengabdianController::class, 'setLolos'])->name('pengabdian.lolos');
    Route::post('pengabdian/{pengabdian}/revisi-pra-final', [App\Http\Controllers\AdminPengabdianController::class, 'setRevisiPraFinal'])->name('pengabdian.revisi-pra-final');
    Route::post('pengabdian/{pengabdian}/selesai', [App\Http\Controllers\AdminPengabdianController::class, 'setSelesai'])->name('pengabdian.selesai');
    Route::patch('pengabdian/{pengabdian}/catatan-verifikasi', [App\Http\Controllers\AdminPengabdianController::class, 'updateCatatanVerifikasi'])->name('pengabdian.update-catatan');

    // Informasi/Berita admin CRUD (resource, slug parameter)
    Route::resource('admin/informasi', App\Http\Controllers\AdminInformasiController::class)
        ->parameters(['informasi' => 'slug'])
        ->names('admin.informasi');
    
    // Laporan admin
    Route::get('/admin/laporan', [App\Http\Controllers\AdminLaporanController::class, 'index'])->name('admin.laporan.index');
    Route::get('/admin/laporan/export-pdf', [App\Http\Controllers\AdminLaporanController::class, 'exportPdf'])->name('admin.laporan.export-pdf');
    Route::get('/admin/laporan/export-csv', [App\Http\Controllers\AdminLaporanController::class, 'exportCsv'])->name('admin.laporan.export-csv');
    Route::get('/admin/laporan/perbandingan', [App\Http\Controllers\AdminLaporanController::class, 'perbandingan'])->name('admin.laporan.perbandingan');

    // Document Management - Admin
    Route::post('/documents/{type}/{document}/verify', [App\Http\Controllers\DocumentController::class, 'verify'])->name('documents.verify');
    Route::post('/documents/{type}/{document}/reject', [App\Http\Controllers\DocumentController::class, 'reject'])->name('documents.reject');
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
Route::get('/informasi-berita', function () {
    return redirect()->route('public.news', ['category' => 'semua']);
})->name('public.news.index');
Route::get('/informasi-berita/{category}', [App\Http\Controllers\PublicController::class, 'newsByCategory'])->name('public.news');
Route::get('/informasi-berita/{category}/{slug}', [App\Http\Controllers\PublicController::class, 'newsDetail'])->name('public.news.detail');
Route::get('/unduh', [App\Http\Controllers\PublicController::class, 'downloads'])->name('public.downloads');
Route::get('/unduh/{type}/{id}', [App\Http\Controllers\PublicController::class, 'downloadDocument'])->name('public.download.document');

// Backward compatibility routes
Route::redirect('/informasi-berita/umum', '/informasi-berita/umum', 301)->name('public.news.umum');
Route::redirect('/informasi-berita/penelitian', '/informasi-berita/penelitian', 301)->name('public.news.penelitian');
Route::redirect('/informasi-berita/pengabdian', '/informasi-berita/pengabdian', 301)->name('public.news.pengabdian');
