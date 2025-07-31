<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Route;
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
    public function index()
    {
        $routes = Route::all();
        return response()->json($routes);
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
     *             required={"operator_id","origin","destination","fare","vehicle_number","time_of_day"},
     *             @OA\Property(property="operator_id", type="integer", example=1),
     *             @OA\Property(property="origin", type="string", example="Dhaka"),
     *             @OA\Property(property="destination", type="string", example="Chittagong"),
     *             @OA\Property(property="fare", type="number", format="float", example=1200.00),
     *             @OA\Property(property="estimated_travel_time", type="string", example="6 hours"),
     *             @OA\Property(property="vehicle_number", type="string", example="ABC-123"),
     *             @OA\Property(property="time_of_day", type="string", example="morning"),
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
            'origin' => [
                'required',
                'string',
                Rule::unique('routes')->where(function ($query) use ($request) {
                    return $query->where('operator_id', $request->operator_id)
                                 ->where('destination', $request->destination)
                                 ->where('estimated_travel_time', $request->estimated_travel_time)
                                 ->where('vehicle_number', $request->vehicle_number)
                                 ->where('time_of_day', $request->time_of_day);
                }),
            ],
            'destination' => 'required|string',
            'fare' => 'required|numeric|min:0',
            'estimated_travel_time' => 'nullable|string',
            'vehicle_number' => 'required|string|max:255',
            'time_of_day' => 'required|string|in:morning,day,evening,night,am,pm',
        ]);

        $route = Route::create($request->all());

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
            'origin' => 'sometimes|required|string',
            'destination' => 'sometimes|required|string',
            'fare' => 'sometimes|required|numeric|min:0',
            'estimated_travel_time' => 'nullable|string',
            'vehicle_number' => 'sometimes|required|string|max:255',
            'time_of_day' => 'sometimes|required|string|in:morning,day,evening,night,am,pm',
        ]);

        $route->update($request->all());

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
}
