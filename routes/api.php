<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\AdminController;

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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/payments/success', [PaymentController::class, 'paymentSuccess']);
Route::post('/payments/fail', [PaymentController::class, 'paymentFail']);
Route::post('/payments/cancel', [PaymentController::class, 'paymentCancel']);
Route::post('/payments/ipn', [PaymentController::class, 'ipn']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('/bookings', BookingController::class);

    Route::post('/payments/initiate/{booking}', [PaymentController::class, 'initiatePayment']);

    Route::post('/profile', [App\Http\Controllers\Api\ProfileController::class, 'update']);

    // Admin Routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/users', [AdminController::class, 'getUsers']);
        Route::get('/admin/users/{user}', [AdminController::class, 'showUser']);
        Route::post('/admin/users', [AdminController::class, 'createUser']);
        Route::put('/admin/users/{user}', [AdminController::class, 'updateUser']);
        Route::delete('/admin/users/{user}', [AdminController::class, 'deleteUser']);

        Route::get('/admin/bookings', [AdminController::class, 'getAllBookings']);
        Route::put('/admin/bookings/{booking}/status', [AdminController::class, 'updateBookingStatus']);
        Route::delete('/admin/bookings/{booking}', [AdminController::class, 'deleteBooking']);
    });
});
