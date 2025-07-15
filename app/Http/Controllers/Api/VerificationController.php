<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import Auth facade

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
     * Send a new email verification notification.
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

        $user->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification link sent!']);
    }
}