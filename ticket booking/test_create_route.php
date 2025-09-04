<?php
require_once 'vendor/autoload.php';
require_once 'bootstrap/app.php';

use Illuminate\Http\Request;

try {
    // Authenticate as admin user (you'll need to adjust this based on your auth setup)
    $user = \App\Models\User::find(1); // Assuming user ID 1 is admin
    if ($user) {
        Auth::login($user);
        
        $request = Request::create('/api/routes', 'POST', [
            'operator_id' => 1,
            'vehicle_id' => 8,
            'origin' => 'Chittagong',
            'destination' => 'Sylhet',
            'fare_per_seat' => 25,
            'departure_time' => '07:30:00',
            'vehicle_number' => 'Hanif Enterprise Volvo',
            'time_of_day' => 'morning'
        ]);
        
        $request->headers->set('Accept', 'application/json');
        $request->headers->set('Authorization', 'Bearer 29|Fr3SWCOuva9QFbxYwtUbcQgu65kUb7jBItwl1RAP71f0aef0');
        
        $response = app()->handle($request);
        
        echo "Status Code: " . $response->getStatusCode() . "\n";
        echo "Response: " . $response->getContent() . "\n";
    } else {
        echo "Admin user not found\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}