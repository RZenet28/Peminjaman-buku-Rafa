<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\PeminjamController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\BorrowingController;
use App\Http\Controllers\Admin\ReportingController;
use App\Http\Controllers\Admin\HistoryController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\ProfileSiswaController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

// =====================
// UNIVERSAL REDIRECT
// =====================
Route::get('/redirect-dashboard', function () {
    $user = Auth::user();
    $role = $user->role;

    if ($role == 'admin') {
        return redirect('/admin');
    } elseif ($role == 'petugas') {
        return redirect('/petugas/dashboard');
    } elseif ($role == 'peminjam' || $role == 'siswa' || $role == 'anggota') {
        return redirect('/peminjam/dashboard');
    } else {
        return redirect('/');
    }
})->middleware('auth')->name('dashboard');

// =====================
// PROFILE (Global Auth)
// =====================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// =====================
// ROLE: ADMIN
// =====================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('books', BookController::class);
    
    // Borrowing Management
    Route::get('/borrowing', [BorrowingController::class, 'index'])->name('borrowing.index');
    Route::post('/borrowing', [BorrowingController::class, 'store'])->name('borrowing.store');
    Route::patch('/borrowing/{id}/approve', [BorrowingController::class, 'approve'])->name('borrowing.approve');
    Route::patch('/borrowing/{id}/reject', [BorrowingController::class, 'reject'])->name('borrowing.reject');
    
    // Reporting
    Route::get('/reporting', [ReportingController::class, 'index'])->name('reporting.index');
    Route::get('/reporting/export', [ReportingController::class, 'export'])->name('reporting.export');
    
    // History
    Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
    Route::get('/history/{id}', [HistoryController::class, 'show'])->name('history.show');
    
    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::get('/settings/system-info', [SettingsController::class, 'systemInfo'])->name('settings.system-info');
});

// =====================
// ROLE: PETUGAS
// =====================
Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', [PetugasController::class, 'dashboard'])->name('dashboard');
    
    // Loan approval routes
    Route::get('/persetujuan-peminjaman', [PetugasController::class, 'persetujuanPeminjaman'])->name('persetujuan');
    Route::post('/peminjaman/{id}/approve', [PetugasController::class, 'approvePeminjaman'])->name('approve');
    Route::post('/peminjaman/{id}/reject', [PetugasController::class, 'rejectPeminjaman'])->name('reject');
});

// =====================
// ROLE: PEMINJAM (SISWA)
// =====================
Route::middleware(['auth', 'role:peminjam'])->prefix('peminjam')->group(function () {
    // Halaman Utama Peminjam
    Route::get('/dashboard', [PeminjamController::class, 'dashboard'])->name('peminjam.dashboard');
    
    // Profile dan Riwayat Peminjaman
    Route::get('/profile', [PeminjamController::class, 'profile'])->name('peminjam.profile');
    
    // Fitur Kelola/Daftar Buku
    Route::get('/daftar-buku', [PeminjamController::class, 'daftarBuku'])->name('peminjam.books.index');
    
    // Rute untuk Form Ajukan Peminjaman
    Route::get('/ajukan-peminjaman', [PeminjamController::class, 'ajukanPeminjaman'])->name('peminjaman.create');
    Route::post('/ajukan-peminjaman', [PeminjamController::class, 'store'])->name('peminjaman.store');

    // Book Return Routes
    Route::get('/kembalikan-buku', [PeminjamController::class, 'kembalikanBuku'])->name('peminjam.kembali');
    Route::post('/kembalikan-buku/{id}', [PeminjamController::class, 'prosesKembaliaBuku'])->name('peminjam.proses_kembali');

    // Profile Khusus Siswa
    Route::get('/profile-siswa', [ProfileSiswaController::class, 'index'])->name('peminjam.profile.siswa');
    Route::put('/profile-siswa', [ProfileSiswaController::class, 'update'])->name('peminjam.profile.siswa.update');
});

require __DIR__.'/auth.php';