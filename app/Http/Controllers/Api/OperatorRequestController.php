<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OperatorRequest;
use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *      name="Operator Requests",
 *      description="API Endpoints for Managing Bus Operator Requests"
 * )
 *
 * @OA\Schema(
 *     schema="OperatorRequest",
 *     title="Operator Request",
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="user_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="operator_name", type="string", example="New Bus Operator"),
 *     @OA\Property(property="route_details", type="string", example="Dhaka to Chittagong"),
 *     @OA\Property(property="fare_details", type="string", example="BDT 1200"),
 *     @OA\Property(property="admin_commission_percentage", type="number", format="float", example=10.00),
 *     @OA\Property(property="status", type="string", example="pending", enum={"pending", "approved", "rejected"}),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-01T00:00:00.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-01T00:00:00.000000Z"),
 * )
 *
 * @OA\Schema(
 *     schema="OperatorRequestList",
 *     title="Operator Request List",
 *     type="array",
 *     @OA\Items(ref="#/components/schemas/OperatorRequest")
 * )
 */
class OperatorRequestController extends Controller
{
    /**
     * @OA\Post(
     *     path="/operator-requests",
     *     tags={"Operator Requests"},
     *     summary="Submit a new operator request",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"operator_name","route_details","fare_details","admin_commission_percentage"},
     *             @OA\Property(property="operator_name", type="string", example="New Bus Operator"),
     *             @OA\Property(property="route_details", type="string", example="Dhaka to Sylhet"),
     *             @OA\Property(property="fare_details", type="string", example="BDT 1000"),
     *             @OA\Property(property="admin_commission_percentage", type="number", format="float", example=10.00),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Operator request submitted successfully",
     *         @OA\JsonContent(ref="#/components/schemas/OperatorRequest")
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
            'operator_name' => 'required|string|max:255|unique:operator_requests,operator_name,NULL,id,status,pending',
            'route_details' => 'required|string',
            'fare_details' => 'required|string',
            'admin_commission_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $operatorRequest = Auth::user()->operatorRequests()->create($request->all());

        return response()->json($operatorRequest, 201);
    }

    /**
     * @OA\Get(
     *     path="/operator-requests/my",
     *     tags={"Operator Requests"},
     *     summary="Get all operator requests for the authenticated user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/OperatorRequestList")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     )
     * )
     */
    public function userRequests()
    {
        $requests = Auth::user()->operatorRequests()->get();
        return response()->json($requests);
    }

    /**
     * @OA\Get(
     *     path="/admin/operator-requests",
     *     tags={"Operator Requests"},
     *     summary="Get all operator requests (Admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/OperatorRequestList")
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
        $this->authorize('viewAny', OperatorRequest::class);
        $requests = OperatorRequest::with('user')->get();
        return response()->json($requests);
    }

    /**
     * @OA\Get(
     *     path="/admin/operator-requests/{id}",
     *     tags={"Operator Requests"},
     *     summary="Get a specific operator request by ID (Admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the operator request to retrieve"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/OperatorRequest")
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
     *         description="Operator request not found",
     *     )
     * )
     */
    public function show(OperatorRequest $operatorRequest)
    {
        $this->authorize('view', $operatorRequest);
        return response()->json($operatorRequest);
    }

    /**
     * @OA\Post(
     *     path="/admin/operator-requests/{id}/approve",
     *     tags={"Operator Requests"},
     *     summary="Approve an operator request (Admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the operator request to approve"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operator request approved and operator created",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Operator request approved and operator created"),
     *             @OA\Property(property="operator", ref="#/components/schemas/Operator"),
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
     *         description="Operator request not found",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     */
    public function approve(OperatorRequest $operatorRequest)
    {
        $this->authorize('approve', $operatorRequest);

        if ($operatorRequest->status !== 'pending') {
            return response()->json(['message' => 'Request is not pending.'], 422);
        }

        $operator = Operator::create([
            'name' => $operatorRequest->operator_name,
            'contact_email' => null, // Assuming these are not provided in the request
            'contact_phone' => null,
            'admin_commission_percentage' => $operatorRequest->admin_commission_percentage,
            'user_id' => $operatorRequest->user_id,
        ]);

        $operatorRequest->status = 'approved';
        $operatorRequest->save();

        return response()->json([
            'message' => 'Operator request approved and operator created',
            'operator' => $operator,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/admin/operator-requests/{id}/reject",
     *     tags={"Operator Requests"},
     *     summary="Reject an operator request (Admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the operator request to reject"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operator request rejected",
     *         @OA\JsonContent(ref="#/components/schemas/OperatorRequest")
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
     *         description="Operator request not found",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     */
    public function reject(OperatorRequest $operatorRequest)
    {
        $this->authorize('reject', $operatorRequest);

        if ($operatorRequest->status !== 'pending') {
            return response()->json(['message' => 'Request is not pending.'], 422);
        }

        $operatorRequest->status = 'rejected';
        $operatorRequest->save();

        return response()->json([
            'message' => 'Operator request rejected',
            'operator_request' => $operatorRequest,
        ]);
    }
}
