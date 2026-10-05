<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\GateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PetugasController;

/*
|--------------------------------------------------------------------------
| API Routes - SIP Member (Sistem Informasi Parkir Member Kelompok 2)
|--------------------------------------------------------------------------
*/

// Health check
Route::get('/ping', function () {
    return response()->json([
        'status' => 'online',
        'system' => 'SIP-Member API v2.4 Core Gate',
        'server_time' => now()->toIso8601String(),
    ]);
});

// Authentication & Staff Access
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/verify-pin', [AuthController::class, 'verifyPin']);
    Route::get('/petugas', [AuthController::class, 'getPetugasList']);
});

// Kelola Akun (Admin, Kasir, Petugas/Operator/Satpam)
Route::prefix('petugas')->group(function () {
    Route::get('/', [PetugasController::class, 'index']);
    Route::post('/', [PetugasController::class, 'store']);
    Route::get('/{id}', [PetugasController::class, 'show']);
    Route::put('/{id}', [PetugasController::class, 'update']);
    Route::delete('/{id}', [PetugasController::class, 'destroy']);
    Route::post('/{id}/reset-password', [PetugasController::class, 'resetPassword']);
});

// HIPO 1.0: Kelola Data Member
Route::prefix('members')->group(function () {
    Route::get('/', [MemberController::class, 'index']);
    Route::post('/', [MemberController::class, 'store']);
    Route::post('/qr-login', [MemberController::class, 'qrLogin']);
    Route::get('/{id}', [MemberController::class, 'show']);
    Route::put('/{id}', [MemberController::class, 'update']);
    Route::delete('/{id}', [MemberController::class, 'destroy']);
    Route::patch('/{id}/status', [MemberController::class, 'toggleStatus']);
});

// HIPO 2.0: Kelola Pembayaran Iuran
Route::prefix('pembayaran')->group(function () {
    Route::get('/', [PembayaranController::class, 'index']);
    Route::get('/check-member/{id}', [PembayaranController::class, 'checkMember']);
    Route::post('/', [PembayaranController::class, 'store']);
    Route::get('/receipt/{id}', [PembayaranController::class, 'receipt']);
});

// HIPO 3.0 & 4.0: Gate-In & Gate-Out Operations (Khusus Member)
Route::prefix('gate')->group(function () {
    Route::post('/check-in', [GateController::class, 'checkIn']);
    Route::post('/override-in', [GateController::class, 'overrideIn']);
    Route::post('/scan-out', [GateController::class, 'scanOut']);
    Route::post('/check-out', [GateController::class, 'checkOut']);
    Route::post('/emergency-open', [GateController::class, 'emergencyOpen']);
    Route::post('/verify-kiosk-access', [GateController::class, 'verifyKioskAccess']);
    Route::get('/active-sessions', [GateController::class, 'activeSessions']);
});

// HIPO 5.0: Dashboard Monitoring & Laporan
Route::prefix('dashboard')->group(function () {
    Route::get('/stats', [DashboardController::class, 'stats']);
    Route::get('/revenue-report', [DashboardController::class, 'revenueReport']);
    Route::get('/visit-report', [DashboardController::class, 'visitReport']);
    Route::get('/recent-activity', [DashboardController::class, 'recentActivity']);
});
