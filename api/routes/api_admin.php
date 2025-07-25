<?php

use App\Http\Controllers\Api\App\AuthController;
use App\Http\Controllers\Api\App\LoyalController;
use App\Http\Controllers\Api\App\MenuController;
use App\Http\Controllers\Api\App\PelangganController;
use App\Http\Controllers\Api\App\PromoController;
use App\Http\Controllers\Api\App\TransaksiController;
use App\Http\Controllers\Api\App\UlasanController;
use App\Http\Controllers\Api\App\UlasanPertanyaanController;
use App\Http\Controllers\Api\App\UserController;
use App\Http\Middleware\AuthCus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/', function (Request $request) {
    return response(['message' => 'app api']);
});

Route::prefix('app')->group(function () {
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/pelanggan-reg', [PelangganController::class,'store']);

    Route::middleware([AuthCus::class])->group(function () {
        Route::get('/dashboard', [UserController::class, 'dashboard']);
        Route::get('/user-info', [UserController::class, 'info']);
        Route::get('/notif', [UserController::class, 'info']);
        Route::post('/change-password', [UserController::class, 'changePassword']);

        Route::post('/transaksi-rating/{id}', [TransaksiController::class, 'updateRating']);
        Route::apiResource('/users', UserController::class);
        Route::get('/menu-cats', [MenuController::class, 'cats']);
        Route::apiResource('/menu', MenuController::class);
        Route::apiResource('/loyal', LoyalController::class);
        Route::apiResource('/ulasan-pertanyaan', UlasanPertanyaanController::class);
        Route::apiResource('/ulasan', UlasanController::class);
        Route::apiResource('/pelanggan', PelangganController::class);
        Route::apiResource('/transaksi', TransaksiController::class);
        Route::apiResource('/promo', PromoController::class);
    });
});
