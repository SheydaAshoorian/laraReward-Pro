<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReleasePendingPointsCommand extends Command
{
    // نام دستوری که در ترمینال زده می‌شود
    protected $signature = 'loyalty:release-points';
    
    // توضیحات دستور
    protected $description = 'انتقال امتیازهای معلق حاصل از خریدهای ۷ روز گذشته به موجودی قطعی کاربران';

    public function handle()
    {
        $this->info('شروع فرآیند بررسی و آزادسازی امتیازهای معلق...');

        // ۱. پیدا کردن سفارشات تکمیلی که ۷ روز از آنها گذشته و هنوز امتیازشان آزاد نشده
        $sevenDaysAgo = Carbon::now()->subDays(7);

        // گرفتن سفارشات به صورت قطعه قطعه (Chunk) برای جلوگیری از پر شدن حافظه RAM در دیتابیس‌های بزرگ
        Order::where('status', 'completed')
            ->where('points_earned', '>', 0)
            ->where('created_at', '<=', $sevenDaysAgo)
            // فرض می‌کنیم فیلدی داریم که مشخص می‌کند آیا امتیاز این سفارش قبلاً آزاد شده یا خیر
            ->where('is_points_released', false) 
            ->chunkById(100, function ($orders) {
                foreach ($orders as $order) {
                    
                    DB::transaction(function () use ($order) {
                        $user = User::where('id', $order->user_id)->lockForUpdate()->first();
                        
                        // پیدا کردن لاگ امتیاز معلق این سفارش
                        $pendingLog = $user->pointLogs()
                            ->where('order_id', $order->id)
                            ->where('status', 'pending')
                            ->first();

                        if ($pendingLog) {
                            // کسر از معلق و واریز به موجودی اصلی و قطعی
                            $user->decrement('pending_points', $order->points_earned);
                            $user->increment('points_balance', $order->points_earned);

                            // آپدیت وضعیت لاگ به تایید شده
                            $pendingLog->update(['status' => 'confirmed']);
                        }

                        // تیک تایید نهایی روی خود سفارش برای اینکه فردا دوباره پردازش نشود
                        $order->update(['is_points_released' => true]);
                    });
                }
            });

        $this->info('تمامی امتیازهای واجد شرایط با موفقیت آزاد شدند.');
    }
}