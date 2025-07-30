<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $booking;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->booking = Booking::factory()->create(['user_id' => $this->user->id, 'total_fare' => 150.00]);
    }

    public function test_user_can_initiate_payment()
    {
        $response = $this->actingAs($this->user, 'sanctum')->postJson('/api/payments/initiate/' . $this->booking->id);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'message', 'payment_id', 'transaction_id', 'amount', 'redirect_url',
                 ]);

        $this->assertDatabaseHas('payments', [
            'booking_id' => $this->booking->id,
            'amount' => 150.00,
            'status' => 'pending',
        ]);
    }

    public function test_payment_success_callback_updates_status()
    {
        $payment = Payment::factory()->create([
            'booking_id' => $this->booking->id,
            'transaction_id' => 'TRX_TEST_SUCCESS',
            'status' => 'pending',
        ]);

        $response = $this->postJson('/api/payments/success', ['tran_id' => 'TRX_TEST_SUCCESS']);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Payment successful']);

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'completed',
        ]);

        $this->assertDatabaseHas('bookings', [
            'id' => $this->booking->id,
            'status' => 'confirmed',
        ]);
    }

    public function test_payment_fail_callback_updates_status()
    {
        $payment = Payment::factory()->create([
            'booking_id' => $this->booking->id,
            'transaction_id' => 'TRX_TEST_FAIL',
            'status' => 'pending',
        ]);

        $response = $this->postJson('/api/payments/fail', ['tran_id' => 'TRX_TEST_FAIL']);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Payment failed']);

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'failed',
        ]);
    }

    public function test_payment_cancel_callback_updates_status()
    {
        $payment = Payment::factory()->create([
            'booking_id' => $this->booking->id,
            'transaction_id' => 'TRX_TEST_CANCEL',
            'status' => 'pending',
        ]);

        $response = $this->postJson('/api/payments/cancel', ['tran_id' => 'TRX_TEST_CANCEL']);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Payment cancelled']);

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'cancelled',
        ]);
    }

    public function test_ipn_callback_updates_status()
    {
        $payment = Payment::factory()->create([
            'booking_id' => $this->booking->id,
            'transaction_id' => 'TRX_TEST_IPN',
            'status' => 'pending',
        ]);

        $response = $this->postJson('/api/payments/ipn', ['tran_id' => 'TRX_TEST_IPN']);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'IPN received and processed']);

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'completed',
        ]);

        $this->assertDatabaseHas('bookings', [
            'id' => $this->booking->id,
            'status' => 'confirmed',
        ]);
    }
}
