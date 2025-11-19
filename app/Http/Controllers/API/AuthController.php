<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * API Login
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // Validation rules - reusing the same validation logic as web login
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Attempt to authenticate using the same credentials as web login
        $credentials = $request->only('email', 'password');
        
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = Auth::user();
        
        // Additional validation: Check if user has change_meter_contractor
        if (!$user->change_meter_contractor || !$user->change_meter_contractor->id) {
            // Logout the user since they're not authorized for API access
            Auth::logout();
            
            return response()->json([
                'success' => false,
                'message' => 'Access denied. Only authorized change meter contractors can access this API.'
            ], 403);
        }
        
        // Create Sanctum token for API authentication
        $token = $user->createToken('API Token')->plainTextToken;

        // Return user data with token (similar to web authentication response)
        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified_at' => $user->email_verified_at,
                    'roles' => $user->getRoleNames(), // Get user roles (reusing existing role system)
                    'permissions' => $user->getPermissionNames(), // Get user permissions
                    'crew_id' => $user->change_meter_contractor->id,
                    'crew_name' => $user->change_meter_contractor->first_name . ' ' . $user->change_meter_contractor->last_name,
                ],
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ], 200);
    }

    /**
     * API Logout
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // Revoke the current user's token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ], 200);
    }

    /**
     * Get authenticated user details
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request)
    {
        $user = $request->user();
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'roles' => $user->getRoleNames(),
                'permissions' => $user->getPermissionNames(),
                'employee' => $user->employee, // Include related employee data if exists
            ]
        ], 200);
    }

    /**
     * Refresh token (optional - for token rotation)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request)
    {
        $user = $request->user();
        
        // Revoke current token
        $request->user()->currentAccessToken()->delete();
        
        // Create new token
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Token refreshed successfully',
            'data' => [
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ], 200);
    }
}