<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FullApiTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $regularUser;
    protected $operatorUser;

    protected function setUp(): void
    {
        parent::setUp();

        // The RefreshDatabase trait handles migration and seeding.
        // Retrieve users after seeding.
        $this->adminUser = \App\Models\User::where('email', 'superadmin@example.com')->first();
        $this->regularUser = \App\Models\User::where('email', 'user@example.com')->first();
        $this->operatorUser = \App\Models\User::where('email', 'operator1@example.com')->first();
    }

    // Test public routes
    public function test_public_can_view_operators()
    {
        $response = $this->getJson('/api/operators/public');
        $response->assertStatus(200);
        $response->assertJsonCount(2); // Assuming 2 operators from TestDataSeeder
    }

    public function test_public_can_view_routes()
    {
        $response = $this->getJson('/api/routes/public');
        $response->assertStatus(200);
        $response->assertJsonCount(4); // Assuming 4 routes from TestDataSeeder
    }

    // Test auth routes
    public function test_user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'New Registered User',
            'email' => 'newregistered@example.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => 'newregistered@example.com']);
    }

    public function test_user_can_login()
    {
        $response = $this->postJson('/api/login', [
            'email' => $this->regularUser->email,
            'password' => 'password'
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure(['access_token']);
    }

    public function test_user_can_logout()
    {
        // Create a token for the user before logging out
        $token = $this->regularUser->createToken('test_token')->plainTextToken;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');
        $response->assertStatus(200);
    }

    // Test authenticated routes
    public function test_user_can_get_their_own_data()
    {
        $response = $this->actingAs($this->regularUser, 'sanctum')->getJson('/api/user');
        $response->assertStatus(200);
        $response->assertJson(['email' => $this->regularUser->email]);
    }

    public function test_user_can_create_booking()
    {
        $route = \App\Models\Route::first(); // Get an existing route from seeder
        $response = $this->actingAs($this->regularUser, 'sanctum')->postJson('/api/bookings', [
            'route_id' => $route->id,
            'from_station' => 'Station A',
            'to_station' => 'Station B',
            'journey_date' => '2025-12-25',
            'seat_type' => 'economy',
            'number_of_seats' => 2,
            'total_fare' => 200,
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('bookings', ['user_id' => $this->regularUser->id]);
    }

    public function test_user_can_view_their_bookings()
    {
        \App\Models\Booking::factory()->create(['user_id' => $this->regularUser->id]);
        $response = $this->actingAs($this->regularUser, 'sanctum')->getJson('/api/bookings');
        $response->assertStatus(200);
        $response->assertJsonCount(1); // Assuming one booking created in this test
    }

    public function test_user_can_search_routes()
    {
        $response = $this->actingAs($this->regularUser, 'sanctum')->getJson('/api/routes/search?origin=Dhaka&destination=Chittagong&journey_date=2025-12-25');
        $response->assertStatus(200);
        $response->assertJsonCount(1); // Assuming one route from Dhaka to Chittagong
    }

    // Operator Routes
    public function test_operator_can_manage_vehicles()
    {
        $operator = \App\Models\Operator::where('user_id', $this->operatorUser->id)->first();
        $vehicle = \App\Models\Vehicle::factory()->create(['operator_id' => $operator->id]);

        // View all vehicles
        $response = $this->actingAs($this->operatorUser, 'sanctum')->getJson('/api/vehicles');
        $response->assertStatus(200);

        // Create a new vehicle
        $response = $this->actingAs($this->operatorUser, 'sanctum')->postJson('/api/vehicles', [
            'operator_id' => $operator->id,
            'model_number' => 'MN-123',
            'type' => 'AC',
            'capacity' => 50
        ]);
        $response->assertStatus(201);

        // Update the vehicle
        $response = $this->actingAs($this->operatorUser, 'sanctum')->putJson('/api/vehicles/' . $vehicle->id, [
            'type' => 'Non-AC',
        ]);
        $response->assertStatus(200);

        // Delete the vehicle
        $response = $this->actingAs($this->operatorUser, 'sanctum')->deleteJson('/api/vehicles/' . $vehicle->id);
        $response->assertStatus(204);
    }

    // Admin Routes
    public function test_admin_can_manage_operators()
    {
        $operator = \App\Models\Operator::first();

        // View all operators
        $response = $this->actingAs($this->adminUser, 'sanctum')->getJson('/api/operators');
        $response->assertStatus(200);
        $response->assertJsonCount(2); // Assuming 2 operators from TestDataSeeder

        // Create a new operator
        $newUser = \App\Models\User::factory()->create();
        $response = $this->actingAs($this->adminUser, 'sanctum')->postJson('/api/operators', [
            'user_id' => $newUser->id,
            'name' => 'New Admin Created Operator',
            'contact_email' => 'admin.op@example.com',
            'contact_phone' => '+8801300000000',
            'admin_commission_percentage' => 10.0,
            'nid' => '1234567890',
            'address' => 'Dhaka, Bangladesh',
            'transport_business_license' => 'TBL-003',
            'vehicles' => [['model_number' => 'test-123', 'type' => 'AC', 'capacity' => 40]]
        ]);
        $response->assertStatus(201);

        // Update the operator
        $response = $this->actingAs($this->adminUser, 'sanctum')->putJson('/api/operators/' . $operator->id, [
            'name' => 'Updated Operator',
        ]);
        $response->assertStatus(200);

        // Delete the operator
        $response = $this->actingAs($this->adminUser, 'sanctum')->deleteJson('/api/operators/' . $operator->id);
        $response->assertStatus(204);
    }

    public function test_admin_can_manage_routes()
    {
        $route = \App\Models\Route::first();

        // View all routes
        $response = $this->actingAs($this->adminUser, 'sanctum')->getJson('/api/routes');
        $response->assertStatus(200);
        $response->assertJsonCount(4); // Assuming 4 routes from TestDataSeeder

        // Create a new route
        $operator = \App\Models\Operator::first();
        $vehicle = \App\Models\Vehicle::first();
        $response = $this->actingAs($this->adminUser, 'sanctum')->postJson('/api/routes', [
            'operator_id' => $operator->id,
            'vehicle_id' => $vehicle->id,
            'origin' => 'City C',
            'destination' => 'City D',
            'departure_time' => '10:00:00',
            'fare_per_seat' => 100,
            'vehicle_number' => 'VN-123',
            'time_of_day' => 'morning'
        ]);
        $response->assertStatus(201);

        // Update the route
        $response = $this->actingAs($this->adminUser, 'sanctum')->putJson('/api/routes/' . $route->id, [
            'origin' => 'City E',
        ]);
        $response->assertStatus(200);

        // Delete the route
        $response = $this->actingAs($this->adminUser, 'sanctum')->deleteJson('/api/routes/' . $route->id);
        $response->assertStatus(204);
    }

    public function test_admin_can_manage_operator_requests()
    {
        $operatorRequest = \App\Models\OperatorRequest::factory()->create(['status' => 'pending']);

        // View all operator requests
        $response = $this->actingAs($this->adminUser, 'sanctum')->getJson('/api/admin/operator-requests');
        $response->assertStatus(200);

        // Approve an operator request
        $response = $this->actingAs($this->adminUser, 'sanctum')->postJson('/api/admin/operator-requests/' . $operatorRequest->id . '/approve');
        $response->assertStatus(200);

        // Reject an operator request
        $newOperatorRequest = \App\Models\OperatorRequest::factory()->create(['status' => 'pending']);
        $response = $this->actingAs($this->adminUser, 'sanctum')->postJson('/api/admin/operator-requests/' . $newOperatorRequest->id . '/reject');
        $response->assertStatus(200);
    }

    public function test_admin_can_get_daily_summary()
    {
        // Test with admin user
        $response = $this->actingAs($this->adminUser, 'sanctum')->getJson('/api/admin/daily-summary');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'new_users_today',
            'total_bookings_today',
            'total_income_today',
        ]);

        // Test with regular user (should be forbidden)
        $response = $this->actingAs($this->regularUser, 'sanctum')->getJson('/api/admin/daily-summary');
        $response->assertStatus(403);

        // Test with operator user (should be forbidden)
        $response = $this->actingAs($this->operatorUser, 'sanctum')->getJson('/api/admin/daily-summary');
        $response->assertStatus(403);
    }
}