<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    public function getToken(Request $request): JsonResponse
    {
        return response()->json([
            'email' => $request->email,
            'token' => $request->token,
        ], 200);
    }
}
