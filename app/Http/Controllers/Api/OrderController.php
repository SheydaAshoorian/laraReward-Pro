<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Actions\Order\CreateOrderAction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

class OrderController extends Controller
{
    public function __construct(
        protected CreateOrderAction $createOrderAction
    ) {}

    public function store(Request $request): JsonResponse
    {
        // ۱. اعتبارسنجی اولیه ساختار داده‌های ورودی
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            // ۲. تلاش برای اجرای اکشن ثبت سفارش (درون کپسول تراکنش)
            $order = $this->createOrderAction->execute(
                $request->user(), 
                $validated['items']
            );

            // اگر همه چیز موفقیت‌آمیز بود، کاربر به درگاه پرداخت هدایت می‌شود
            return response()->json([
                'success' => true,
                'message' => 'سفارش با موفقیت ثبت شد و امتیازات موقتاً بلوکه شدند.',
                'order_id' => $order->id,
                'payment_url' => route('payment.redirect', ['order' => $order->id])
            ], 201);

        } catch (Exception $e) {
            
            // ۳. مدیریت خطاهای احتمالی (مثل کمبود موجودی، قطع دیتابیس و...)
            // ثبت لاگ ارور واقعی در فایل لاراول برای دیباگ خودمان
            \Log::error('خطا در ثبت سفارش: ' . $e->getMessage());

            // فرستادن یک پیام خطای محترمانه و تمیز به فرانت‌اِند برای نمایش به کاربر
            return response()->json([
                'success' => false,
                'message' => 'متأسفانه در فرآیند ثبت سفارش خطایی رخ داده است. لطفاً مجدداً تلاش کنید.',
                'error_dev' => config('app.debug') ? $e->getMessage() : null // نمایش ارور واقعی فقط در حالت توسعه
            ], 500);
        }
    }
}