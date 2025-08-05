<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OtpVerification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *      name="Forgot Password",
 *      description="API Endpoints for Forgot Password functionality"
 * )
 */
class ForgotPasswordController extends Controller
{
    /**
     * @OA\Post(
     *     path="/forgot-password/request-otp",
     *     tags={"Forgot Password"},
     *     summary="Request OTP for password reset",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"identifier"},
     *             @OA\Property(property="identifier", type="string", example="user@example.com or +8801XXXXXXXXX"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OTP sent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="OTP sent to your identifier."),
     *             @OA\Property(property="otp", type="string", example="123456", description="For testing purposes only. In production, this would be sent via email/SMS."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User not found with this identifier."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     */
    public function requestOtp(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
        ]);

        $identifier = $request->identifier;

        // Determine if identifier is email or phone number
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $identifier)->first();
        } else {
            $user = User::where('phone_number', $identifier)->first();
        }

        if (!$user) {
            return response()->json(['message' => 'User not found with this identifier.'], 404);
        }

        // Generate OTP
        $otp = Str::random(6); // You might want to use a numeric OTP generator

        // Store OTP
        OtpVerification::updateOrCreate(
            ['identifier' => $identifier],
            [
                'otp' => $otp,
                'expires_at' => Carbon::now()->addMinutes(10), // OTP valid for 10 minutes
            ]
        );

        // In a real application, send OTP via email/SMS here
        // For now, we'll return it in the response for testing
        return response()->json([
            'message' => 'OTP sent to your identifier.',
            'otp' => $otp, // REMOVE IN PRODUCTION
        ]);
    }

    /**
     * @OA\Post(
     *     path="/forgot-password/verify-otp",
     *     tags={"Forgot Password"},
     *     summary="Verify OTP and get password reset token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"identifier","otp"},
     *             @OA\Property(property="identifier", type="string", example="user@example.com or +8801XXXXXXXXX"),
     *             @OA\Property(property="otp", type="string", example="123456"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OTP verified successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="OTP verified. You can now reset your password."),
     *             @OA\Property(property="reset_token", type="string", example="some_long_random_string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid or expired OTP",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid or expired OTP."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
            'otp' => 'required|string|size:6',
        ]);

        $otpRecord = OtpVerification::where('identifier', $request->identifier)
            ->where('otp', $request->otp)
            ->first();

        if (!$otpRecord || $otpRecord->expires_at->isPast()) {
            return response()->json(['message' => 'Invalid or expired OTP.'], 400);
        }

        // OTP is valid, generate a reset token
        $resetToken = Str::random(60);
        $otpRecord->delete(); // Invalidate OTP after use

        // Store the reset token with the user (or in a separate password_resets table)
        // For simplicity, we'll temporarily store it on the user model for this example
        // In a real app, consider a dedicated password_resets table with token and expiry
        $user = User::where('email', $request->identifier)
                    ->orWhere('phone_number', $request->identifier)
                    ->first();

        if ($user) {
            $user->remember_token = $resetToken; // Using remember_token for simplicity
            $user->save();
        }

        return response()->json([
            'message' => 'OTP verified. You can now reset your password.',
            'reset_token' => $resetToken,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/forgot-password/reset",
     *     tags={"Forgot Password"},
     *     summary="Reset user password",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"reset_token","password","password_confirmation"},
     *             @OA\Property(property="reset_token", type="string", example="some_long_random_string"),
     *             @OA\Property(property="password", type="string", format="password", example="new_secure_password"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="new_secure_password"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password reset successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Password has been reset successfully."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid or expired reset token",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid or expired reset token."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     */
    public function reset(Request $request)
    {
        $request->validate([
            'reset_token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('remember_token', $request->reset_token)->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid or expired reset token.'], 400);
        }

        $user->password = Hash::make($request->password);
        $user->remember_token = null; // Invalidate the reset token
        $user->save();

        return response()->json(['message' => 'Password has been reset successfully.']);
    }
}