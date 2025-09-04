<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Operator;
use App\Models\Vehicle;
use App\Models\Route;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ExtensiveRouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $origins = ['Dhaka', 'Chittagong', 'Sylhet', 'Rajshahi', 'Khulna'];
        $destinations = ['Dhaka', 'Chittagong', 'Sylhet', 'Rajshahi', 'Khulna'];
        $operatorNames = [
            'Hanif Enterprise', 'Shyamoli Paribahan', 'Green Line Paribahan',
            'Desh Travels', 'Ena Paribahan', 'Nabil Paribahan',
            'S. Alam Paribahan', 'London Express', 'Saintmartin Paribahan',
            'Relax Transport'
        ];
        $vehicleTypes = ['AC', 'non-AC', 'sleeper', 'hyundai', 'scania'];
        $vehicleCapacities = [28, 30, 38, 40]; // Different capacities

        // Ensure existing operators are not duplicated if this seeder is run multiple times
        // For simplicity, we'll create new ones for this extensive test
        foreach ($operatorNames as $opName) {
            $user = User::factory()->create([
                'name' => $opName . ' Owner',
                'email' => strtolower(str_replace(' ', '', $opName)) . '@example.com',
                'password' => Hash::make('password'),
                'role' => 'operator',
            ]);

            $operator = Operator::create([
                'user_id' => $user->id,
                'name' => $opName . ' ' . rand(100, 999),
                'contact_email' => strtolower(str_replace(' ', '', $opName)) . '@contact.com',
                'contact_phone' => '+8801' . rand(100000000, 999999999),
                'admin_commission_percentage' => rand(5, 15),
                'nid' => 'NID' . rand(1000000000, 9999999999),
                'address' => $origins[array_rand($origins)] . ', Bangladesh',
                'transport_business_license' => 'TBL-' . strtoupper(substr($opName, 0, 3)) . rand(1000, 9999),
                // 'logo' => 'path/to/logo.png', // Optional: add real paths if needed
            ]);

            // Create multiple vehicles for each operator
            $numVehicles = rand(2, 5); // Each operator has 2 to 5 vehicles
            for ($v = 0; $v < $numVehicles; $v++) {
                $vehicleType = $vehicleTypes[array_rand($vehicleTypes)];
                $capacity = $vehicleCapacities[array_rand($vehicleCapacities)];
                $vehicle = Vehicle::create([
                    'operator_id' => $operator->id,
                    'model_number' => 'Model-' . strtoupper(substr($opName, 0, 2)) . rand(100, 999),
                    'type' => $vehicleType,
                    'capacity' => $capacity,
                    'vehicle_number' => 'DHAKA-METRO-B-' . rand(10, 99) . '-' . rand(1000, 9999),
                    // 'image' => 'path/to/vehicle.png', // Optional
                ]);

                // Create routes for this vehicle
                $numRoutesPerVehicle = rand(1, 3); // Each vehicle runs 1 to 3 routes
                for ($r = 0; $r < $numRoutesPerVehicle; $r++) {
                    $origin = $origins[array_rand($origins)];
                    $destination = $destinations[array_rand($destinations)];
                    // Ensure origin and destination are different
                    while ($origin === $destination) {
                        $destination = $destinations[array_rand($destinations)];
                    }

                    $baseFarePerSeat = rand(20, 100); // Base fare per seat
                    $estimatedTravelTime = rand(3, 10) . ' hours';

                    // Generate routes every 15 minutes from 6:30 AM to 12:00 PM
                    $startTime = Carbon::createFromTime(6, 30, 0); // 6:30 AM
                    $endTime = Carbon::createFromTime(12, 0, 0);   // 12:00 PM

                    for ($time = clone $startTime; $time->lte($endTime); $time->addMinutes(15)) {
                        Route::create([
                            'operator_id' => $operator->id,
                            'vehicle_id' => $vehicle->id,
                            'origin' => $origin,
                            'destination' => $destination,
                            'fare_per_seat' => $baseFarePerSeat,
                            'fare' => $baseFarePerSeat * $vehicle->capacity, // Total fare for the whole bus
                            'estimated_travel_time' => $estimatedTravelTime,
                            'vehicle_number' => $vehicle->vehicle_number, // Use vehicle's number
                            'time_of_day' => $this->getTimeOfDay($time),
                            'departure_time' => $time->format('H:i:s'),
                        ]);
                    }
                }
            }
        }
    }

    private function getTimeOfDay(Carbon $time): string
    {
        $hour = $time->hour;
        if ($hour >= 5 && $hour < 12) {
            return 'morning';
        } elseif ($hour >= 12 && $hour < 17) {
            return 'afternoon';
        } elseif ($hour >= 17 && $hour < 21) {
            return 'evening';
        } else {
            return 'night';
        }
    }
}