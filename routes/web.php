<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminPolicyManagerController;
use App\Http\Controllers\AgentDashboardController;
use App\Http\Controllers\AgentPolicyManagerController;
use App\Http\Controllers\AgentPolicyTypeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentsController;
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

    // All Bought Policies
    Route::get('/admin/all/policies', [AdminPolicyManagerController::class, 'index'])->name('admin.all.policies');

    // All Payments
    Route::get('/admin/all/payments', [PaymentsController::class, 'index'])->name('admin.all.payments');

    // Users
    Route::get('/admin/users', [ProfileController::class, 'index'])->name('admin.users.all');
    Route::get('/admin/users/create', [ProfileController::class, 'create'])->name('admin.users.create');
    Route::delete('/admin/users/{id}', [ProfileController::class, 'destroy'])->name('admin.users.destroy');
    Route::post('/admin/users', [ProfileController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/edit', [ProfileController::class, 'admin_edit'])->name('admin.users.edit');
    Route::patch('/admin/profile', [ProfileController::class, 'admin_update'])->name('admin.profile.update');
});

Route::middleware(['auth', 'verified', 'roleManager:user'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Policy Manager
    Route::get('/policy', [PolicyManagerController::class, 'index'])->name('all.policy.user');
    Route::get('/all/policies', [PolicyManagerController::class, 'show'])->name('show.policy.user');
    Route::get('/policy/buy/{id}', [PolicyManagerController::class, 'create'])->name('policy.buy');
    Route::post('/policies', [PolicyManagerController::class, 'store'])->name('policy.store');
    Route::get('/policies/{id}', [PolicyManagerController::class, 'show'])->name('policy.show');
    Route::get('/policies/{id}/edit', [PolicyManagerController::class, 'edit'])->name('policy.edit');
    Route::put('/policies/{id}', [PolicyManagerController::class, 'update'])->name('policy.update');
    Route::delete('/policies/{id}', [PolicyManagerController::class, 'destroy'])->name('policy.destroy');

    // Payments
    Route::get('/all/payments', [PaymentsController::class, 'index_users'])->name('users.all.payments');

    Route::get('/profile/edit', [ProfileController::class, 'user_edit'])->name('user.profile.edit');
    Route::patch('/user/profile', [ProfileController::class, 'update'])->name('user.profile.update');
});

Route::middleware(['auth', 'verified', 'roleManager:agent'])->group(function () {
    Route::get('/agent/dashboard', [AgentDashboardController::class, 'index'])->name('agent.dashboard');

    // Policy Type
    Route::get('/agent/policy/type', [AgentPolicyTypeController::class, 'index'])->name('agent.policy.type');
    Route::get('/agent/create/policy/type', [AgentPolicyTypeController::class, 'create'])->name('agent.policy.type.create');
    Route::post('/agent/policy/type/store', [AgentPolicyTypeController::class, 'store'])->name('agent.policy.types.store');
    Route::get('/agent/policy/type/{id}/edit', [AgentPolicyTypeController::class, 'edit'])->name('agent.policy.type.edit');
    Route::put('/agent/policy/type/{id}', [AgentPolicyTypeController::class, 'update'])->name('agent.policy.type.update');
    Route::delete('/agent/policy/type/{id}', [AgentPolicyTypeController::class, 'destroy'])->name('agent.policy.type.destroy');

    // All Bought Policies
    Route::get('/agent/all/policies', [AgentPolicyManagerController::class, 'index'])->name('agent.all.policies');

    // Payments
    Route::get('/agent/all/payments', [PaymentsController::class, 'index_agent'])->name('agent.all.payments');

    // Users
    Route::get('/agent/profile/edit', [ProfileController::class, 'user_edit'])->name('agent.profile.edit');
    Route::patch('/agent/profile', [ProfileController::class, 'update'])->name('agent.profile.update');
});

require __DIR__ . '/auth.php';
