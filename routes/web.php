<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrganizationController as AdminOrganizationController;
use Illuminate\Support\Facades\Route;

// Halaman Depan / Landing Page
Route::get('/', function () {
    return view('welcome');
});

// Autentikasi Khusus Super Admin
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Dashboard Panel Pusat Super Admin
Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
Route::post('/admin/organizations', [AdminOrganizationController::class, 'store'])->name('admin.organizations.store');
