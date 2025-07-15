<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon; // Import Carbon

class VerificationController extends Controller
{
    /**
     * Get the email verification status of the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatus(Request $request)
    {
        // Ensure a user is authenticated via Sanctum before proceeding
        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['message' => 'Unauthenticated or token expired.'], 401);
        }

        $user = $request->user();

        return response()->json([
            'is_verified' => $user->hasVerifiedEmail(),
            'message' => $user->hasVerifiedEmail() ? 'Email is verified.' : 'Email is not verified.',
        ]);
    }

    /**
     * Send a new email verification notification (with code).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendVerificationEmail(Request $request)
    {
        // Ensure a user is authenticated via Sanctum
        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['message' => 'Unauthenticated or token expired.'], 401);
        }

        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.'], 400);
        }

        // Re-generate a new code and send it
        $verificationCode = random_int(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(60); // Set expiry
        $user->update([
            'email_verification_code' => $verificationCode,
            'email_verification_code_expires_at' => $expiresAt,
        ]);

        $user->notify(new \App\Notifications\VerifyEmailWithCode($verificationCode)); // Use full namespace

        return response()->json(['message' => 'A new verification code has been sent to your email address!']);
    }

    /**
     * Verify the email using a provided code.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyCode(Request $request)
    {
        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['message' => 'Unauthenticated or token expired.'], 401);
        }

        $request->validate([
            'verification_code' => ['required', 'numeric', 'digits:6'],
        ]);

        $user = $request->user();

        // Check if the provided code matches and is not expired
        if ($user->email_verification_code === $request->verification_code &&
            $user->email_verification_code_expires_at &&
            Carbon::now()->lt($user->email_verification_code_expires_at)) {

            $user->markEmailAsVerified();
            $user->forceFill([
                'email_verification_code' => null,
                'email_verification_code_expires_at' => null,
            ])->save();

            return response()->json(['message' => 'Email verified successfully!', 'is_verified' => true]);
        }

        throw ValidationException::withMessages([
            'verification_code' => ['The provided verification code is invalid or has expired.'],
        ]);
    }
}