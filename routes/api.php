<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Kepsek\DashboardController as KepsekDashboard;
use App\Http\Controllers\Api\Admin\LaporanController as AdminLaporan;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public auth
Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login',    [AuthController::class, 'login']);

// Protected with Sanctum
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('auth/logout', [AuthController::class, 'logout']);

    // Admin / Kepsek protected routes
    Route::middleware(['role:admin,kepsek'])->get('admin/laporan/pendaftar', [AdminLaporan::class,'exportPendaftar']);
    Route::middleware(['role:kepsek'])->get('kepsek/kpi', [KepsekDashboard::class, 'getKpi']);
});