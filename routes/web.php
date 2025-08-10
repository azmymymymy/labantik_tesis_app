

<?php

use App\Http\Controllers\DataPenelitian\AngketMinatController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DataPenelitian\AngketMotivasiController;
use App\Http\Controllers\DataPenelitian\HasilBelajarController;
use App\Http\Controllers\SiswaController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::resource('siswa', SiswaController::class);
    Route::resource('kelas', App\Http\Controllers\KelasController::class);
    Route::resource('keahlian', App\Http\Controllers\KeahlianController::class);
    Route::resource('konsentrasi', App\Http\Controllers\KonsentrasiController::class);
       Route::delete('angket-minat/clear-all', [AngketMinatController::class, 'clear'])->name('angket-minat.clear');

    Route::resource('angket-minat', AngketMinatController::class);

    


    // Edit & Update route Angket Minat (opsional, untuk kejelasan eksplisit)
    Route::get('angket-minat/{angket_minat}/edit', [AngketMinatController::class, 'edit'])->name('angket-minat.edit');
    Route::put('angket-minat/{angket_minat}', [AngketMinatController::class, 'update'])->name('angket-minat.update');

    Route::post('angket-minat/import', [AngketMinatController::class, 'import'])->name('angket-minat.import');
    Route::get('angket-minat/export', [AngketMinatController::class, 'export'])->name('angket-minat.export');
  Route::resource('angket-motivasi', AngketMotivasiController::class);
    Route::resource('hasil-belajar', HasilBelajarController::class);
});
