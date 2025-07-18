<?php

namespace App\Http\Controllers\Auth;

use App\Constants\User\UserStatus;
use App\Constants\User\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Models\User;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegistrationRequest $request): JsonResponse
    {
        $data = $request->validated();

        $data['status'] = UserStatus::ACTIVE->value;

        $data['type'] = UserType::USER->value;

        User::create($data);

        return response()->json(
            [
                'message' => 'User created successfully.',
                'status'  => 201
            ]
        );
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $loginCredentials = $request->validated();

        if (!auth()->attempt($loginCredentials)) {
            return response()->json(
                [
                    'message' => 'Invalid credentials.',
                    'status'  => 401
                ]
            );
        }

        $token = Auth::user()->createToken('authToken')->plainTextToken;

        return response()->json(
            [
                'message'      => 'Login successful.',
                'access_token' => $token,
                'status'       => 200
            ]
        );
    }
}
