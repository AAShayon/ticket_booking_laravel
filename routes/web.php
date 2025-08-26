<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Laravel Ticket Booking System API',
        'status' => 'active',
        'version' => '1.0',
        'endpoints' => [
            'api_documentation' => url('/api/documentation'),
            'user_endpoints' => url('/api/user'),
            'operator_endpoints' => url('/api/operators'),
            'booking_endpoints' => url('/api/bookings')
        ],
        'timestamp' => now()
    ]);
});
