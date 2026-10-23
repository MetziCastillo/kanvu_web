<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Controllers\Auth\AuthController;

// Mostrar el login
Route::get('/', function () {
    return Inertia::render('auth/Login');
})->name('login');

// Procesar el inicio de sesión
Route::post('/login', [AuthController::class, 'login'])
    ->name('login.authenticate');

// Rutas que requieren iniciar sesión
Route::middleware('auth')->group(function () {

    // Redirigir según el rol
    Route::get('/dashboard', function () {
        $user = Auth::user();

        return match ((int) $user->role?->id) {
            1 => redirect()->route('dashboard.admin'),
            5 => redirect()->route('dashboard.psychologist'),
            default => abort(403, 'No tienes un rol válido asignado.'),
        };
    })->name('dashboard');

    // Dashboard del administrador
    Route::get('/dashboard/admin', function () {
        abort_unless(
            (int) Auth::user()->role?->id === 1,
            403
        );

        return Inertia::render('dashboard/admin_role/Dashboard');
    })->name('dashboard.admin');

    // Dashboard del psicólogo
    Route::get('/dashboard/psychologist', function () {
        abort_unless(
            (int) Auth::user()->role?->id === 5,
            403
        );

        return Inertia::render(
            'dashboard/psychologist_role/Dashboard'
        );
    })->name('dashboard.psychologist');

    // Cerrar sesión
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});
