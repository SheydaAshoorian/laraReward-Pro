<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReleasePendingPointsCommand extends Command
{
    protected $signature = 'loyalty:release-points';
    
    protected $description = 'انتقال امتیازهای معلق حاصل از خریدهای ۷ روز گذشته به موجودی قطعی کاربران';

    public function handle()
    {
        $this->info('شروع فرآیند بررسی و آزادسازی امتیازهای معلق...');

        $sevenDaysAgo = Carbon::now()->subDays(7);

        Order::where('status', 'completed')
            ->where('points_earned', '>', 0)
            ->where('created_at', '<=', $sevenDaysAgo)
            ->where('is_points_released', false) 
            ->chunkById(100, function ($orders) {
                foreach ($orders as $order) {
                    
                    DB::transaction(function () use ($order) {
                        $user = User::where('id', $order->user_id)->lockForUpdate()->first();
                        
                        $pendingLog = $user->pointLogs()
                            ->where('order_id', $order->id)
                            ->where('status', 'pending')
                            ->first();

                        if ($pendingLog) {
                            $user->decrement('pending_points', $order->points_earned);
                            $user->increment('points_balance', $order->points_earned);

                            $pendingLog->update(['status' => 'confirmed']);
                        }

                        $order->update(['is_points_released' => true]);
                    });
                }
            });

        $this->info('تمامی امتیازهای واجد شرایط با موفقیت آزاد شدند.');
    }
}