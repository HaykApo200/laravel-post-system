<?php

namespace App\Http\Controllers\Auth;

use App\Constants\User\UserStatus;
use App\Constants\User\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function register(RegistrationRequest $request): JsonResponse
    {
        $data = $request->validated();

        $data['status'] = UserStatus::ACTIVE->value;

        $data['type'] = UserType::USER->value;

        User::create($data);

        return response()->json(['message' => 'User created successfully.', 'status' => 201]);
    }
}
