<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Pnr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *      name="Admin",
 *      description="API Endpoints for Admin Management"
 * )
 *
 * @OA\Schema(
 *     schema="AdminUser",
 *     title="Admin User",
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="name", type="string", example="Admin User"),
 *     @OA\Property(property="email", type="string", format="email", example="admin@example.com"),
 *     @OA\Property(property="phone_number", type="string", nullable=true, example="+8801XXXXXXXXX"),
 *     @OA\Property(property="profile_image", type="string", nullable=true, example="uploads/profile.jpg"),
 *     @OA\Property(property="role", type="string", example="admin"),
 *     @OA\Property(property="last_login_at", type="string", format="date-time", nullable=true, example="2025-01-01T00:00:00.000000Z"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-01T00:00:00.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-01T00:00:00.000000Z"),
 * )
 *
 * @OA\Schema(
 *     schema="AdminUserList",
 *     title="Admin User List",
 *     type="array",
 *     @OA\Items(ref="#/components/schemas/AdminUser")
 * )
 *
 * @OA\Schema(
 *     schema="AdminBookingList",
 *     title="Admin Booking List",
 *     type="array",
 *     @OA\Items(ref="#/components/schemas/Booking")
 * )
 *
 * @OA\Schema(
 *     schema="Pnr",
 *     title="PNR",
 *     description="PNR (Passenger Name Record) details",
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="booking_id", type="integer", format="int64", example=1),
 *     @OA\Property(property="pnr_number", type="string", example="ABCDEF"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-01T00:00:00.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-01T00:00:00.000000Z"),
 * )
 */
class AdminController extends Controller
{
    // User Management
    /**
     * @OA\Get(
     *     path="/admin/users",
     *     tags={"Admin"},
     *     summary="Get all users (Admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AdminUser")),
     *             @OA\Property(property="first_page_url", type="string", example="http://localhost:8000/api/admin/users?page=1"),
     *             @OA\Property(property="from", type="integer", example=1),
     *             @OA\Property(property="last_page", type="integer", example=1),
     *             @OA\Property(property="last_page_url", type="string", example="http://localhost:8000/api/admin/users?page=1"),
     *             @OA\Property(property="next_page_url", type="string", example="http://localhost:8000/api/admin/users?page=2"),
     *             @OA\Property(property="path", type="string", example="http://localhost:8000/api/admin/users"),
     *             @OA\Property(property="per_page", type="integer", example=15),
     *             @OA\Property(property="prev_page_url", type="string", example=null),
     *             @OA\Property(property="to", type="integer", example=15),
     *             @OA\Property(property="total", type="integer", example=15)
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=15)
     *     )
     * )
     */
    public function getUsers(Request $request)
    {
        $perPage = $request->query('limit', 15);
        $users = User::paginate($perPage);
        return response()->json($users);
    }

    /**
     * @OA\Get(
     *     path="/admin/users/{user}",
     *     tags={"Admin"},
     *     summary="Get a specific user by ID (Admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the user to retrieve"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/AdminUser")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *     )
     * )
     */
    public function showUser(User $user)
    {
        return response()->json($user);
    }

    /**
     * @OA\Post(
     *     path="/admin/users",
     *     tags={"Admin"},
     *     summary="Create a new user (Admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","role"},
     *             @OA\Property(property="name", type="string", example="New Admin"),
     *             @OA\Property(property="email", type="string", format="email", example="newadmin@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password"),
     *             @OA\Property(property="role", type="string", example="admin", enum={"user", "admin"}),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/AdminUser")
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
    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:user,admin,operator',
            'phone_number' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone_number' => $request->phone_number,
        ]);

        return response()->json($user, 201);
    }

    /**
     * @OA\Put(
     *     path="/admin/users/{user}",
     *     tags={"Admin"},
     *     summary="Update an existing user (Admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the user to update"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Updated Name"),
     *             @OA\Property(property="email", type="string", format="email", example="updated@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="new_password"),
     *             @OA\Property(property="role", type="string", example="admin", enum={"user", "admin"}),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/AdminUser")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     */
    public function updateUser(Request $request, User $user)
    {
        // The 'sometimes' rule ensures we only validate fields that are actually present.
        // The `validate` method returns an array of ONLY the data that passed.
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|nullable|string|min:8',
            'role' => 'sometimes|required|string|in:user,admin,operator',
            'phone_number' => 'sometimes|nullable|string|max:20',
            'profile_image' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // If the validated data includes a password, hash it.
        if (isset($validatedData['password'])) {
            // Handle case where password might be an empty string but we don't want to update it
            if (empty($validatedData['password'])) {
                unset($validatedData['password']);
            } else {
                $validatedData['password'] = Hash::make($validatedData['password']);
            }
        }

        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $validatedData['profile_image'] = $request->file('profile_image')->store('uploads', 'public');
        }

        // This is the most important part. We call update() with the validated data.
        // If Flutter sends only {'role': 'user'}, then $validatedData will be ['role' => 'user'],
        // and ONLY the role column will be updated in the database.
        $user->update($validatedData);

