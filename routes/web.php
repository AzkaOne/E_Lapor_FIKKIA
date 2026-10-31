<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\WhistleblowingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/tracking', [PublicController::class, 'track'])->name('tracking');

// Public Whistleblowing Routes
Route::get('/whistleblowing/buat', [WhistleblowingController::class, 'create'])->name('whistleblowing.create');
Route::post('/whistleblowing', [WhistleblowingController::class, 'store'])->name('whistleblowing.store');

// Guest Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Pelapor Routes
Route::middleware(['auth', 'role:pelapor'])->prefix('pelapor')->name('pelapor.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Pelapor\ReportController::class, 'index'])->name('dashboard');
    Route::get('/laporan/buat', [\App\Http\Controllers\Pelapor\ReportController::class, 'create'])->name('create');
    Route::post('/laporan', [\App\Http\Controllers\Pelapor\ReportController::class, 'store'])->name('store');
    Route::get('/status', [\App\Http\Controllers\Pelapor\ReportController::class, 'status'])->name('status');
    Route::get('/riwayat', [\App\Http\Controllers\Pelapor\ReportController::class, 'history'])->name('history');
    Route::post('/laporan/{report}/rating', [\App\Http\Controllers\Pelapor\ReportController::class, 'rate'])->name('rate');
});

// Kabag Routes
Route::middleware(['auth', 'role:kabag'])->prefix('kabag')->name('kabag.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Kabag\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/laporan/{report}', [\App\Http\Controllers\Kabag\DashboardController::class, 'show'])->name('show');
    Route::post('/laporan/{report}/disposisi', [\App\Http\Controllers\Kabag\DispositionController::class, 'dispose'])->name('dispose');
    Route::post('/laporan/{report}/kerjakan-sendiri', [\App\Http\Controllers\Kabag\DispositionController::class, 'selfExecute'])->name('self-execute');
    Route::post('/laporan/{report}/selesai-mandiri', [\App\Http\Controllers\Kabag\DispositionController::class, 'completeSelfExecution'])->name('complete-self');
    Route::post('/laporan/{report}/tolak', [\App\Http\Controllers\Kabag\DispositionController::class, 'reject'])->name('reject');
    Route::get('/laporan-saya', [\App\Http\Controllers\Kabag\DashboardController::class, 'myReports'])->name('my-reports');
    Route::get('/riwayat', [\App\Http\Controllers\Kabag\DashboardController::class, 'history'])->name('history');

    // Whistleblowing Exclusive Tab
    Route::get('/whistleblowing', [\App\Http\Controllers\Kabag\WhistleblowingController::class, 'index'])->name('whistleblowing');
    Route::post('/whistleblowing/{report}/proses', [\App\Http\Controllers\Kabag\WhistleblowingController::class, 'process'])->name('whistleblowing.process');
    Route::post('/whistleblowing/{report}/selesai', [\App\Http\Controllers\Kabag\WhistleblowingController::class, 'complete'])->name('whistleblowing.complete');
});

Route::middleware(['auth', 'role:kasubag'])->prefix('kasubag')->name('kasubag.')->group(function () {
    Route::get('/dashboard', function () {
        return view('kasubag.dashboard');
    })->name('dashboard');
});

Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', function () {
        return view('petugas.dashboard');
    })->name('dashboard');
});
