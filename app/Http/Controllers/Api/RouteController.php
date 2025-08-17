<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Route;
use App\Models\Booking;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Illuminate\Validation\Rule;

/**
 * @OA\Tag(
 *      name="Routes",
 *      description="API Endpoints for Bus Route Management (Admin Only)"
 * )
 *
 * @OA\Schema(
 *     schema="BusRoute",
 *     title="Bus Route",
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="operator_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="origin", type="string", example="Dhaka"),
 *     @OA\Property(property="destination", type="string", example="Chittagong"),
 *     @OA\Property(property="fare", type="number", format="float", example=1200.00),
 *     @OA\Property(property="estimated_travel_time", type="string", example="6 hours"),
 *     @OA\Property(property="vehicle_number", type="string", example="ABC-123"),
 *     @OA\Property(property="time_of_day", type="string", example="morning"),
 *     @OA\Property(property="departure_time", type="string", format="time", example="06:30:00"),
 *     @OA\Property(property="vehicle_id", type="integer", format="int64", example=1, description="ID of the vehicle assigned to this route"),
 *     @OA\Property(property="fare_per_seat", type="number", format="float", example=500.00, description="Fare per seat for this route"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-01T00:00:00.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-01T00:00:00.000000Z"),
 * )
 *
 * @OA\Schema(
 *     schema="BusRouteList",
 *     title="Bus Route List",
 *     type="array",
 *     @OA\Items(ref="#/components/schemas/BusRoute")
 * )
 */
class RouteController extends Controller
{
    // public function __construct()
    // {
    //     parent::__construct();
    //     $this->middleware('role:admin');
    // }

    /**
     * @OA\Get(
     *     path="/routes",
     *     tags={"Routes"},
     *     summary="Get all bus routes (Admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/BusRouteList")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized",
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = \App\Models\Route::query();

        if ($request->has('origin')) {
            $query->where('origin', 'like', '%' . $request->input('origin') . '%');
        }

        if ($request->has('destination')) {
            $query->where('destination', 'like', '%' . $request->input('destination') . '%');
        }

        if ($request->has('operator_id')) {
            $query->where('operator_id', $request->input('operator_id'));
        }

        if ($request->has('min_fare')) {
            $query->where('fare', '>=', $request->input('min_fare'));
        }

        if ($request->has('max_fare')) {
            $query->where('fare', '<=', $request->input('max_fare'));
        }

        if ($request->has('date')) {
            // Assuming 'date' is stored as a date column or part of created_at/updated_at
            // You might need to adjust this based on how dates are stored for routes
            $query->whereDate('created_at', $request->input('date'));
        }

        // Sorting
        if ($request->has('sort_by') && $request->has('sort_order')) {
            $query->orderBy($request->input('sort_by'), $request->input('sort_order', 'asc'));
        }

        $perPage = $request->input('limit', 15);
        $routes = $query->with('operator', 'vehicle')->paginate($perPage);

        return \App\Http\Resources\RouteResource::collection($routes);
    }

    /**
     * @OA\Get(
     *     path="/routes/public",
     *     tags={"Routes"},
     *     summary="Get all bus routes (Publicly accessible)",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/BusRouteList")
     *     )
     * )
     */
    public function publicIndex()
    {
        $routes = \App\Models\Route::with('operator', 'vehicle')->get();
        return \App\Http\Resources\RouteResource::collection($routes);
    }

    /**
     * @OA\Post(
     *     path="/routes",
     *     tags={"Routes"},
     *     summary="Create a new bus route (Admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"operator_id","vehicle_id","origin","destination","fare","fare_per_seat","estimated_travel_time","vehicle_number","time_of_day","departure_time"},
     *             @OA\Property(property="operator_id", type="integer", example=1),
     *             @OA\Property(property="vehicle_id", type="integer", example=1),
     *             @OA\Property(property="origin", type="string", example="Dhaka"),
     *             @OA\Property(property="destination", type="string", example="Chittagong"),
     *             @OA\Property(property="fare", type="number", format="float", example=1200.00),
     *             @OA\Property(property="fare_per_seat", type="number", format="float", example=500.00),
     *             @OA\Property(property="estimated_travel_time", type="string", example="6 hours"),
     *             @OA\Property(property="vehicle_number", type="string", example="ABC-123"),
     *             @OA\Property(property="time_of_day", type="string", example="morning"),
     *             @OA\Property(property="departure_time", type="string", format="time", example="06:30:00"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Route created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/BusRoute")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized",
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'operator_id' => 'required|exists:operators,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'origin' => 'required|string',
            'destination' => 'required|string',
            'fare_per_seat' => 'required|numeric|min:0',
            'estimated_travel_time' => 'nullable|string',
            'vehicle_number' => 'required|string|max:255',
            'time_of_day' => 'required|string|in:morning,afternoon,evening,night,am,pm',
            'departure_time' => 'required|date_format:H:i:s',
        ]);

        $vehicle = \App\Models\Vehicle::find($request->vehicle_id);
        $calculatedFare = $request->fare_per_seat * $vehicle->capacity;

        $route = Route::create(array_merge($request->all(), ['fare' => $calculatedFare]));

        return response()->json($route, 201);
    }

    /**
     * @OA\Get(
     *     path="/routes/{id}",
     *     tags={"Routes"},
     *     summary="Get a specific bus route by ID (Admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the route to retrieve"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/BusRoute")
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
     *         description="Route not found",
     *     )
     * )
     */
    public function show(Route $route)
    {
        return response()->json($route);
    }

