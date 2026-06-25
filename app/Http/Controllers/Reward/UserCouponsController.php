<?php

namespace App\Http\Controllers\Reward;

use App\Http\Controllers\Controller;
use App\Http\Resources\Reward\CouponResource; 
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log; 
use Exception;

class UserCouponsController extends Controller
{

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $coupons = $request->user()
                ->coupons()
                ->latest()
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => CouponResource::collection($coupons)
            ], 200);

        } catch (Exception $e) {
            Log::error('خطا در دریافت لیست کوپن‌های کاربر', [
                'user_id' => $request->user()->id,
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'مشکلی در لود اطلاعات تاریخچه جوایز به وجود آمده است. لطفاً بعداً تلاش کنید.'
            ], 500);
        }
    }
}