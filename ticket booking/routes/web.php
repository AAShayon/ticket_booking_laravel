<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to Ansteches Ticket Booking Backend API',
        'status' => 'Server Running Successfully',
        'api_base_url' => url('/api'),
        'documentation' => url('/api/documentation'),
        'version' => '1.0.0',
        'endpoints' => [
            'authentication' => '/api/login, /api/register',
            'bookings' => '/api/bookings',
            'routes' => '/api/routes/search',
            'payments' => '/api/payments/*',
            'admin' => '/api/admin/*'
        ]
    ], 200, [], JSON_PRETTY_PRINT);
});

