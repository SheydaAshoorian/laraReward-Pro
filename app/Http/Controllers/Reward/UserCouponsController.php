<?php

namespace App\Http\Controllers\Reward;

use App\Http\Controllers\Controller;
use App\Http\Resources\Reward\CouponResource; // لایه محافظ و تبدیل‌کننده خروجی
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log; // برای ثبت خطاهای سرور
use Exception;

class UserCouponsController extends Controller
{
    /**
     * دریافت لیست کدهای تخفیف و جوایز کاربر با فرمت استاندارد Resource
     * @security BearerAuth
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            // ۱. دریافت کوپن‌های کاربر جاری به ترتیب جدیدترین‌ها
            $coupons = $request->user()
                ->coupons()
                ->latest()
                ->get();

            // ۲. عبور دادن دیتای خام از لایه محافظ Resource و بازگرداندن پاسخ موفقیت
            return response()->json([
                'status' => 'success',
                'data' => CouponResource::collection($coupons)
            ], 200);

        } catch (Exception $e) {
            // ۳. در صورت بروز هرگونه خطای دیتابیسی یا سیستمی، آن را در لایه بک‌اِند لاگ می‌کنیم
            Log::error('خطا در دریافت لیست کوپن‌های کاربر', [
                'user_id' => $request->user()->id,
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // ۴. برگرداندن یک پاسخ دیپلماتیک و امن به فرانت‌اِند
            return response()->json([
                'status' => 'error',
                'message' => 'مشکلی در لود اطلاعات تاریخچه جوایز به وجود آمده است. لطفاً بعداً تلاش کنید.'
            ], 500);
        }
    }
}