    /**
     * @OA\Put(
     *     path="/routes/{id}",
     *     tags={"Routes"},
     *     summary="Update a specific bus route by ID (Admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the route to update"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="origin", type="string", example="Sylhet"),
     *             @OA\Property(property="fare", type="number", format="float", example=1500.00),
     *             @OA\Property(property="vehicle_number", type="string", example="XYZ-789"),
     *             @OA\Property(property="time_of_day", type="string", example="evening"),
     *             @OA\Property(property="departure_time", type="string", format="time", example="18:00:00"),
     *             @OA\Property(property="vehicle_id", type="integer", example=1),
     *             @OA\Property(property="fare_per_seat", type="number", format="float", example=500.00),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Route updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/BusRoute")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
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
     *         description="Route not found",
     *     )
     * )
     */
    public function update(Request $request, Route $route)
    {
        $request->validate([
            'operator_id' => 'sometimes|required|exists:operators,id',
            'vehicle_id' => 'sometimes|required|exists:vehicles,id',
            'origin' => 'sometimes|required|string',
            'destination' => 'sometimes|required|string',
            'fare_per_seat' => 'sometimes|required|numeric|min:0',
            'estimated_travel_time' => 'nullable|string',
            'vehicle_number' => 'sometimes|required|string|max:255',
            'time_of_day' => 'sometimes|required|string|in:morning,afternoon,evening,night,am,pm',
            'departure_time' => 'sometimes|required|date_format:H:i:s',
        ]);

        $data = $request->all();

        if ($request->has('fare_per_seat') && $request->has('vehicle_id')) {
            $vehicle = \App\Models\Vehicle::find($request->vehicle_id);
            $data['fare'] = $request->fare_per_seat * $vehicle->capacity;
        }

        $route->update($data);

        return response()->json($route);
    }

    /**
     * @OA\Delete(
     *     path="/routes/{id}",
     *     tags={"Routes"},
     *     summary="Delete a bus route by ID (Admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the route to delete"
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Route deleted successfully",
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
     *         description="Route not found",
     *     )
     * )
     */
    public function destroy(Route $route)
    {
        $route->delete();
        return response()->json(null, 204);
    }

    /**
     * @OA\Get(
     *     path="/routes/search",
     *     tags={"Routes"},
     *     summary="Search for available bus routes",
     *     @OA\Parameter(
     *         name="origin",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         description="Origin station"
     *     ),
     *     @OA\Parameter(
     *         name="destination",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         description="Destination station"
     *     ),
     *     @OA\Parameter(
     *         name="journey_date",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string", format="date"),
     *         description="Date of journey (YYYY-MM-DD)"
     *     ),
     *     @OA\Parameter(
     *         name="departure_time",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", format="time"),
     *         description="Departure time (HH:MM:SS)"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="operator_id", type="integer", example=1),
     *                 @OA\Property(property="origin", type="string", example="Dhaka"),
     *                 @OA\Property(property="destination", type="string", example="Chittagong"),
     *                 @OA\Property(property="fare", type="number", format="float", example=1200.00),
     *                 @OA\Property(property="fare_per_seat", type="number", format="float", example=500.00),
     *                 @OA\Property(property="estimated_travel_time", type="string", example="6 hours"),
     *                 @OA\Property(property="vehicle_number", type="string", example="ABC-123"),
     *                 @OA\Property(property="time_of_day", type="string", example="morning"),
     *                 @OA\Property(property="departure_time", type="string", format="time", example="06:30:00"),
     *                 @OA\Property(property="available_seats", type="integer", example=30),
     *                 @OA\Property(property="vehicle_model", type="string", example="Scania K360"),
     *                 @OA\Property(property="vehicle_type", type="string", example="AC"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     */
    public function search(Request $request)
    {
        $request->validate([
            'origin' => 'required|string',
            'destination' => 'required|string',
            'journey_date' => 'required|date_format:Y-m-d',
            'departure_time' => 'nullable|date_format:H:i:s',
        ]);

        $routes = Route::with('vehicle')
            ->where('origin', $request->origin)
            ->where('destination', $request->destination)
            ->withCount(['bookings as booked_seats' => function ($query) use ($request) {
                $query->where('journey_date', $request->journey_date);
            }])
            ->get();

        $results = $routes->map(function ($route) {
            $availableSeats = $route->vehicle->capacity - $route->booked_seats;
            if ($availableSeats > 0) {
                return [
                    'id' => $route->id,
                    'operator_id' => $route->operator_id,
                    'origin' => $route->origin,
                    'destination' => $route->destination,
                    'fare' => $route->fare,
                    'fare_per_seat' => $route->fare_per_seat,
                    'estimated_travel_time' => $route->estimated_travel_time,
                    'vehicle_number' => $route->vehicle_number,
                    'time_of_day' => $route->time_of_day,
                    'departure_time' => $route->departure_time,
                    'available_seats' => $availableSeats,
                    'vehicle_model' => $route->vehicle->model_number,
                    'vehicle_type' => $route->vehicle->type,
                ];
            }
            return null;
        })->filter();

        return response()->json($results);
    }
}
