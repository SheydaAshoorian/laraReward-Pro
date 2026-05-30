<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Actions\RegisterUserAction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    // تزریق اتوماتیک اکشن توسط سرویس کانتینر لاراول
    public function __invoke(Request $request, RegisterUserAction $registerAction): JsonResponse
    {
        // ۱. اعتبارسنجی ورودی‌ها (در مراحل بعد با Form Request پیشرفته‌ترش می‌کنیم)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // ۲. اجرای اکشن بیزنس
        $user = $registerAction->execute($validated);

        // ۳. بازگرداندن پاسخ
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