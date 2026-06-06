<?php

namespace App\Actions\Order;

use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class CompleteOrderAction
{
    /**
     * @param Order $order سفارشی که از درگاه برگشته است
     * @param bool $isPaymentSuccessful وضعیت پرداخت از سمت بانک
     */
    public function execute(Order $order, bool $isPaymentSuccessful): void
    {
        DB::transaction(function () use ($order, $isPaymentSuccessful) {
            
            // 🔒 قفل کردن ردیف کاربر برای امنیت تراکنش مالی
            $user = User::where('id', $order->user_id)->lockForUpdate()->first();

            // پیدا کردن لاگ امتیازی که در مرحله قبل بلوکه شده بود
            $blockedLog = $user->pointLogs()
                ->where('order_id', $order->id)
                ->where('status', 'blocked')
                ->first();

            if ($isPaymentSuccessful) {
                // --- سناریو آ: پرداخت موفق بود ---
                
                // ۱. تغییر وضعیت سفارش به تکمیل شده
                $order->update(['status' => 'completed']);

                if ($blockedLog) {
                    // ۲. کسر قطعی امتیازهای بلوکه شده
                    $user->decrement('blocked_points', $blockedLog->points);
                    
                    // ۳. تغییر وضعیت لاگ به قطعی شده (Spent)
                    $blockedLog->update(['status' => 'confirmed']);
                }

                // ۴. محاسبه و تخصیص امتیاز جدید بابت این خرید (مثلاً به ازای هر ۱۰ هزار تومان، ۱ امتیاز)
                $newPointsEarned = (int) floor($order->total_amount / 10000);
                
                if ($newPointsEarned > 0) {
                    // انتقال به صف انتظار ۷ روزه (فرآیند مرجوعی که قبلاً ساختیم)
                    $user->increment('pending_points', $newPointsEarned);
                    $order->update(['points_earned' => $newPointsEarned]);

                    // ثبت لاگ امتیاز جدید با وضعیت pending
                    $user->pointLogs()->create([
                        'order_id' => $order->id,
                        'points' => $newPointsEarned,
                        'type' => 'credit',
                        'status' => 'pending',
                        'reason' => "امتیاز معلق بابت خرید موفق شماره #{$order->id}",
                    ]);
                }

            } else {
                // --- سناریو ب: پرداخت ناموفق بود ---
                
                // ۱. تغییر وضعیت سفارش به لغو شده یا ناموفق
                $order->update(['status' => 'failed']);

                if ($blockedLog) {
                    // ۲. بازگرداندن امتیازهای بلوکه شده به حساب اصلی کاربر
                    $user->decrement('blocked_points', $blockedLog->points);
                    $user->increment('points_balance', $blockedLog->points);

                    // ۳. تغییر وضعیت لاگ به لغو شده
                    $blockedLog->update(['status' => 'cancelled']);
                }
            }
        });
    }
}