<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *      name="Vehicles",
 *      description="API Endpoints for Vehicle Management (Operator Only)"
 * )
 *
 * @OA\Schema(
 *     schema="Vehicle",
 *     title="Vehicle",
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="operator_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="model_number", type="string", example="Scania K360"),
 *     @OA\Property(property="type", type="string", example="AC"),
 *     @OA\Property(property="capacity", type="integer", example=40),
 *     @OA\Property(property="image", type="string", nullable=true, example="uploads/vehicles/bus.png"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-01T00:00:00.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-01T00:00:00.000000Z"),
 * )
 *
 * @OA\Schema(
 *     schema="VehicleList",
 *     title="Vehicle List",
 *     type="array",
 *     @OA\Items(ref="#/components/schemas/Vehicle")
 * )
 */
class VehicleController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:sanctum');
    //     $this->middleware('role:operator');
    // }

    /**
     * @OA\Get(
     *     path="/vehicles",
     *     tags={"Vehicles"},
     *     summary="Get all vehicles for the authenticated operator",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/VehicleList")
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
        $user = Auth::user();
        $query = Vehicle::query();

        if ($user->role === 'operator') {
            $operator = $user->operator;
            if (!$operator) {
                return response()->json(['message' => 'Operator not found for this user.'], 404);
            }
            $query->where('operator_id', $operator->id);
        } elseif ($user->role === 'admin') {
            if ($request->has('operator_id')) {
                $query->where('operator_id', $request->input('operator_id'));
            }
        }

        $limit = $request->input('limit', 15);
        $vehicles = $query->paginate($limit);

        return response()->json($vehicles);
    }

    /**
     * @OA\Get(
     *     path="/admin/vehicles",
     *     tags={"Vehicles"},
     *     summary="Get all vehicles (Admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/VehicleList")
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
    public function adminIndex()
    {
        $this->authorize('viewAny', Vehicle::class);
        $vehicles = Vehicle::all();
        return response()->json($vehicles);
    }

    /**
     * @OA\Post(
     *     path="/vehicles",
     *     tags={"Vehicles"},
     *     summary="Create a new vehicle for the authenticated operator",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"model_number","type"},
     *             @OA\Property(property="model_number", type="string", example="Scania K360"),
     *             @OA\Property(property="type", type="string", enum={"AC", "Sleeper", "Non-AC", "hyundai", "scania"}, example="AC"),
     *             @OA\Property(property="capacity", type="integer", example=40),
     *             @OA\Property(property="image", type="string", format="binary", description="Vehicle image file"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Vehicle created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Vehicle")
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
            'model_number' => 'required|string|max:255',
            'type' => 'required|string|in:AC,Sleeper,Non-AC,hyundai,scania',
            'capacity' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = Auth::user();
        $operatorId = $request->input('operator_id');

        if ($user->role === 'operator') {
            $operator = $user->operator;
            if (!$operator || $operator->id != $operatorId) {
                return response()->json(['message' => 'You are not authorized to add a vehicle to this operator.'], 403);
            }
        }

        $data = $request->all();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('vehicles', 'public');
        }

        $vehicle = Vehicle::create($data);

        return response()->json(['message' => 'Vehicle created successfully', 'data' => $vehicle], 201);
    }

    /**
     * @OA\Get(
     *     path="/vehicles/{id}",
     *     tags={"Vehicles"},
     *     summary="Get a specific vehicle by ID for the authenticated operator",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the vehicle to retrieve"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Vehicle")
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
     *         description="Vehicle not found",
     *     )
     * )
     */
    public function show(Vehicle $vehicle)
    {
        $operator = Auth::user()->operator;
        if (!$operator || $vehicle->operator_id !== $operator->id) {
            return response()->json(['message' => 'Unauthorized to view this vehicle.'], 403);
        }
        return response()->json($vehicle);
    }

    /**
     * @OA\Put(
     *     path="/vehicles/{id}",
     *     tags={"Vehicles"},
     *     summary="Update a specific vehicle by ID for the authenticated operator",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the vehicle to update"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="model_number", type="string", example="Volvo 9700"),
     *             @OA\Property(property="type", type="string", enum={"AC", "Sleeper", "Non-AC", "hyundai", "scania"}, example="Sleeper"),
     *             @OA\Property(property="capacity", type="integer", example=28),
     *             @OA\Property(property="image", type="string", format="binary", description="Vehicle image file"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vehicle updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Vehicle")
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
     *         description="Vehicle not found",
     *     )
     * )
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'model_number' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|string|in:AC,Sleeper,Non-AC,hyundai,scania',
            'capacity' => 'sometimes|required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = Auth::user();

        if ($user->role === 'operator') {
            $operator = $user->operator;
            if (!$operator || $vehicle->operator_id != $operator->id) {
                return response()->json(['message' => 'You are not authorized to update this vehicle.'], 403);
            }
        }

        $data = $request->all();
        if ($request->hasFile('image')) {
            if ($vehicle->image) {
                Storage::disk('public')->delete($vehicle->image);
            }
            $data['image'] = $request->file('image')->store('vehicles', 'public');
        }

        $vehicle->update($data);

        return response()->json(['message' => 'Vehicle updated successfully', 'data' => $vehicle]);
    }

    /**
     * @OA\Delete(
     *     path="/vehicles/{id}",
     *     tags={"Vehicles"},
     *     summary="Delete a specific vehicle by ID for the authenticated operator",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the vehicle to delete"
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Vehicle deleted successfully",
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
     *         description="Vehicle not found",
     *     )
     * )
     */
    public function destroy(Vehicle $vehicle)
    {
        $operator = Auth::user()->operator;
        if (!$operator || $vehicle->operator_id !== $operator->id) {
            return response()->json(['message' => 'Unauthorized to delete this vehicle.'], 403);
        }

        if ($vehicle->image) {
            Storage::disk('public')->delete($vehicle->image);
        }
        $vehicle->delete();
        return response()->json(null, 204);
    }
}