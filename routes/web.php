<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrganizationController as AdminOrganizationController;
use App\Http\Controllers\Anggota\DashboardController as AnggotaDashboardController;
use App\Http\Controllers\Auth\AnggotaAuthController;
use App\Http\Controllers\Auth\PengurusAuthController;
use App\Http\Controllers\Pengurus\DashboardController as PengurusDashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Halaman Depan / Landing Page
Route::get('/', function () {
    return view('welcome');
});

// Redirect Login Publik Berdasarkan Parameter
Route::get('/login', function (Request $request) {
    if ($request->query('role') === 'anggota') {
        return redirect()->route('anggota.login');
    }

    return redirect()->route('pengurus.login');
})->name('login');

// Autentikasi Pengurus / Staff Organisasi
Route::get('/pengurus/login', [PengurusAuthController::class, 'showLoginForm'])->name('pengurus.login');
Route::post('/pengurus/login', [PengurusAuthController::class, 'login'])->name('pengurus.login.submit');
Route::post('/pengurus/logout', [PengurusAuthController::class, 'logout'])->name('pengurus.logout');

// Panel Kerja Pengurus / Staff (Multi-Tenant Protected)
Route::middleware(['auth', 'pengurus'])->prefix('pengurus')->name('pengurus.')->group(function () {
    Route::get('/dashboard', [PengurusDashboardController::class, 'index'])->name('dashboard');
});

// Autentikasi Anggota Organisasi
Route::get('/anggota/login', [AnggotaAuthController::class, 'showLoginForm'])->name('anggota.login');
Route::post('/anggota/login', [AnggotaAuthController::class, 'login'])->name('anggota.login.submit');
Route::post('/anggota/logout', [AnggotaAuthController::class, 'logout'])->name('anggota.logout');

// Portal Transparansi Anggota
Route::middleware(['auth', 'anggota'])->prefix('anggota')->name('anggota.')->group(function () {
    Route::get('/dashboard', [AnggotaDashboardController::class, 'index'])->name('dashboard');
});

// Autentikasi Khusus Super Admin
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Panel Pusat Super Admin
Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
Route::get('/admin/organizations', [AdminOrganizationController::class, 'index'])->name('admin.organizations.index');
Route::post('/admin/organizations', [AdminOrganizationController::class, 'store'])->name('admin.organizations.store');
