<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Actions\RegisterUserAction;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request, RegisterUserAction $registerAction): JsonResponse
    {

    $user = $registerAction->execute($request->validated());

        return response()->json([
            'message' => 'User registered successfully with 100 welcome points!',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'points' => $user->points_balance
            ]
        ], 201);
    }
}