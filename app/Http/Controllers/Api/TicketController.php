<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Pnr;

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

        $booking = Booking::where('id', $pnr->booking_id)
            ->where('journey_date', $request->journey_date)
            ->first();

        if (!$booking) {
            return response()->json(['message' => 'Ticket not found for the given date.'], 404);
        }

        return response()->json($booking);
    }
}
