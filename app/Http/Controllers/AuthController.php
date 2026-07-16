<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\CreateAccountRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request): object
    {
        $credentials = $request->only('email', 'password');

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => new UserResource(auth('api')->user()->load('entity')),
        ]);
    }

    public function logout(): object
    {
        Auth::logout();

        return response()->json(['message' => 'Successfully logged out'], self::HTTP_STATUS_CODES['success']);
    }

    public function register(CreateAccountRequest $request): object
    {
        try {

            $user = User::create($request->validated());

            return response()->json([
                'article' => new UserResource($user),
            ], self::HTTP_STATUS_CODES['created']);
        } catch (\Exception $e) {
            Log::critical('Error creating new user'.$e->getMessage());

            return response()->json([
                'message' => 'Failed to create new user',
            ], self::HTTP_STATUS_CODES['server_error']);
        }
    }
}
