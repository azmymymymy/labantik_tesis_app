<?php

use App\Http\Controllers\DataPenelitian\AngketMinatController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DataPenelitian\AhpIndividuController;
use App\Http\Controllers\DataPenelitian\AhpKelompokController;
use App\Http\Controllers\DataPenelitian\AngketMotivasiController;
use App\Http\Controllers\DataPenelitian\HasilBelajarController;
use App\Http\Controllers\DataPenelitian\ObservasiController;
use App\Http\Controllers\SiswaController;

Route::get('/', function () {
    return redirect()->route('login');
})->middleware('guest');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');

Route::get('/register', action: [RegisterController::class, 'showRegistrationForm'])->name('register')->middleware('guest');
Route::post('/register', [RegisterController::class, 'register'])->middleware('guest');

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Basic CRUD routes
    Route::delete('siswa/clear-all', [SiswaController::class, 'clear'])->name('siswa.clear');
    Route::resource('siswa', SiswaController::class);
    Route::resource('kelas', App\Http\Controllers\KelasController::class);
    Route::resource('keahlian', App\Http\Controllers\KeahlianController::class);
    Route::resource('konsentrasi', App\Http\Controllers\KonsentrasiController::class);
    
    // Angket Minat routes
    Route::delete('angket-minat/clear-all', [AngketMinatController::class, 'clear'])->name('angket-minat.clear');
    Route::get('angket-minat/daftar', [AngketMinatController::class, 'daftar'])->name('angket-minat.daftar');
    Route::get('angket-minat/{angket_minat}/edit', [AngketMinatController::class, 'edit'])->name('angket-minat.edit');
    Route::put('angket-minat/{angket_minat}', [AngketMinatController::class, 'update'])->name('angket-minat.update');
    Route::post('angket-minat/import', [AngketMinatController::class, 'import'])->name('angket-minat.import');
    Route::get('angket-minat/export', [AngketMinatController::class, 'export'])->name('angket-minat.export');
    Route::resource('angket-minat', AngketMinatController::class);
    
    // Angket Motivasi routes
    Route::get('angket-motivasi/daftar', [AngketMotivasiController::class, 'daftar'])->name('angket-motivasi.daftar');
    Route::resource('angket-motivasi', AngketMotivasiController::class)->except(['show']);
    
    // Other resources
    Route::resource('hasil-belajar', HasilBelajarController::class);
    Route::get('observasi/daftar', [ObservasiController::class, 'daftar'])->name('observasi.daftar');
    Route::resource('observasi', ObservasiController::class);
    Route::resource('ahp-kelompok', AhpKelompokController::class);

    // ===== AHP INDIVIDU ROUTES - FIXED =====
    // PENTING: Route spesifik harus di atas route resource/generic
    Route::prefix('ahp')->name('ahp.')->group(function () {
        Route::get('/individu', [AhpIndividuController::class, 'index'])->name('individu.index');
        Route::get('/calculate/{siswa_id}', [AhpIndividuController::class, 'calculateAHP'])->name('calculate');
        Route::get('/search-siswa', [AhpIndividuController::class, 'searchSiswa'])->name('search-siswa');
        Route::get('/siswa-list', [AhpIndividuController::class, 'getSiswaList'])->name('siswa-list');
        Route::get('/bulk-analysis', [AhpIndividuController::class, 'bulkAnalysis'])->name('bulk-analysis');
    });
    
    // Resource route untuk AHP (jika masih diperlukan untuk CRUD lain)
    Route::resource('ahp', AhpIndividuController::class);
});