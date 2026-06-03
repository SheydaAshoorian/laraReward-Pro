<?php

namespace App\Http\Controllers\Reward;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; 

class ShowCouponController extends Controller
{
    use AuthorizesRequests;

    public function __invoke(Request $request, Coupon $coupon): JsonResponse
    {
        $this->authorize('view', $coupon);

        return response()->json([
            'status' => 'success',
            'coupon' => $coupon
        ]);
    }
}