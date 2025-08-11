<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\OperatorController;
use App\Http\Controllers\Api\RouteController;
use App\Http\Controllers\Api\OperatorRequestController;
use App\Http\Controllers\Api\VehicleController;
use App\Http\Controllers\Api\ForgotPasswordController;

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

// Forgot Password Routes
Route::post('/forgot-password/request-otp', [ForgotPasswordController::class, 'requestOtp']);
Route::post('/forgot-password/verify-otp', [ForgotPasswordController::class, 'verifyOtp']);
Route::post('/forgot-password/reset', [ForgotPasswordController::class, 'reset']);

Route::post('/payments/success', [PaymentController::class, 'paymentSuccess']);
Route::post('/payments/fail', [PaymentController::class, 'paymentFail']);
Route::post('/payments/cancel', [PaymentController::class, 'paymentCancel']);
Route::post('/payments/ipn', [PaymentController::class, 'ipn']);

Route::get('/operators/public', [OperatorController::class, 'publicIndex']);
Route::get('/routes/public', [RouteController::class, 'publicIndex']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('/bookings', BookingController::class);

    Route::get('/routes/search', [RouteController::class, 'search']);

    Route::post('/payments/initiate/{booking}', [PaymentController::class, 'initiatePayment']);

    Route::post('/profile', [App\Http\Controllers\Api\ProfileController::class, 'update']);

    // Operator Requests (User can submit and view their own)
    Route::post('/operator-requests', [OperatorRequestController::class, 'store']);
    Route::get('/operator-requests/my', [OperatorRequestController::class, 'userRequests']);

    // Operator Routes
    Route::middleware(['role:operator'])->group(function () {
        Route::apiResource('/vehicles', VehicleController::class);
    });

    // Admin Routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/vehicles', [VehicleController::class, 'adminIndex']);
        Route::get('/admin/users', [AdminController::class, 'getUsers']);
        Route::get('/admin/users/{user}', [AdminController::class, 'showUser']);
        Route::post('/admin/users', [AdminController::class, 'createUser']);
        Route::put('/admin/users/{user}', [AdminController::class, 'updateUser']);
        Route::delete('/admin/users/{user}', [AdminController::class, 'deleteUser']);

        Route::get('/admin/bookings', [AdminController::class, 'getAllBookings']);
        Route::put('/admin/bookings/{booking}/status', [AdminController::class, 'updateBookingStatus']);
        Route::delete('/admin/bookings/{booking}', [AdminController::class, 'deleteBooking']);

        // Operator Management
        Route::apiResource('/operators', OperatorController::class);

        // Route Management
        Route::apiResource('/routes', RouteController::class);

        // Operator Request Management
        Route::get('/admin/operator-requests', [OperatorRequestController::class, 'index']);
        Route::get('/admin/operator-requests/{operatorRequest}', [OperatorRequestController::class, 'show']);
        Route::post('/admin/operator-requests/{operatorRequest}/approve', [OperatorRequestController::class, 'approve']);
        Route::post('/admin/operator-requests/{operatorRequest}/reject', [OperatorRequestController::class, 'reject']);
        Route::get('/admin/daily-summary', [AdminController::class, 'dailySummary']);
    });
});

Route::get('/routes', [RouteController::class, 'publicIndex']);
