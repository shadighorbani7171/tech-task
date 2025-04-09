<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auth Controller
 * 
 * This controller handles user authentication using JWT.
 * It provides login, logout, refresh token, and user profile methods.
 */
class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     */
    public function __construct()
    {
        // No middleware here - we'll set it in the routes file
    }

    /**
     * Log in and get a JWT token
     * 
     * This method authenticates a user and returns a JWT token.
     * It requires email and password.
     * 
     * @param Request $request The HTTP request with login credentials
     * @return JsonResponse JSON with token or error message
     */
    public function login(Request $request): JsonResponse
    {
        // Validate login credentials
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        // Attempt to authenticate and get token
        if (!$token = Auth::attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid login credentials'
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Return the token in response
        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated user's profile
     * 
     * @return JsonResponse JSON with user data
     */
    public function me(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => Auth::user()
        ]);
    }

    /**
     * Log out the user (invalidate the token)
     * 
     * @return JsonResponse JSON with logout confirmation
     */
    public function logout(): JsonResponse
    {
        Auth::logout();

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Refresh a token
     * 
     * This method creates a new token by refreshing the old one.
     * 
     * @return JsonResponse JSON with new token
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(Auth::refresh());
    }

    /**
     * Format the token response in a consistent way
     * 
     * @param string $token JWT token
     * @return JsonResponse Formatted JSON response with token details
     */
    protected function respondWithToken(string $token): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ]);
    }
} 