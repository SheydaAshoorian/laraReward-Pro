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
            
            $user = User::where('id', $order->user_id)->lockForUpdate()->first();

            $blockedLog = $user->pointLogs()
                ->where('order_id', $order->id)
                ->where('status', 'blocked')
                ->first();

            if ($isPaymentSuccessful) {
                
                $order->update(['status' => 'completed']);

                if ($blockedLog) {
                    $user->decrement('blocked_points', $blockedLog->points);
                    
                    $blockedLog->update(['status' => 'confirmed']);
                }

                $newPointsEarned = (int) floor($order->total_amount / 10000);
                
                if ($newPointsEarned > 0) {
                    $user->increment('pending_points', $newPointsEarned);
                    $order->update(['points_earned' => $newPointsEarned]);

                    $user->pointLogs()->create([
                        'order_id' => $order->id,
                        'points' => $newPointsEarned,
                        'type' => 'credit',
                        'status' => 'pending',
                        'reason' => "امتیاز معلق بابت خرید موفق شماره #{$order->id}",
                    ]);
                }

            } else {
          
                $order->update(['status' => 'failed']);

                if ($blockedLog) {
                    $user->decrement('blocked_points', $blockedLog->points);
                    $user->increment('points_balance', $blockedLog->points);

                    $blockedLog->update(['status' => 'cancelled']);
                }
            }
        });
    }
}