        // Return the full, updated user object to the app.
        return response()->json($user);
    }

    /**
     * @OA\Delete(
     *     path="/admin/users/{user}",
     *     tags={"Admin"},
     *     summary="Delete a user (Admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the user to delete"
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="User deleted successfully",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *     )
     * )
     */
    public function deleteUser(User $user)
    {
        $user->delete();
        return response()->json(null, 204);
    }

    // Booking Management
    /**
     * @OA\Get(
     *     path="/admin/bookings",
     *     tags={"Admin"},
     *     summary="Get all bookings (Admin only)",
     *     description="Retrieves a paginated list of all bookings in the system. Includes user_id and pnr_number directly in the booking object.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="from_station", type="string", example="Dhaka"),
     *                 @OA\Property(property="to_station", type="string", example="Chittagong"),
     *                 @OA\Property(property="journey_date", type="string", format="date", example="2025-12-25"),
     *                 @OA\Property(property="seat_type", type="string", example="Economy"),
     *                 @OA\Property(property="number_of_seats", type="integer", example=2),
     *                 @OA\Property(property="seat_number", type="array", @OA\Items(type="string"), example={"A1", "A2"}),
     *                 @OA\Property(property="total_fare", type="number", format="float", example=100.00),
     *                 @OA\Property(property="status", type="string", example="confirmed"),
     *                 @OA\Property(property="payment_method", type="string", example="cash"),
     *                 @OA\Property(property="payment_name", type="string", example="Cash"),
     *                 @OA\Property(property="transaction_id", type="string", nullable=true, example="txn_123"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-01T00:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-01T00:00:00Z"),
     *                 @OA\Property(property="route_id", type="integer", example=1),
     *                 @OA\Property(property="pnr_number", type="string", nullable=true, example="ABCDEF", description="PNR number if generated")
     *             )),
     *             @OA\Property(property="first_page_url", type="string", example="http://localhost:8000/api/admin/bookings?page=1"),
     *             @OA\Property(property="from", type="integer", example=1),
     *             @OA\Property(property="last_page", type="integer", example=1),
     *             @OA\Property(property="last_page_url", type="string", example="http://localhost:8000/api/admin/bookings?page=1"),
     *             @OA\Property(property="next_page_url", type="string", nullable=true, example=null),
     *             @OA\Property(property="path", type="string", example="http://localhost:8000/api/admin/bookings"),
     *             @OA\Property(property="per_page", type="integer", example=15),
     *             @OA\Property(property="prev_page_url", type="string", nullable=true, example=null),
     *             @OA\Property(property="to", type="integer", example=1),
     *             @OA\Property(property="total", type="integer", example=1)
     *         )
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
    public function getAllBookings(Request $request)
    {
        $limit = $request->input('limit', 15);
        $bookings = Booking::with('pnr')->paginate($limit);

        // Map the bookings to include pnr_number directly and remove the nested pnr object
        $bookings->getCollection()->transform(function ($booking) {
            $bookingArray = $booking->toArray();
            if ($booking->pnr) {
                $bookingArray['pnr_number'] = $booking->pnr->pnr_number;
            } else {
                $bookingArray['pnr_number'] = null; // Or an empty string, depending on preference
            }
            unset($bookingArray['pnr']); // Remove the nested pnr object
            return $bookingArray;
        });

        return response()->json($bookings);
    }

    /**
     * @OA\Put(
     *     path="/admin/bookings/{booking}/status",
     *     tags={"Admin"},
     *     summary="Update booking status (Admin only)",
     *     description="Updates the status of a booking. If a pending cash booking is confirmed, a PNR will be generated.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="booking",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the booking to update"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"status"},
     *             @OA\Property(property="status", type="string", example="confirmed", enum={"pending", "confirmed", "cancelled"}),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Booking status updated successfully",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/Booking",
     *             @OA\Property(property="pnr", ref="#/components/schemas/Pnr", description="Associated PNR details if generated")
     *         )
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
    public function updateBookingStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|string|in:pending,confirmed,cancelled',
        ]);

        $oldStatus = $booking->status;
        $newStatus = $request->status;

        // If a pending cash booking is being confirmed, generate PNR
        if ($oldStatus === 'pending' && $newStatus === 'confirmed' && $booking->payment_method === 'cash') {
            $pnrNumber = $this->generatePnrNumber();
            $booking->pnr()->create(['pnr_number' => $pnrNumber]);
        }

        $booking->update(['status' => $newStatus]);

        // Eager load the PNR relationship before returning
        $booking->load('pnr');

        return response()->json($booking);
    }

    /**
     * Generate a unique PNR number.
     *
     * @return string
     */
    private function generatePnrNumber()
    {
        do {
            $pnr = strtoupper(Str::random(6)); // Generate a 6-character alphanumeric string
        } while (Pnr::where('pnr_number', $pnr)->exists());

        return $pnr;
    }

    /**
     * @OA\Delete(
     *     path="/admin/bookings/{booking}",
     *     tags={"Admin"},
     *     summary="Delete a booking (Admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="booking",
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
    public function deleteBooking(Booking $booking)
    {
        $booking->delete();
        return response()->json(null, 204);
    }

    /**
     * @OA\Get(
     *     path="/api/admin/daily-summery",
     *     tags={"Admin"},
     *     summary="Get a daily summary for the admin dashboard.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="metrics",
     *                 type="object",
     *                 @OA\Property(property="new_users_today", type="integer", example=42),
     *                 @OA\Property(property="pending_operator_requests", type="integer", example=5),
     *                 @OA\Property(property="total_bookings", type="integer", example=128),
     *                 @OA\Property(property="todays_revenue", type="number", format="float", example=3420.50)
     *             ),
     *             @OA\Property(
     *                 property="recent_activities",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="type", type="string", example="new_booking"),
     *                     @OA\Property(property="text", type="string", example="New booking: PNR784523"),
     *                     @OA\Property(property="timestamp", type="string", format="date-time", example="2025-08-16T10:32:15.000000Z")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     )
     * )
     */
    public function dailySummary()
    {
        $today = Carbon::today();

        // Metrics
        $newUsersToday = User::whereDate('created_at', $today)->count();
        $pendingOperatorRequests = \App\Models\OperatorRequest::where('status', 'pending')->count();
        $totalBookings = Booking::whereDate('created_at', $today)->count();
        $todaysRevenue = Booking::whereDate('created_at', $today)->sum('total_fare');

        // Recent Activities
        $bookings = Booking::with('pnr')->latest()->limit(5)->get()->map(function ($item) {
            return [
                'type' => 'new_booking',
                'text' => 'New booking: ' . ($item->pnr ? $item->pnr->pnr_number : 'N/A'),
                'timestamp' => $item->created_at,
            ];
        });

        $users = User::latest()->limit(5)->get()->map(function ($item) {
            return [
                'type' => 'new_user',
                'text' => 'New user: ' . $item->email,
                'timestamp' => $item->created_at,
            ];
        });

        $operators = \App\Models\Operator::latest()->limit(5)->get()->map(function ($item) {
            return [
                'type' => 'new_operator',
                'text' => 'New operator: ' . $item->name,
                'timestamp' => $item->created_at,
            ];
        });

        $allActivities = $bookings->toBase()->merge($users)->merge($operators);
        $recentActivities = $allActivities->sortByDesc('timestamp')->take(10)->values();

        return response()->json([
            'metrics' => [
                'new_users_today' => $newUsersToday,
                'pending_operator_requests' => $pendingOperatorRequests,
                'total_bookings' => $totalBookings,
                'todays_revenue' => (float) $todaysRevenue,
            ],
            'recent_activities' => $recentActivities,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/admin/recent-activities",
     *     tags={"Admin"},
     *     summary="Get all recent activities with pagination.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Number of items per page (5, 10, 15)",
     *         required=false,
     *         @OA\Schema(type="integer", default=15, enum={5, 10, 15})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="type", type="string", example="new_booking"),
     *                     @OA\Property(property="text", type="string", example="New booking: PNR784523"),
     *                     @OA\Property(property="timestamp", type="string", format="date-time", example="2025-08-16T10:32:15.000000Z")
     *                 )
     *             ),
     *             @OA\Property(property="first_page_url", type="string", example="http://localhost/api/admin/recent-activities?page=1"),
     *             @OA\Property(property="from", type="integer", example=1),
     *             @OA\Property(property="last_page", type="integer", example=1),
     *             @OA\Property(property="last_page_url", type="string", example="http://localhost/api/admin/recent-activities?page=1"),
     *             @OA\Property(property="next_page_url", type="string", example=null),
     *             @OA\Property(property="path", type="string", example="http://localhost/api/admin/recent-activities"),
     *             @OA\Property(property="per_page", type="integer", example=15),
     *             @OA\Property(property="prev_page_url", type="string", example=null),
     *             @OA\Property(property="to", type="integer", example=10),
     *             @OA\Property(property="total", type="integer", example=10)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     )
     * )
     */
    public function recentActivities(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('limit', 15);

        $bookings = Booking::with('pnr')->latest()->get()->map(function ($item) {
            return [
                'type' => 'new_booking',
                'text' => 'New booking: ' . ($item->pnr ? $item->pnr->pnr_number : 'N/A'),
                'timestamp' => $item->created_at,
            ];
        });

        $users = User::latest()->get()->map(function ($item) {
            return [
                'type' => 'new_user',
                'text' => 'New user: ' . $item->email,
                'timestamp' => $item->created_at,
            ];
        });

        $operators = \App\Models\Operator::latest()->get()->map(function ($item) {
            return [
                'type' => 'new_operator',
                'text' => 'New operator: ' . $item->name,
                'timestamp' => $item->created_at,
            ];
        });

        $allActivities = $bookings->toBase()->merge($users)->merge($operators)->sortByDesc('timestamp');

        $paginatedActivities = new \Illuminate\Pagination\LengthAwarePaginator(
            $allActivities->forPage($page, $perPage)->values(),
            $allActivities->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return response()->json($paginatedActivities);
    }
}
