<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Actions\Order\CalculateCartValueAction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

class CartController extends Controller
{
    public function __construct(
        protected CalculateCartValueAction $calculateCartValueAction
    ) {}

  
    public function calculate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            $result = $this->calculateCartValueAction->execute(
                $request->user(),
                $validated['items']
            );

            return response()->json($result, 200);

        } catch (Exception $e) {
            Log::error("خطا در محاسبه ریل‌تایم سبد خرید کاربر #{$request->user()->id}: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'خطایی در محاسبه سبد خرید رخ داده است. لطفا مجددا تلاش کنید.'
            ], 500);
        }
    }
}