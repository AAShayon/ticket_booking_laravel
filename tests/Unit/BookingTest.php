<?php

namespace Tests\Unit;

use App\Models\Route;
use App\Models\Vehicle;
use App\Models\Operator;
use Tests\TestCase;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->operator = User::factory()->create(['role' => 'operator']);
    }

    public function test_user_can_create_booking()
    {
        $operator = Operator::factory()->create(['user_id' => $this->operator->id]);
        $vehicle = Vehicle::factory()->create(['operator_id' => $operator->id]);
        $route = Route::factory()->create(['operator_id' => $operator->id, 'vehicle_id' => $vehicle->id]);

        $response = $this->actingAs($this->user, 'sanctum')->postJson('/api/bookings', [
            'route_id' => $route->id,
            'from_station' => 'Station A',
            'to_station' => 'Station B',
            'journey_date' => '2025-12-25',
            'seat_type' => 'Economy',
            'number_of_seats' => 2,
            'total_fare' => 100.00,
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'id', 'user_id', 'from_station', 'to_station',
                     'journey_date', 'seat_type', 'number_of_seats',
                     'total_fare', 'status', 'created_at', 'updated_at',
                 ]);

        $this->assertDatabaseHas('bookings', [
            'user_id' => $this->user->id,
            'route_id' => $route->id,
            'from_station' => 'Station A',
            'to_station' => 'Station B',
            'journey_date' => '2025-12-25',
            'seat_type' => 'Economy',
            'number_of_seats' => 2,
            'total_fare' => 100.00,
        ]);
    }

    public function test_user_can_view_their_bookings()
    {
        Booking::factory()->create(['user_id' => $this->user->id, 'from_station' => 'Station X']);
        Booking::factory()->create(['user_id' => $this->admin->id, 'from_station' => 'Station Y']);

        $response = $this->actingAs($this->user, 'sanctum')->getJson('/api/bookings');

        $response->assertStatus(200)
                 ->assertJsonCount(1);

        $response->assertJsonFragment(['from_station' => 'Station X']);
        $response->assertJsonMissing(['from_station' => 'Station Y']);
    }

    public function test_user_can_view_a_specific_booking()
    {
        $booking = Booking::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user, 'sanctum')->getJson('/api/bookings/' . $booking->id);

        $response->assertStatus(200)
                 ->assertJson(['id' => $booking->id]);
    }

    public function test_user_cannot_view_other_users_booking()
    {
        $booking = Booking::factory()->create(['user_id' => $this->admin->id]);

        $response = $this->actingAs($this->user, 'sanctum')->getJson('/api/bookings/' . $booking->id);

        $response->assertStatus(403);
    }

    public function test_user_can_update_their_booking()
    {
        $booking = Booking::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user, 'sanctum')->putJson('/api/bookings/' . $booking->id, [
            'seat_type' => 'Business',
            'total_fare' => 200.00,
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['seat_type' => 'Business', 'total_fare' => 200.00]);

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'seat_type' => 'Business',
            'total_fare' => 200.00,
        ]);
    }

    public function test_user_cannot_update_other_users_booking()
    {
        $booking = Booking::factory()->create(['user_id' => $this->admin->id]);

        $response = $this->actingAs($this->user, 'sanctum')->putJson('/api/bookings/' . $booking->id, [
            'seat_type' => 'Business',
        ]);

        $response->assertStatus(403);
    }

    public function test_user_can_delete_their_booking()
    {
        $booking = Booking::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user, 'sanctum')->deleteJson('/api/bookings/' . $booking->id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('bookings', ['id' => $booking->id]);
    }

    public function test_user_cannot_delete_other_users_booking()
    {
        $booking = Booking::factory()->create(['user_id' => $this->admin->id]);

        $response = $this->actingAs($this->user, 'sanctum')->deleteJson('/api/bookings/' . $booking->id);

        $response->assertStatus(403);
    }
}
