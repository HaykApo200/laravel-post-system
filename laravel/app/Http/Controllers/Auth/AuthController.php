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
    /**
     * Register a new user.
     *
     * Handles validation, sets default user status and type,
     * and returns a JSON response.
     *
     * @param  RegistrationRequest  $request  The validated registration data
     * @return JsonResponse  JSON response indicating success or failure
     */
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

    /**
     * Authenticate a user and return an access token.
     *
     * Validates login credentials, attempts authentication, and
     * generates a personal access token using Laravel Sanctum.
     *
     * @param  LoginRequest  $request  The login credentials (email, password)
     * @return JsonResponse  JSON response with token or error message
     */
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
                'token_type'   => 'Bearer',
                'status'       => 200
            ]
        );
    }
}
