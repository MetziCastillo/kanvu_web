<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Http\Request;

Route::get('/', function () {
    return Inertia::render('auth/Login');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.authenticate');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        $user = Auth::user();

        return match ((int) $user->role?->id) {
            1 => redirect()->route('dashboard.admin'),
            2 => redirect()->route('dashboard.boss'),
            3 => redirect()->route('dashboard.boss'),
            4 => redirect()->route('dashboard.nurse'),
            5 => redirect()->route('dashboard.psychologist'),
            default => abort(403, 'No tienes un rol válido asignado.'),
        };
    })->name('dashboard');

    Route::get('/dashboard/admin', function () {
        abort_unless((int) Auth::user()->role?->id === 1, 403);
        return Inertia::render('dashboard/admin_role/Dashboard');
    })->name('dashboard.admin');

    Route::get('/dashboard/nurse', function () {
        abort_unless((int) Auth::user()->role?->id === 4, 403);
        return Inertia::render('dashboard/nurse_role/Dashboard');
    })->name('dashboard.nurse');

    Route::get('/dashboard/boss', function () {
        abort_unless(
            Auth::check() && in_array((int) Auth::user()->role?->id, [2, 3], true),
            403
        );
        return Inertia::render('dashboard/boss_departments/Dashboard');
    })->name('dashboard.boss');

    Route::get('/dashboard/psychologist', function () {
        abort_unless((int) Auth::user()->role?->id === 5, 403);
        return Inertia::render('dashboard/psychologist_role/Dashboard');
    })->name('dashboard.psychologist');

    // ── User management ──────────────────────────────────────────

    // Página Inertia
    Route::get('/user_management/Dashboard_um', [UserManagementController::class, 'index'])
        ->name('Dashboard_um');

    // Crear usuario
    Route::post('/users', [UserController::class, 'store'])
        ->name('users.store');

    Route::put('/users/{user}', [UserController::class, 'update'])
        ->name('users.update');

    // Grupos por carrera
    Route::get('/groups/by-career/{career}', [UserManagementController::class, 'groupsByCareer'])
        ->name('groups.byCareer');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});
