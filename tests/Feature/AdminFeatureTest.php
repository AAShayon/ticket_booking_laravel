<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $regularUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adminUser = User::factory()->create(['role' => 'admin']);
        $this->regularUser = User::factory()->create(['role' => 'user']);
    }

    public function test_admin_can_access_admin_dashboard()
    {
        $response = $this->actingAs($this->adminUser, 'sanctum')->getJson('/api/admin/users');
        $response->assertStatus(200);
    }

    public function test_regular_user_cannot_access_admin_dashboard()
    {
        $response = $this->actingAs($this->regularUser, 'sanctum')->getJson('/api/admin/users');
        $response->assertStatus(403);
    }

    public function test_admin_can_manage_users()
    {
        // Create a new user
        $response = $this->actingAs($this->adminUser, 'sanctum')->postJson('/api/admin/users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'role' => 'user',
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
        $newUser = User::where('email', 'test@example.com')->first();

        // Update the user
        $response = $this->actingAs($this->adminUser, 'sanctum')->putJson('/api/admin/users/' . $newUser->id, [
            'name' => 'Updated User',
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['id' => $newUser->id, 'name' => 'Updated User']);

        // Delete the user
        $response = $this->actingAs($this->adminUser, 'sanctum')->deleteJson('/api/admin/users/' . $newUser->id);
        $response->assertStatus(204);
        $this->assertDatabaseMissing('users', ['id' => $newUser->id]);
    }

    public function test_admin_can_manage_bookings()
    {
        $booking = Booking::factory()->create();

        // View all bookings
        $response = $this->actingAs($this->adminUser, 'sanctum')->getJson('/api/admin/bookings');
        $response->assertStatus(200);

        // Update booking status
        $response = $this->actingAs($this->adminUser, 'sanctum')->putJson('/api/admin/bookings/' . $booking->id . '/status', [
            'status' => 'confirmed',
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'status' => 'confirmed']);

        // Delete the booking
        $response = $this->actingAs($this->adminUser, 'sanctum')->deleteJson('/api/admin/bookings/' . $booking->id);
        $response->assertStatus(204);
        $this->assertDatabaseMissing('bookings', ['id' => $booking->id]);
    }
}
