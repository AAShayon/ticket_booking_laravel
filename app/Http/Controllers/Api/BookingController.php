<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *      name="Bookings",
 *      description="API Endpoints for Booking Management"
 * )
 *
 * @OA\Schema(
 *     schema="Booking",
 *     title="Booking",
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="user_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="from_station", type="string", example="Station A"),
 *     @OA\Property(property="to_station", type="string", example="Station B"),
 *     @OA\Property(property="journey_date", type="string", format="date", example="2025-12-25"),
 *     @OA\Property(property="seat_type", type="string", example="Economy"),
 *     @OA\Property(property="number_of_seats", type="integer", example=2),
 *     @OA\Property(property="total_fare", type="number", format="float", example=100.00),
 *     @OA\Property(property="status", type="string", example="pending"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-01T00:00:00.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-01T00:00:00.000000Z"),
 * )
 *
 * @OA\Schema(
 *     schema="BookingList",
 *     title="Booking List",
 *     type="array",
 *     @OA\Items(ref="#/components/schemas/Booking")
 * )
 */
class BookingController extends Controller
{
    /**
     * @OA\Get(
     *     path="/bookings",
     *     tags={"Bookings"},
     *     summary="Get all bookings for the authenticated user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/BookingList")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     )
     * )
     */
    public function index()
    {
        $bookings = Auth::user()->bookings()->get();
        return response()->json($bookings);
    }

    /**
     * @OA\Post(
     *     path="/bookings",
     *     tags={"Bookings"},
     *     summary="Create a new booking",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"from_station","to_station","journey_date","seat_type","number_of_seats","total_fare"},
     *             @OA\Property(property="from_station", type="string", example="Station C"),
     *             @OA\Property(property="to_station", type="string", example="Station D"),
     *             @OA\Property(property="journey_date", type="string", format="date", example="2025-12-30"),
     *             @OA\Property(property="seat_type", type="string", example="Business"),
     *             @OA\Property(property="number_of_seats", type="integer", example=1),
     *             @OA\Property(property="total_fare", type="number", format="float", example=250.00),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Booking created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Booking")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'from_station' => 'required|string',
            'to_station' => 'required|string',
            'journey_date' => 'required|date',
            'seat_type' => 'required|string',
            'number_of_seats' => 'required|integer|min:1',
            'total_fare' => 'required|numeric|min:0',
        ]);

        $booking = Auth::user()->bookings()->create(array_merge($request->all(), ['status' => 'pending']));

        return response()->json($booking, 201);
    }

    /**
     * @OA\Get(
     *     path="/bookings/{id}",
     *     tags={"Bookings"},
     *     summary="Get a specific booking by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the booking to retrieve"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Booking")
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
    public function show(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return response()->json($booking);
    }

    /**
     * @OA\Put(
     *     path="/bookings/{id}",
     *     tags={"Bookings"},
     *     summary="Update a specific booking by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the booking to update"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="from_station", type="string", example="Station E"),
     *             @OA\Property(property="status", type="string", example="confirmed"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Booking updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Booking")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Booking not found",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     */
    public function update(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'from_station' => 'sometimes|required|string',
            'to_station' => 'sometimes|required|string',
            'journey_date' => 'sometimes|required|date',
            'seat_type' => 'sometimes|required|string',
            'number_of_seats' => 'sometimes|required|integer|min:1',
            'total_fare' => 'sometimes|required|numeric|min:0',
            'status' => 'sometimes|required|string',
        ]);

        $booking->update($request->all());

        return response()->json($booking);
    }

    /**
     * @OA\Delete(
     *     path="/bookings/{id}",
     *     tags={"Bookings"},
     *     summary="Delete a specific booking by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the booking to delete"
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Booking deleted successfully",
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
    public function destroy(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $booking->delete();

        return response()->json(null, 204);
    }
}
