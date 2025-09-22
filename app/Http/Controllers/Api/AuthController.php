<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon; // For carbon
use App\Notifications\VerifyEmailWithCode; // Import your custom notification

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $verificationCode = random_int(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(60); // Code valid for 60 minutes

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student', // Assign the 'student' role
            'email_verification_code' => $verificationCode, // Save the code
            'email_verification_code_expires_at' => $expiresAt, // Save expiry
        ]);

        // Do NOT log the user in immediately for API if you want them to verify first.
        // If you want them logged in but unverified, then proceed.
        // For a mobile app, it's common to issue a token and then direct to verification screen.
        $token = $user->createToken('auth_token')->plainTextToken;

        // Send the email verification notification with the code
        $user->notify(new VerifyEmailWithCode($verificationCode));

        return response()->json([
            'message' => 'User registered successfully! A verification code has been sent to your email.',
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
            'needs_verification' => true, // Indicate that verification is pending
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials do not match our records.'],
            ]);
        }

        $user = $request->user();

        // If email is not verified, you might want to prevent access or redirect
        // For now, we allow login and let the frontend handle the redirect to verify-notice
        // based on `is_verified` status from `/user/verification-status`
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Logged in successfully!',
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
            'is_verified' => $user->hasVerifiedEmail(), // Send verification status
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully!']);
    }

    public function handleGoogleAuth(Request $request)
    {
        $request->validate([
            'google_id' => 'required|string',
            'email' => 'required|email',
            'name' => 'required|string',
            'avatar' => 'nullable|string',
        ]);

        try {
            // Find user by Google ID or email
            $user = User::where('google_id', $request->google_id)
                        ->orWhere('email', $request->email)
                        ->first();

            if ($user) {
                // User exists - link Google account if not already linked
                if (empty($user->google_id)) {
                    $user->google_id = $request->google_id;
                    $user->save();
                }
                
                // Mark email as verified if from Google
                if (is_null($user->email_verified_at)) {
                    $user->email_verified_at = now();
                    $user->save();
                }
            } else {
                // Create new user
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'google_id' => $request->google_id,
                    'password' => Hash::make(Str::random(24)), // Random password
                    'role' => 'student', // Default role for mobile users
                    'email_verified_at' => now(), // Google emails are verified
                    'profile_image' => $request->avatar, // Save Google avatar if provided
                ]);
            }

            // Create token for mobile app
            $token = $user->createToken('google_auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Google authentication successful!',
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
                'is_verified' => true,
                'is_new_user' => !$user->wasRecentlyCreated,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Google authentication failed.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}