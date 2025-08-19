<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *      name="Profile",
 *      description="API Endpoints for User Profile Management"
 * )
 */
class ProfileController extends Controller
{
    /**
     * @OA\Post(
     *     path="/profile",
     *     tags={"Profile"},
     *     summary="Update authenticated user's profile",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string", example="Jane Doe"),
     *                 @OA\Property(property="email", type="string", format="email", example="jane.doe@example.com"),
     *                 @OA\Property(property="phone_number", type="string", nullable=true, example="+8801XXXXXXXXX"),
     *                 @OA\Property(property="password", type="string", format="password", example="new_password"),
     *                 @OA\Property(property="password_confirmation", type="string", format="password", example="new_password"),
     *                 @OA\Property(property="profile_image", type="string", format="binary", description="Profile image file"),
     *                 @OA\Property(property="last_login_at", type="string", format="date-time", nullable=true, example="2025-01-01T00:00:00.000000Z"),
     *                 @OA\Property(property="total_bookings", type="integer", example=5, readOnly=true),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Profile updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Profile updated successfully"),
     *             @OA\Property(property="user", ref="#/components/schemas/AdminUser"),
     *         )
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
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:8|confirmed',
            'profile_image' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'phone_number' => 'nullable|string|max:20',
        ]);

        $data = $request->only(['name', 'email', 'phone_number']);

        if ($request->has('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $data['profile_image'] = $request->file('profile_image')->store('uploads', 'public');
        }

        $user->update($data);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user,
        ]);
    }
}
