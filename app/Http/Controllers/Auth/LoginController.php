<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['مشخصات وارد شده با اطلاعات ما همخوانی ندارد.'],
            ]);
        }

        $token = $user->createToken('nextjs-auth-token')->plainTextToken;

        return response()->json([
            'message' => 'خوش آمدید!',
            'token' => $token,
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'points' => $user->points_balance
            ]
        ]);
    }
}