<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardDataController;
use App\Http\Controllers\MedicalManagementController;
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

    // ── Dashboards apuntando al Controlador ──────────────────────

    Route::get('/dashboard/admin', [DashboardDataController::class, 'informationUsers'])
        ->name('dashboard.admin');

    Route::get('/dashboard/nurse', [DashboardDataController::class, 'informationNurse'])
        ->name('dashboard.nurse');

    Route::get('/dashboard/boss', [DashboardDataController::class, 'informationBoss'])
        ->name('dashboard.boss');

    Route::get('/dashboard/psychologist', [DashboardDataController::class, 'informationPsychologist'])
        ->name('dashboard.psychologist');

    // ── User management ──────────────────────────────────────────

    Route::get('/user_management/Dashboard_um', [UserManagementController::class, 'index'])
        ->name('Dashboard_um');

    Route::post('/users', [UserController::class, 'store'])
        ->name('users.store');

    Route::put('/users/{user}', [UserController::class, 'update'])
        ->name('users.update');

    Route::patch('/users/{user}/status', [UserController::class, 'updateStatus'])
        ->name('users.update-status');

    Route::get('/groups/by-career/{career}', [UserManagementController::class, 'groupsByCareer'])
        ->name('groups.byCareer');

    // ── Medical management ──────────────────────────────────────────

    Route::get('/appointments/Medical', [MedicalManagementController::class, 'index'])
        ->name('Medical');


    Route::get('/students/by-matricula/{matricula}', [MedicalManagementController::class, 'searchStudent'])
        ->name('students.by-matricula');

    Route::post('/appointments/medical', [MedicalManagementController::class, 'store'])
        ->name('appointments.medical.store');

    //Details medical appointments
    Route::get('/appointments/{appointment}', [MedicalManagementController::class, 'detailsAppointment'])
        ->name('appointments.details');



    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});
