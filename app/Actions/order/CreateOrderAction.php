<?php

namespace App\Actions\Order;

use App\Models\User;
use App\Models\Order;
use App\Models\Product; // فرض می‌کنیم در آینده این مدل اضافه می‌شود یا از ماک استفاده می‌کنیم
use Illuminate\Support\Facades\DB;
use Exception;

class CreateOrderAction
{
    // تزریق اکشن محاسباتی که در گام قبل با هم نوشتیم
    public function __construct(
        protected CalculateCartValueAction $calculateCartValueAction
    ) {}

    /**
     * @param User $user کاربر ثبت‌کننده سفارش
     * @param array $cartItems اقلام سبد خرید [['id' => 1, 'quantity' => 2]]
     * @return Order
     * @throws Exception
     */
    public function execute(User $user, array $cartItems): Order
    {
        return DB::transaction(function () use ($user, $cartItems) {
            
            // 🔒 گام ۱: قفل کردن ردیف کاربر در دیتابیس برای جلوگیری از Race Condition
            // تا زمانی که این تراکنش تمام نشده، هیچ ریکوئست همزمانی نمی‌تواند امتیاز این کاربر را دستکاری کند
            $securedUser = User::where('id', $user->id)->lockForUpdate()->first();

            // گام ۲: محاسبه مبالغ و امتیاز قابل کسر با استفاده از اکشن بهینه قبلی
            $cartCalculations = $this->calculateCartValueAction->execute($securedUser, $cartItems);

            $finalAmount = $cartCalculations['final_amount'];
            $pointsToBlock = $cartCalculations['points_to_be_blocked'];

            // گام ۳: ثبت فاکتور سفارش در جدول با وضعیت pending
            $order = Order::create([
                'user_id' => $securedUser->id,
                'total_amount' => $finalAmount,
                'status' => 'pending',
                'points_earned' => 0, // هنوز نهایی نشده پس امتیاز صفره
            ]);

            // گام ۴: اگر خرید بالای ۱۰ میلیون بوده و کاربر امتیاز داشته، امتیازها را بلوکه کن
            if ($pointsToBlock > 0) {
                // کسر از موجودی قطعی و انتقال به بخش بلوکه شده
                $securedUser->decrement('points_balance', $pointsToBlock);
                $securedUser->increment('blocked_points', $pointsToBlock);

                // ثبت سابقه (Log) برای شفافیت مالی سیستم
                // وضعیت این لاگ را هم 'held' یا 'blocked' می‌گذاریم چون هنوز نهایی نشده
                $securedUser->pointLogs()->create([
                    'order_id' => $order->id,
                    'points' => $pointsToBlock,
                    'type' => 'debit',
                    'status' => 'blocked', 
                    'reason' => "بلوکه شدن امتیاز بابت تخفیف سفارش شماره #{$order->id}",
                ]);
            }

            // خروجی نهایی مدل سفارش برای فرستادن کاربر به درگاه پرداخت
            return $order;
        });
    }
}