<?php
// Simple script to test the route creation API endpoint

$url = 'https://ansteches.shop/api/routes';

$data = [
    'operator_id' => 1,
    'vehicle_id' => 8,
    'origin' => 'Chittagong',
    'destination' => 'Sylhet',
    'fare_per_seat' => 25,
    'departure_time' => '07:30:00',
    'vehicle_number' => 'Hanif Enterprise Volvo',
    'time_of_day' => 'morning'
];

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
    'Authorization: Bearer 29|Fr3SWCOuva9QFbxYwtUbcQgu65kUb7jBItwl1RAP71f0aef0'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo "HTTP Code: " . $httpCode . "\n";
echo "Response: " . $response . "\n";

curl_close($ch);