<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Pnr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *      name="Payments",
 *      description="API Endpoints for Payment Processing"
 * )
 *
 * @OA\Schema(
 *     schema="Payment",
 *     title="Payment",
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="booking_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="transaction_id", type="string", example="TRX_123456789"),
 *     @OA\Property(property="amount", type="number", format="float", example=150.00),
 *     @OA\Property(property="currency", type="string", example="BDT"),
 *     @OA\Property(property="status", type="string", example="pending"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-01T00:00:00.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-01T00:00:00.000000Z"),
 * )
 */
class PaymentController extends Controller
{
    /**
     * @OA\Post(
     *     path="/payments/initiate/{booking}",
     *     tags={"Payments"},
     *     summary="Initiate a payment for a booking",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="booking",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the booking to initiate payment for"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payment initiated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Payment initiated"),
     *             @OA\Property(property="payment_id", type="integer", example=1),
     *             @OA\Property(property="transaction_id", type="string", example="TRX_abcdef12345"),
     *             @OA\Property(property="amount", type="number", format="float", example=150.00),
     *             @OA\Property(property="redirect_url", type="string", example="http://example.com/sslcommerz-payment-gateway?trxid=TRX_abcdef12345"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Booking not found",
     *     )
     * )
     */
    public function initiatePayment(Request $request, Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // For simplicity, assuming SSLCommerz integration details are handled by a service
        // In a real application, you would integrate with the SSLCommerz SDK here.
        // This is a placeholder for the actual payment initiation logic.

        $amount = $booking->total_fare;
        $transactionId = 'TRX_' . uniqid(); // Generate a unique transaction ID

        // Simulate payment initiation success
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'currency' => Config::get('sslcommerz.currency'),
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Payment initiated',
            'payment_id' => $payment->id,
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'redirect_url' => 'http://example.com/sslcommerz-payment-gateway?trxid=' . $transactionId // Placeholder
        ]);
    }

    /**
     * @OA\Post(
     *     path="/payments/success",
     *     tags={"Payments"},
     *     summary="Callback URL for successful payments",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"tran_id"},
     *             @OA\Property(property="tran_id", type="string", example="TRX_abcdef12345"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payment successful",
     *         @OA\JsonContent(ref="#/components/schemas/Payment")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Payment not found",
     *     )
     * )
     */
    public function paymentSuccess(Request $request)
    {
        // Handle successful payment callback from SSLCommerz
        // Verify transaction and update payment status
        $transactionId = $request->input('tran_id');
        $payment = Payment::where('transaction_id', $transactionId)->first();

        if ($payment) {
            $payment->status = 'completed';
            $payment->save();
            $payment->booking->status = 'confirmed';
            $payment->booking->save();

            $pnr = \App\Models\Pnr::create([
                'booking_id' => $payment->booking->id,
                'pnr_number' => generatePnr(),
            ]);

            return response()->json(['message' => 'Payment successful', 'payment' => $payment, 'pnr' => $pnr]);
        }

        return response()->json(['message' => 'Payment not found'], 404);
    }

    /**
     * @OA\Post(
     *     path="/payments/fail",
     *     tags={"Payments"},
     *     summary="Callback URL for failed payments",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"tran_id"},
     *             @OA\Property(property="tran_id", type="string", example="TRX_abcdef12345"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payment failed",
     *         @OA\JsonContent(ref="#/components/schemas/Payment")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Payment not found",
     *     )
     * )
     */
    public function paymentFail(Request $request)
    {
        // Handle failed payment callback from SSLCommerz
        $transactionId = $request->input('tran_id');
        $payment = Payment::where('transaction_id', $transactionId)->first();

        if ($payment) {
            $payment->status = 'failed';
            $payment->save();
            return response()->json(['message' => 'Payment failed', 'payment' => $payment]);
        }

        return response()->json(['message' => 'Payment not found'], 404);
    }

    /**
     * @OA\Post(
     *     path="/payments/cancel",
     *     tags={"Payments"},
     *     summary="Callback URL for cancelled payments",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"tran_id"},
     *             @OA\Property(property="tran_id", type="string", example="TRX_abcdef12345"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payment cancelled",
     *         @OA\JsonContent(ref="#/components/schemas/Payment")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Payment not found",
     *     )
     * )
     */
    public function paymentCancel(Request $request)
    {
        // Handle cancelled payment callback from SSLCommerz
        $transactionId = $request->input('tran_id');
        $payment = Payment::where('transaction_id', $transactionId)->first();

        if ($payment) {
            $payment->status = 'cancelled';
            $payment->save();
            return response()->json(['message' => 'Payment cancelled', 'payment' => $payment]);
        }

        return response()->json(['message' => 'Payment not found'], 404);
    }

    /**
     * @OA\Post(
     *     path="/payments/ipn",
     *     tags={"Payments"},
     *     summary="Callback URL for Instant Payment Notification (IPN)",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"tran_id"},
     *             @OA\Property(property="tran_id", type="string", example="TRX_abcdef12345"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="IPN received and processed",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="IPN received and processed"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Payment not found",
     *     )
     * )
     */
    public function ipn(Request $request)
    {
        // Handle IPN (Instant Payment Notification) from SSLCommerz
        // This is typically a server-to-server communication
        $transactionId = $request->input('tran_id');
        $payment = Payment::where('transaction_id', $transactionId)->first();

        if ($payment) {
            // Verify IPN data and update payment status accordingly
            // For simplicity, assuming IPN always means success here
            $payment->status = 'completed';
            $payment->save();
            $payment->booking->status = 'confirmed';
            $payment->booking->save();

            $pnr = \App\Models\Pnr::create([
                'booking_id' => $payment->booking->id,
                'pnr_number' => generatePnr(),
            ]);

            return response()->json(['message' => 'IPN received and processed', 'pnr' => $pnr]);
        }

        return response()->json(['message' => 'Payment not found'], 404);
    }
}
