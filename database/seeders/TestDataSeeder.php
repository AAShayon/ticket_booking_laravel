<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Operator;
use App\Models\Vehicle;
use App\Models\Route;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Operator Owners (Users with 'operator' role)
        $operatorOwner1 = User::updateOrCreate(['email' => 'operator1@example.com'], [
            'name' => 'Operator Owner 1',
            'password' => Hash::make('password'),
            'role' => 'operator',
        ]);

        $operatorOwner2 = User::updateOrCreate(['email' => 'operator2@example.com'], [
            'name' => 'Operator Owner 2',
            'password' => Hash::make('password'),
            'role' => 'operator',
        ]);

        // Create Operators
        $operator1 = Operator::updateOrCreate(['name' => 'Green Line Paribahan'], [
            'user_id' => $operatorOwner1->id,
            'contact_email' => 'contact@greenline.com',
            'contact_phone' => '+8801711111111',
            'admin_commission_percentage' => 10.0,
            'nid' => '1234567890',
            'address' => 'Dhaka, Bangladesh',
            'transport_business_license' => 'GLP-TBL-001',
            'logo' => 'greenline_logo.png',
        ]);

        $operator2 = Operator::updateOrCreate(['name' => 'Shyamoli Paribahan'], [
            'user_id' => $operatorOwner2->id,
            'contact_email' => 'contact@shyamoli.com',
            'contact_phone' => '+8801722222222',
            'admin_commission_percentage' => 12.5,
            'nid' => '0987654321',
            'address' => 'Chittagong, Bangladesh',
            'transport_business_license' => 'SMP-TBL-002',
            'logo' => 'shyamoli_logo.png',
        ]);

        // Create Vehicles for Operator 1 (Green Line)
        $vehicle1_1 = Vehicle::updateOrCreate(['operator_id' => $operator1->id, 'model_number' => 'Volvo B11R'], [
            'type' => 'AC',
            'capacity' => 40,
            'image' => 'volvo_b11r.png',
        ]);

        $vehicle1_2 = Vehicle::updateOrCreate(['operator_id' => $operator1->id, 'model_number' => 'Scania K360'], [
            'type' => 'non-AC',
            'capacity' => 45,
            'image' => 'scania_k360.png',
        ]);

        // Create Vehicles for Operator 2 (Shyamoli)
        $vehicle2_1 = Vehicle::updateOrCreate(['operator_id' => $operator2->id, 'model_number' => 'Hino RM2'], [
            'type' => 'sleeper',
            'capacity' => 30,
            'image' => 'hino_rm2.png',
        ]);

        $vehicle2_2 = Vehicle::updateOrCreate(['operator_id' => $operator2->id, 'model_number' => 'Hyundai Universe'], [
            'type' => 'hyundai',
            'capacity' => 35,
            'image' => 'hyundai_universe.png',
        ]);

        // Create Routes for Operator 1
        Route::updateOrCreate(
            [
                'operator_id' => $operator1->id,
                'vehicle_id' => $vehicle1_1->id,
                'origin' => 'Dhaka',
                'destination' => 'Chittagong',
                'departure_time' => '07:00:00',
            ],
            [
                'fare' => 1200.00,
                'fare_per_seat' => 1200.00 / $vehicle1_1->capacity,
                'estimated_travel_time' => '6 hours',
                'vehicle_number' => 'DHAKA-METRO-B-11-1234',
                'time_of_day' => 'morning',
            ]
        );

        Route::updateOrCreate(
            [
                'operator_id' => $operator1->id,
                'vehicle_id' => $vehicle1_2->id,
                'origin' => 'Dhaka',
                'destination' => 'Sylhet',
                'departure_time' => '19:00:00',
            ],
            [
                'fare' => 800.00,
                'fare_per_seat' => 800.00 / $vehicle1_2->capacity,
                'estimated_travel_time' => '5 hours',
                'vehicle_number' => 'DHAKA-METRO-B-11-5678',
                'time_of_day' => 'evening',
            ]
        );

        // Create Routes for Operator 2
        Route::updateOrCreate(
            [
                'operator_id' => $operator2->id,
                'vehicle_id' => $vehicle2_1->id,
                'origin' => 'Chittagong',
                'destination' => 'Cox\'s Bazar',
                'departure_time' => '14:30:00',
            ],
            [
                'fare' => 600.00,
                'fare_per_seat' => 600.00 / $vehicle2_1->capacity,
                'estimated_travel_time' => '3 hours',
                'vehicle_number' => 'CHITTAGONG-METRO-B-12-9012',
                'time_of_day' => 'afternoon',
            ]
        );

        Route::updateOrCreate(
            [
                'operator_id' => $operator2->id,
                'vehicle_id' => $vehicle2_2->id,
                'origin' => 'Sylhet',
                'destination' => 'Dhaka',
                'departure_time' => '22:00:00',
            ],
            [
                'fare' => 900.00,
                'fare_per_seat' => 900.00 / $vehicle2_2->capacity,
                'estimated_travel_time' => '5.5 hours',
                'vehicle_number' => 'SYLHET-METRO-B-13-3456',
                'time_of_day' => 'night',
                'departure_time' => '22:00:00',
            ]
        );

        // Create a regular user
        User::updateOrCreate(['email' => 'user@example.com'], [
            'name' => 'Regular User',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
    }
}
