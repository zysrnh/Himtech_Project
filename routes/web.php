<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrganizationController as AdminOrganizationController;
use App\Http\Controllers\Anggota\DashboardController as AnggotaDashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Pengurus\DashboardController as PengurusDashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Halaman Depan / Landing Page
Route::get('/', function () {
    return view('welcome');
});

// Portal Login Terpadu (Pengurus & Anggota)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login/pengurus', [LoginController::class, 'loginPengurus'])->name('login.pengurus');
Route::post('/login/anggota', [LoginController::class, 'loginAnggota'])->name('login.anggota');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Backward compatibility redirect untuk tautan login spesifik
Route::get('/pengurus/login', fn () => redirect()->route('login', ['role' => 'pengurus']))->name('pengurus.login');
Route::get('/anggota/login', fn () => redirect()->route('login', ['role' => 'anggota']))->name('anggota.login');
Route::post('/pengurus/logout', [LoginController::class, 'logout'])->name('pengurus.logout');
Route::post('/anggota/logout', [LoginController::class, 'logout'])->name('anggota.logout');

// Panel Kerja Pengurus / Staff (Multi-Tenant Protected)
Route::middleware(['auth', 'pengurus'])->prefix('pengurus')->name('pengurus.')->group(function () {
    Route::get('/dashboard', [PengurusDashboardController::class, 'index'])->name('dashboard');
});

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
