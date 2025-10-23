<?php

use App\Http\Controllers\api\ArtaIDVerificationController;
use App\Http\Controllers\api\AttendanceApiController;
use App\Http\Controllers\api\AuthApiController;
use App\Http\Controllers\api\AttendeeApiController;
use App\Http\Controllers\api\DashboardDataApiContoller;
use App\Http\Controllers\api\GuestQrCodeController;
use App\Http\Controllers\api\MyQRApiController;
use App\Http\Controllers\api\NotificationApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:100,1')->resource('/arta-id-verify', ArtaIDVerificationController::class)->only(['store']);

// Route::apiResource('/guest-qr', GuestQrCodeController::class)->only(['index', 'store', 'destroy']);

// Route::post('/login', [AuthApiController::class, 'login'])->name('login');
// Route::post('/forgot', [AuthApiController::class, 'forgot'])->name('forgot');
// Route::post('/forgot/recovery', [AuthApiController::class, 'recovery'])->name('forgot.recovery');

// Route::middleware(['auth:sanctum'])->group(function () {
//     Route::get('/user', function (Request $request) {
//         return $request->user();
//     });

//     Route::post('/logout', [AuthApiController::class, 'logout'])->name('logout');
//     Route::get('/me', [AuthApiController::class, 'me'])->name('me');
//     Route::post('/change-password', [AuthApiController::class, 'changePassword'])->name('change-password');

//     Route::apiResource('/attendance', AttendanceApiController::class)->only(['index', 'show']);
//     Route::apiResource('/attendee', AttendeeApiController::class)->only(['index', 'show', 'store', 'destroy']);
//     Route::apiResource('/dashboard-data', DashboardDataApiContoller::class)->only(['index']);
//     Route::apiResource('/notification', NotificationApiController::class)->only(['index']);
//     Route::apiResource('/my-qr', MyQRApiController::class)->only(['index']);
// });
