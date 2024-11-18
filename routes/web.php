<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PolicyManagerController;
use App\Http\Controllers\PolicyTypeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskManagerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return view('welcome');
    return view('auth.login');
});

Route::middleware(['auth', 'verified', 'roleManager:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Policy Type
    Route::get('/policy/type', [PolicyTypeController::class, 'index'])->name('admin.policy.type');
    Route::get('/create/policy/type', [PolicyTypeController::class, 'create'])->name('admin.policy.type.create');
    Route::post('/policy/type/store', [PolicyTypeController::class, 'store'])->name('admin.policy.types.store');
    Route::get('/policy/type/{id}/edit', [PolicyTypeController::class, 'edit'])->name('admin.policy.type.edit');
    Route::put('/policy/type/{id}', [PolicyTypeController::class, 'update'])->name('admin.policy.type.update');
    Route::delete('/policy/type/{id}', [PolicyTypeController::class, 'destroy'])->name('admin.policy.type.destroy');
});

Route::middleware(['auth', 'verified', 'roleManager:user'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Policy Manager
    Route::get('/policy', [PolicyManagerController::class, 'index'])->name('all.policy.user');
    Route::get('/all/policies', [PolicyManagerController::class, 'show'])->name('show.policy.user');
    // Route::get('/policy/create', [PolicyManagerController::class, 'create'])->name('create.policy.user');
    Route::get('/policy/buy/{id}', [PolicyManagerController::class, 'create'])->name('policy.buy');

    Route::post('/policies', [PolicyManagerController::class, 'store'])->name('policy.store');
    Route::get('/policies/{id}', [PolicyManagerController::class, 'show'])->name('policy.show');
    Route::get('/policies/{id}/edit', [PolicyManagerController::class, 'edit'])->name('policy.edit');
    Route::put('/policies/{id}', [PolicyManagerController::class, 'update'])->name('policy.update');
    Route::delete('/policies/{id}', [PolicyManagerController::class, 'destroy'])->name('policy.destroy');
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
