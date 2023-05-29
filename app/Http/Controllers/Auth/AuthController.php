<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);
        $user = User::where('email', $credentials['email'])->first();
        if ($user && Hash::check($credentials['password'], $user->password)) {
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'message' => 'Logged in successfully',
                'token' => $token,
                'status' => 200,
            ]);
        }
        return response()->json([
            'message' => 'Invalid credentials',
            'status' => 401,
        ], 401);
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        $user =   User::create($data);
        if ($user) {
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'message' => 'User created successfully',
                'token' => $token,
                'status' => 200,
            ]);
        }
    }

    public function user()
    {
        $user = auth()->user();
        return response()->json([
            'message' => 'User retrieved successfully',
            'user' => $user,
            'status' => 200,
        ]);
    }
}