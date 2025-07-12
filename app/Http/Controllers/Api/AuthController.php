<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; // Still useful for attempt and user retrieval

class AuthController extends Controller
{
    /**
     * Handle user registration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        try {
            // Validate incoming data
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            // Create the user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'instructor', // Default role for new registrations
            ]);

            // You might want to automatically log in the user and return a token here too
            // $token = $user->createToken('auth_token')->plainTextToken;
            // return response()->json(['message' => 'User registered successfully!', 'access_token' => $token, 'token_type' => 'Bearer'], 201);

            return response()->json(['message' => 'User registered successfully!'], 201);

        } catch (ValidationException $e) {
            // Handle validation errors specifically
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Handle any other unexpected errors
            Log::error('Registration error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['message' => 'Registration failed due to a server error.'], 500);
        }
    }

    /**
     * Handle user login and issue an API token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // Validate the input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to authenticate the user
        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials do not match our records.'],
            ]);
        }

        $user = Auth::user();

        // Create a new API token for the user
        // You can specify abilities (permissions) for the token if needed
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return the token to the client
        return response()->json([
            'message' => 'Login successful!',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Log the user out by revoking their current API token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // Get the currently authenticated user's token and delete it
        // This will only work if the request is authenticated with Sanctum
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully logged out.'], 200);
    }

    /**
     * Get the authenticated user's details.
     * This route will be protected by Sanctum middleware.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
