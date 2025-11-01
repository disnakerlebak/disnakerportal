<?php

use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;

// Breeze (profile bawaan)
use App\Http\Controllers\ProfileController as BreezeProfileController;

// Pencaker
use App\Http\Controllers\Pencaker\ProfileController as PencakerProfileController;
use App\Http\Controllers\Pencaker\EducationController;
use App\Http\Controllers\Pencaker\TrainingController;
use App\Http\Controllers\Pencaker\WorkController;
use App\Http\Controllers\Pencaker\JobPreferenceController;
use App\Http\Controllers\Pencaker\CardApplicationController;

// Admin
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CardVerificationController;
use App\Http\Controllers\Admin\JobseekerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman utama
Route::get('/', fn () => view('welcome'));

// Dashboard switcher per-role
Route::get('/dashboard', function () {
    $user = auth()->user();

    return match ($user->role) {
        'admin'       => redirect()->route('admin.dashboard'),
        'perusahaan'  => redirect()->route('perusahaan.dashboard'),
        'pencaker'    => redirect()->route('pencaker.dashboard'),
        default       => view('dashboard'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

/* ===================== ADMIN ===================== */
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {
        // Dashboard Admin
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

               // Verifikasi AK1
        Route::get('/ak1', [CardVerificationController::class, 'index'])->name('ak1.index');
        Route::get('/ak1/{application}', [CardVerificationController::class, 'show'])->name('ak1.show');
        Route::post('/ak1/{application}/approve', [CardVerificationController::class, 'approve'])->name('ak1.approve');
        Route::post('/ak1/{application}/reject', [CardVerificationController::class, 'reject'])->name('ak1.reject');
        Route::post('/ak1/{application}/revision', [CardVerificationController::class, 'requestRevision'])->name('ak1.revision');
        Route::post('/ak1/{application}/printed', [CardVerificationController::class, 'markPrinted'])->name('ak1.printed');
        Route::post('/ak1/{application}/picked-up', [CardVerificationController::class, 'markPickedUp'])->name('ak1.picked');

        // Daftar Pencari Kerja (kalau ada)
        Route::get('/pencaker', [JobseekerController::class, 'index'])->name('pencaker.index');
        Route::get('/pencaker/{user}', [JobseekerController::class, 'show'])->name('pencaker.show');

        // routes/detail ak1
        Route::get('/ak1/{application}/detail', [CardVerificationController::class, 'ajaxDetail'])
        ->name('admin.ak1.ajaxDetail');

        // Cetak PDF Kartu Pencaker
        Route::get('/ak1/{application}/cetak', [CardVerificationController::class, 'cetakPdf'])
        ->name('ak1.cetak');

        // Alasan Penolakan (CRUD via modal AJAX)
Route::resource('rejection-reasons', \App\Http\Controllers\Admin\RejectionReasonController::class)
->except(['show']);

    });


/* ===================== PERUSAHAAN ===================== */
Route::middleware(['auth', 'role:perusahaan'])
    ->prefix('perusahaan')
    ->as('perusahaan.')
    ->group(function () {
        Route::get('/dashboard', fn () => view('perusahaan.dashboard'))->name('dashboard');
    });

/* ===================== PENCAKER ===================== */
Route::middleware(['auth', 'role:pencaker'])
    ->prefix('pencaker')
    ->as('pencaker.')
    ->group(function () {
        Route::get('/dashboard', fn () => view('pencaker.dashboard'))->name('dashboard');

        // Data Diri
        Route::get('/profile',  [PencakerProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/profile', [PencakerProfileController::class, 'store'])->name('profile.store');
        Route::put('/profile',  [PencakerProfileController::class, 'update'])->name('profile.update');

        // Pendidikan / Pelatihan / Riwayat Kerja
        Route::resource('education', EducationController::class);
        Route::resource('training',  TrainingController::class)->only(['index','store','update','destroy']);
        Route::resource('work',      WorkController::class)->only(['index','store','update','destroy']);

        // Minat Kerja
        Route::get('/preferences', [JobPreferenceController::class, 'index'])->name('preferences.index');
        Route::post('/preferences', [JobPreferenceController::class, 'store'])->name('preferences.store');

        // Pengajuan Kartu Pencaker (AK1)
        Route::get('/card-application',  [CardApplicationController::class, 'index'])->name('card.index');
        Route::post('/card-application', [CardApplicationController::class, 'store'])->name('card.store');
        // Unduh/Cetak Kartu AK1 (hanya milik sendiri dan jika disetujui)
        Route::get('/card-application/{application}/cetak', [CardApplicationController::class, 'cetakPdf'])
            ->name('card.cetak');
    });

/* ===================== PROFILE BREEZE (tetap ada) ===================== */
Route::middleware('auth')->group(function () {
    Route::get('/profile',   [BreezeProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [BreezeProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',[BreezeProfileController::class, 'destroy'])->name('profile.destroy');
});

/* ===================== UTIL (opsional untuk cek file avatar) ===================== */
Route::get('/test-pdf', function () {
    $pdf = Pdf::loadHTML('<h1>Halo Dunia!</h1><p>PDF ini berhasil dibuat 🎉</p>');
    return $pdf->stream('contoh.pdf');
});

require __DIR__ . '/auth.php';
