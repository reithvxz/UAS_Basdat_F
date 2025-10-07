<?php
// Rute Kelompok Okan

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\AdminDashboardController;
use App\Models\Mahasiswa;

Route::get('/', function () {
    return view('landingpage');
})->name('landing');

// Rute untuk Mahasiswa (Guard Mahasiswa)
Route::middleware('auth:mahasiswa')->group(function() {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/status', [SuratController::class, 'index'])->name('status');
    Route::get('/pengajuan', [SuratController::class, 'create'])->name('pengajuan.create');
    Route::post('/pengajuan', [SuratController::class, 'store'])->name('pengajuan.store');
    Route::delete('/surat/{surat}/batal', [SuratController::class, 'destroy'])->name('surat.batal');
    Route::get('/surat/{surat}/tracking', [SuratController::class, 'tracking'])->name('surat.tracking');
    Route::get('/template', [TemplateController::class, 'index'])->name('template.index');
    Route::get('/ajax/template-link', [TemplateController::class, 'getLink'])->name('ajax.template.link');
});

// Rute untuk Admin (Guard Web/Users)
Route::middleware('auth:web')->group(function() {
    // TAMBAHKAN ROUTE INI untuk halaman dashboard admin
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/surat-masuk', [ApprovalController::class, 'index'])->name('surat.masuk');
    Route::get('/surat/{surat}/periksa', [ApprovalController::class, 'show'])->name('surat.periksa');
    Route::post('/surat/{surat}/approve', [ApprovalController::class, 'approve'])->name('surat.approve');
    Route::post('/surat/{surat}/reject', [ApprovalController::class, 'reject'])->name('surat.reject');
});

// Rute umum yang bisa diakses kedua guard
Route::middleware('auth:web,mahasiswa')->group(function(){
    // Filepath di-encode agar tidak ada karakter aneh di URL
    Route::get('/preview/{filepath}', [ApprovalController::class, 'preview'])->name('file.preview');
});


// Rute autentikasi bawaan Breeze
require __DIR__.'/auth.php';

// DIHAPUS: Rute '/reset-password-test' adalah rute sementara untuk debugging
// dan sebaiknya dihapus agar kode tetap bersih.