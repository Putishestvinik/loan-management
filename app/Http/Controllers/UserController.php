<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller {

    public function register(Request $request) {
        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8', // Make sure passwords match
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']), // Hash the password
        ]);

        // Return the user or a success message
        return response()->json(['message' => 'Registered successfully. Now you need to login', 'user' => $user], 200);
    }

    public function login(Request $request) {
        // Validate input
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to authenticate user
        if (Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
            // Authentication successful
            // Create a JWT token for further operations
            $token = JWTAuth::fromUser(Auth::user());

            return response()->json(['message' => 'Login successful', 'token' => $token], 200);
        }

        // Authentication failed
        return response()->json(['message' => 'Invalid credentials'], 401);
    }
}
