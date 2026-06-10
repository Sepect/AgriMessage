<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BroadcastController;
use App\Http\Controllers\InboxController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\FarmerGroupController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;

// Redirect root to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Auth Routes (Guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes (Authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Broadcast
    Route::resource('broadcast', BroadcastController::class)->only(['index', 'create', 'store']);

    // Inbox
    Route::get('/inbox', [InboxController::class, 'index'])->name('inbox.index');
    Route::post('/inbox/{chat}/reply', [InboxController::class, 'reply'])->name('inbox.reply');

    // Master Data
    Route::resource('petani', FarmerController::class)->except(['create', 'show', 'edit']);
    Route::resource('kelompok-tani', FarmerGroupController::class)->except(['create', 'show', 'edit']);
    Route::resource('wilayah', RegionController::class)->except(['create', 'show', 'edit']);
    Route::resource('template', TemplateController::class)->except(['create', 'show', 'edit']);

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Admin only routes
    Route::middleware('role:admin')->group(function () {
        Route::resource('pengguna', UserController::class)->except(['create', 'show', 'edit']);

        Route::get('/pengaturan-wa', function () {
            return view('pengaturan-wa.index');
        })->name('pengaturan-wa.index');
    });

    Route::get('/arsip', function () {
        return view('arsip.index');
    })->name('arsip.index');
});
