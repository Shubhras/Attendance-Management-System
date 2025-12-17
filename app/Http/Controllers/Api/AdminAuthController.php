<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
            'device_name' => 'required'
        ]);

        // Find user by email
        $user = User::where('email', $validated['email'])->first();

        // Validate credentials
        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        // Allow only admin user
        if ($user->role !== 'admin') {
            return response()->json([
                'status' => false,
                'message' => 'Access denied. Only admin can login.'
            ], 403);
        }

        // Create Sanctum Token
        $token = $user->createToken($validated['device_name'])->plainTextToken;

        return response()->json([
            'status' => 200,
            'message' => 'Admin login successfully',
            'token' => $token,
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken();

        if ($token) {
            $token->delete();
        }

        return response()->json([
            'status' => 200,
            'message' => 'Logout successfully'
        ]);
    }
}
