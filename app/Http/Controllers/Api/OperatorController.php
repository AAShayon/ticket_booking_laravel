<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *      name="Operators",
 *      description="API Endpoints for Bus Operator Management (Admin Only)"
 * )
 *
 * @OA\Schema(
 *     schema="Operator",
 *     title="Operator",
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="name", type="string", example="Green Line Paribahan"),
 *     @OA\Property(property="contact_email", type="string", format="email", example="contact@greenline.com"),
 *     @OA\Property(property="contact_phone", type="string", example="+8801XXXXXXXXX"),
 *     @OA\Property(property="admin_commission_percentage", type="number", format="float", example=10.00),
 *     @OA\Property(property="user_id", type="integer", format="int64", example=1, description="ID of the user who owns/manages this operator"),
 *     @OA\Property(property="nid", type="string", example="1234567890"),
 *     @OA\Property(property="address", type="string", example="123 Main St, City"),
 *     @OA\Property(property="transport_business_license", type="string", example="TBL-12345"),
 *     @OA\Property(property="logo", type="string", nullable=true, example="uploads/logos/greenline.png"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-01T00:00:00.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-01T00:00:00.000000Z"),
 * )
 *
 * @OA\Schema(
 *     schema="OperatorList",
 *     title="Operator List",
 *     type="array",
 *     @OA\Items(ref="#/components/schemas/Operator")
 * )
 */
class OperatorController extends Controller
{
    // public function __construct()
    // {
    //     parent::__construct();
    //     $this->middleware('role:admin');
    // }

    /**
     * @OA\Get(
     *     path="/operators",
     *     tags={"Operators"},
     *     summary="Get all bus operators (Admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/OperatorList")
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
        $operators = Operator::all();
        return response()->json($operators);
    }

    /**
     * @OA\Get(
     *     path="/operators/public",
     *     tags={"Operators"},
     *     summary="Get all bus operators (Publicly accessible)",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/OperatorList")
     *     )
     * )
     */
    public function publicIndex()
    {
        $operators = Operator::all();
        return response()->json($operators);
    }

    /**
     * @OA\Post(
     *     path="/operators",
     *     tags={"Operators"},
     *     summary="Create a new bus operator (Admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Green Line Paribahan"),
     *             @OA\Property(property="contact_email", type="string", format="email", example="contact@greenline.com"),
     *             @OA\Property(property="contact_phone", type="string", example="+8801XXXXXXXXX"),
     *             @OA\Property(property="admin_commission_percentage", type="number", format="float", example=10.00),
     *             @OA\Property(property="nid", type="string", example="1234567890"),
     *             @OA\Property(property="address", type="string", example="123 Main St, City"),
     *             @OA\Property(property="transport_business_license", type="string", example="TBL-12345"),
     *             @OA\Property(property="logo", type="string", format="binary", description="Operator logo image"),
     *             @OA\Property(
     *                 property="vehicles",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="model_number", type="string", example="SR112233"),
     *                     @OA\Property(property="type", type="string", enum={"AC", "non-AC", "sleeper", "hyundai", "scania"}, example="AC"),
     *                     @OA\Property(property="capacity", type="integer", example=40)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Operator created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Operator")
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
            'name' => 'required|string|unique:operators,name',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'admin_commission_percentage' => 'nullable|numeric|min:0|max:100',
            'user_id' => 'nullable|exists:users,id',
            'nid' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'transport_business_license' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'vehicles' => 'required|array|min:1',
            'vehicles.*.model_number' => 'required|string|max:255',
            'vehicles.*.type' => 'required|string|in:AC,non-AC,sleeper,hyundai,scania',
            'vehicles.*.capacity' => 'required|integer|min:1',
        ]);

        $data = $request->all();
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }
        if (!isset($data['user_id'])) {
            $data['user_id'] = Auth::id();
        }

        $operator = Operator::create($data);

        foreach ($data['vehicles'] as $vehicleData) {
            $operator->vehicles()->create($vehicleData);
        }

        return response()->json($operator->load('vehicles'), 201);
    }

    /**
     * @OA\Get(
     *     path="/operators/{id}",
     *     tags={"Operators"},
     *     summary="Get a specific bus operator by ID (Admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the operator to retrieve"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Operator")
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
     *         description="Operator not found",
     *     )
     * )
     */
    public function show(Operator $operator)
    {
        return response()->json($operator);
    }

    /**
     * @OA\Put(
     *     path="/operators/{id}",
     *     tags={"Operators"},
     *     summary="Update a specific bus operator by ID (Admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the operator to update"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Green Line Paribahan Updated"),
     *             @OA\Property(property="admin_commission_percentage", type="number", format="float", example=12.50),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="nid", type="string", example="1234567890"),
     *             @OA\Property(property="address", type="string", example="123 Main St, City"),
     *             @OA\Property(property="transport_business_license", type="string", example="TBL-12345"),
     *             @OA\Property(property="logo", type="string", format="binary", description="Operator logo image"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operator updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Operator")
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
     *         description="Operator not found",
     *     )
     * )
     */
    public function update(Request $request, Operator $operator)
    {
        $request->validate([
            'name' => 'sometimes|required|string|unique:operators,name,' . $operator->id,
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'admin_commission_percentage' => 'nullable|numeric|min:0|max:100',
            'user_id' => 'nullable|exists:users,id',
            'nid' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'transport_business_license' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();
        if ($request->hasFile('logo')) {
            if ($operator->logo) {
                Storage::disk('public')->delete($operator->logo);
            }
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $operator->update($data);

        return response()->json($operator);
    }

    /**
     * @OA\Delete(
     *     path="/operators/{id}",
     *     tags={"Operators"},
     *     summary="Delete a bus operator by ID (Admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the operator to delete"
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Operator deleted successfully",
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
     *         description="Operator not found",
     *     )
     * )
     */
    public function destroy(Operator $operator)
    {
        $operator->delete();
        return response()->json(null, 204);
    }
}
