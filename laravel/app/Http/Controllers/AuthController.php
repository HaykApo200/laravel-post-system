<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(): void
    {
        echo 'Hello World!';
        //return response()->json(['message' => 'Hello World!']);
    }
}
