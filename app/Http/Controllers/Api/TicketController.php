<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Pnr;
use Carbon\Carbon;

class TicketController extends Controller
{
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