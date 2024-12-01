<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserProfileInformationRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Remove the specified resource from users.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Display a listing of the users.
     */
    public function index()
    {
        dd('oio');
    }

    /**
     * Display the specified user.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Store a newly created resource in user.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in user.
     */
    public function update(
        UpdateUserProfileInformationRequest $request,
        UserRepository $repository
    ): JsonResponse {
        if ($repository->update($request->validated(), $request->user())) {
            return response()->json([
                'message' => 'Profile information successfully updated.',
            ], 200);
        }

        return response()->json([
            'message' => 'Error while processing the request.',
        ], 500);
    }
}
