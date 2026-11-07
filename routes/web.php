<?php

use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrganizationController as AdminOrganizationController;
use App\Http\Controllers\Anggota\DashboardController as AnggotaDashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\OrganizationRequestController;
use App\Http\Controllers\Pengurus\DashboardController as PengurusDashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Halaman Depan / Landing Page
Route::get('/', function () {
    return view('welcome');
});

// Formulir Publik Pengajuan Pendaftaran Organisasi Baru
Route::get('/ajukan-organisasi', [OrganizationRequestController::class, 'showForm'])->name('ajukan-organisasi');
Route::post('/ajukan-organisasi', [OrganizationRequestController::class, 'store'])->name('ajukan-organisasi.submit');
Route::get('/ajukan-organisasi/sukses', [OrganizationRequestController::class, 'success'])->name('ajukan-organisasi.success');

// Portal Login Tunggal Universal (Pengurus & Anggota)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Backward compatibility redirect untuk tautan login spesifik
Route::get('/pengurus/login', fn () => redirect()->route('login'))->name('pengurus.login');
Route::get('/anggota/login', fn () => redirect()->route('login'))->name('anggota.login');
Route::post('/pengurus/logout', [LoginController::class, 'logout'])->name('pengurus.logout');
Route::post('/anggota/logout', [LoginController::class, 'logout'])->name('anggota.logout');

// Panel Kerja Pengurus / Staff (Multi-Tenant Protected)
Route::middleware(['auth', 'pengurus'])->prefix('pengurus')->name('pengurus.')->group(function () {
    Route::get('/dashboard', [PengurusDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'showPengurusProfile'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'updatePengurusProfile'])->name('profile.update');
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
Route::get('/admin/organizations/create', [AdminOrganizationController::class, 'create'])->name('admin.organizations.create');
Route::post('/admin/organizations', [AdminOrganizationController::class, 'store'])->name('admin.organizations.store');
Route::patch('/admin/organizations/{organization}/toggle-status', [AdminOrganizationController::class, 'toggleStatus'])->name('admin.organizations.toggle-status');
Route::delete('/admin/organizations/{organization}', [AdminOrganizationController::class, 'destroy'])->name('admin.organizations.destroy');

// Persetujuan (Approval) Pengajuan Organisasi oleh Super Admin
Route::post('/admin/organizations/requests/{organizationRequest}/approve', [AdminOrganizationController::class, 'approveRequest'])->name('admin.organizations.requests.approve');
Route::post('/admin/organizations/requests/{organizationRequest}/reject', [AdminOrganizationController::class, 'rejectRequest'])->name('admin.organizations.requests.reject');

// Kelola Administrator Kasly (Multi-Admin Super Admin)
Route::get('/admin/admins', [AdminManagementController::class, 'index'])->name('admin.admins.index');
Route::post('/admin/admins', [AdminManagementController::class, 'store'])->name('admin.admins.store');
Route::delete('/admin/admins/{user}', [AdminManagementController::class, 'destroy'])->name('admin.admins.destroy');

// Profil Super Admin & Pengaturan Kontak Notifikasi
Route::get('/admin/profile', [ProfileController::class, 'showAdminProfile'])->name('admin.profile');
Route::put('/admin/profile', [ProfileController::class, 'updateAdminProfile'])->name('admin.profile.update');
