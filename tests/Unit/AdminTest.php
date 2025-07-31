<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $regularUser;

    protected function setUp(): void
    {
        parent::setUp();
        User::query()->delete(); // Clear all users to ensure a clean state for this test suite
        $this->adminUser = User::factory()->create(['role' => 'admin']);
        $this->regularUser = User::factory()->create(['role' => 'user']);
    }

    // User Management Tests
    public function test_admin_can_get_all_users()
    {
        $response = $this->actingAs($this->adminUser, 'sanctum')->getJson('/api/admin/users');

        $response->assertStatus(200)
                 ->assertJsonCount(2); // Admin and regular user
    }

    public function test_regular_user_cannot_get_all_users()
    {
        $response = $this->actingAs($this->regularUser, 'sanctum')->getJson('/api/admin/users');

        $response->assertStatus(403);
    }

    public function test_admin_can_create_user()
    {
        $response = $this->actingAs($this->adminUser, 'sanctum')->postJson('/api/admin/users', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password',
            'role' => 'user',
        ]);

        $response->assertStatus(201)
                 ->assertJson(['email' => 'newuser@example.com', 'role' => 'user']);

        $this->assertDatabaseHas('users', ['email' => 'newuser@example.com']);
    }

    public function test_admin_can_update_user()
    {
        $userToUpdate = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($this->adminUser, 'sanctum')->putJson('/api/admin/users/' . $userToUpdate->id, [
            'name' => 'Updated Name',
            'role' => 'admin',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['name' => 'Updated Name', 'role' => 'admin']);

        $this->assertDatabaseHas('users', [
            'id' => $userToUpdate->id,
            'name' => 'Updated Name',
            'role' => 'admin',
        ]);
    }

    public function test_admin_can_delete_user()
    {
        $userToDelete = User::factory()->create();

        $response = $this->actingAs($this->adminUser, 'sanctum')->deleteJson('/api/admin/users/' . $userToDelete->id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('users', ['id' => $userToDelete->id]);
    }

    // Booking Management Tests
    public function test_admin_can_get_all_bookings()
    {
        Booking::factory()->count(3)->create();

        $response = $this->actingAs($this->adminUser, 'sanctum')->getJson('/api/admin/bookings');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    public function test_admin_can_update_booking_status()
    {
        $booking = Booking::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($this->adminUser, 'sanctum')->putJson('/api/admin/bookings/' . $booking->id . '/status', [
            'status' => 'confirmed',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['status' => 'confirmed']);

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'confirmed',
        ]);
    }

    public function test_admin_can_delete_booking()
    {
        $booking = Booking::factory()->create();

        $response = $this->actingAs($this->adminUser, 'sanctum')->deleteJson('/api/admin/bookings/' . $booking->id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('bookings', ['id' => $booking->id]);
    }
}
