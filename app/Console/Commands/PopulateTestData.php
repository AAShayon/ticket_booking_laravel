<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Operator;
use App\Models\Vehicle;
use App\Models\Route;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Faker\Factory as Faker;

class PopulateTestData extends Command
{
    protected $faker;

    public function __construct()
    {
        parent::__construct();
        $this->faker = Faker::create();
    }

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populates the database with test data for admins, users, operators, vehicles, routes, bookings, and payments.';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:populate-test-data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Populating database with test data...');

        // Define some common cities in Bangladesh
        $cities = [
            'Dhaka', 'Chittagong', 'Sylhet', 'Rajshahi', 'Khulna', 'Barishal', 'Rangpur', 'Mymensingh', 'Comilla', 'Jessore'
        ];

        // 1. Create 3 Admin Accounts
        $this->info('Creating 3 admin accounts...');
        for ($i = 1; $i <= 3; $i++) {
            User::create([
                'name' => 'Admin ' . $i,
                'email' => 'admin' . $i . '@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => Carbon::now(),
            ]);
        }
        $this->info('Admin accounts created.');

        // 2. Create 20 User Accounts
        $this->info('Creating 20 user accounts...');
        User::factory()->count(20)->create([
            'role' => 'user',
            'password' => Hash::make('password'),
            'email_verified_at' => Carbon::now(),
        ]);
        $this->info('User accounts created.');

        // 3. Create 10 Operator Buses (Operators, Vehicles, Routes)
        $this->info('Creating 10 operator buses with vehicles and routes...');
        for ($i = 1; $i <= 10; $i++) {
            // Create an operator user
            $operatorUser = User::factory()->create([
                'name' => 'Operator User ' . $i,
                'email' => 'operator' . $i . '@example.com',
                'password' => Hash::make('password'),
                'role' => 'operator',
                'email_verified_at' => Carbon::now(),
            ]);

            // Create an operator
            $operator = Operator::create([
                'user_id' => $operatorUser->id,
                'name' => 'Operator ' . $i . ' Bus Service',
                'contact_email' => 'contact_op' . $i . '@example.com',
                'contact_phone' => '+8801' . rand(3, 9) . Str::random(8),
                'admin_commission_percentage' => rand(5, 15),
                'nid' => Str::random(10),
                'address' => $this->faker->address,
                'transport_business_license' => 'TBL-' . Str::upper(Str::random(5)),
                'logo' => 'operator_logo_' . $i . '.png',
            ]);

            // Create a vehicle for the operator
            $vehicle = Vehicle::create([
                'operator_id' => $operator->id,
                'model_number' => 'Model-' . Str::upper(Str::random(4)),
                'type' => $this->faker->randomElement(['AC', 'Non-AC', 'Sleeper']),
                'capacity' => rand(30, 50),
                'image' => 'vehicle_image_' . $i . '.png',
            ]);

            // Create a route for the operator and vehicle
            $origin = $this->faker->randomElement($cities);
            $destination = $this->faker->randomElement(array_diff($cities, [$origin])); // Ensure different origin/destination

            Route::create([
                'operator_id' => $operator->id,
                'vehicle_id' => $vehicle->id,
                'origin' => $origin,
                'destination' => $destination,
                'fare' => rand(500, 2000),
                'fare_per_seat' => rand(500, 2000) / $vehicle->capacity,
                'estimated_travel_time' => rand(3, 10) . ' hours',
                'vehicle_number' => 'BD-' . rand(1000, 9999),
                'time_of_day' => $this->faker->randomElement(['morning', 'afternoon', 'evening', 'night']),
                'departure_time' => Carbon::createFromTime(rand(0, 23), rand(0, 59), rand(0, 59))->format('H:i:s'),
            ]);
        }
        $this->info('Operator buses, vehicles, and routes created.');

        // 4. Create some Bookings and Payments
        $this->info('Creating some bookings and payments...');
        $users = User::where('role', 'user')->get();
        $routes = Route::all();

        if ($users->isNotEmpty() && $routes->isNotEmpty()) {
            foreach ($users->take(5) as $user) { // Create bookings for first 5 users
                $route = $routes->random();
                $numberOfSeats = rand(1, 4);
                $totalFare = $route->fare_per_seat * $numberOfSeats;

                $booking = Booking::create([
                    'user_id' => $user->id,
                    'route_id' => $route->id,
                    'from_station' => $route->origin,
                    'to_station' => $route->destination,
                    'journey_date' => Carbon::today()->addDays(rand(1, 30)),
                    'seat_type' => $this->faker->randomElement(['economy', 'business']),
                    'number_of_seats' => $numberOfSeats,
                    'total_fare' => $totalFare,
                    'status' => $this->faker->randomElement(['pending', 'confirmed', 'cancelled']),
                ]);

                if ($booking->status === 'confirmed') {
                    Payment::create([
                        'booking_id' => $booking->id,
                        'transaction_id' => 'TRX-' . Str::upper(Str::random(10)),
                        'amount' => $totalFare,
                        'currency' => 'BDT',
                        'status' => 'completed',
                    ]);
                }
            }
        }
        $this->info('Bookings and payments created.');

        $this->info('Database population complete!');
    }
}