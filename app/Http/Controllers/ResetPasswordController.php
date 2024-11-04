<?php

namespace App\Http\Controllers;

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
