<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Pnr;
use Carbon\Carbon;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *      name="Tickets",
 *      description="API Endpoints for Ticket Management"
 * )
 */
class TicketController extends Controller
{
    /**
     * @OA\Post(
     *     path="/ticket/check",
     *     tags={"Tickets"},
     *     summary="Check ticket details by PNR",
     *      security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"pnr_number", "journey_date"},
     *             @OA\Property(property="pnr_number", type="string", example="ABCDEF"),
     *             @OA\Property(property="journey_date", type="string", format="date", example="2025-12-25"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ticket details",
     *         @OA\JsonContent(ref="#/components/schemas/Booking")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Ticket not found"
     *     ),
     *      @OA\Response(
     *         response=403,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function check(Request $request)
    {
        $request->validate([
            'pnr_number' => 'required|string|exists:pnrs,pnr_number',
            'journey_date' => 'required|date_format:Y-m-d',
        ]);

        $pnr = Pnr::where('pnr_number', $request->pnr_number)->first();

        if (!$pnr) {
            return response()->json(['message' => 'Ticket not found.'], 404);
        }

        // Load the user relationship and route relationship
        $booking = Booking::with('user', 'route') // Load user and route
            ->where('id', $pnr->booking_id)
            ->where('journey_date', $request->journey_date)
            ->first();

        if (!$booking) {
            return response()->json(['message' => 'Ticket not found for the given date.'], 404);
        }

        if ($booking->user_id !== auth()->id()) {
            return response()->json(['message' => 'You are not authorized to view this ticket.'], 403);
        }

        $bookingArray = $booking->toArray();

        // Add user_name
        $bookingArray['user_name'] = $booking->user->name;
        unset($bookingArray['user']); // Remove the full user object

        // Format departure time and add to response
        if ($booking->route && $booking->route->departure_time) {
            $departureTime = Carbon::parse($booking->route->departure_time);
            $bookingArray['formatted_departure_time'] = $departureTime->format('h:i A'); // e.g., 07:00 PM
        } else {
            $bookingArray['formatted_departure_time'] = null;
        }

        // Remove the full route object
        unset($bookingArray['route']);

        return response()->json($bookingArray);
    }
}
