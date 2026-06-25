<?php

namespace App\Actions\Order;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem; 
use Illuminate\Support\Facades\DB;
use Exception;

class CreateOrderAction
{
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
            
            $securedUser = User::where('id', $user->id)->lockForUpdate()->first();

            $cartCalculations = $this->calculateCartValueAction->execute($securedUser, $cartItems);

            $finalAmount = $cartCalculations['final_amount'];
            $pointsToBlock = $cartCalculations['points_to_be_blocked'];
            $validatedItems = $cartCalculations['validated_items'] ?? $cartItems; 

            $order = Order::create([
                'user_id' => $securedUser->id,
                'total_amount' => $finalAmount,
                'status' => 'pending',
                'points_earned' => 0, 
            ]);

            foreach ($validatedItems as $item) {
              
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['id'],
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'] ?? 0, 
                ]);
            }

            if ($pointsToBlock > 0) {
                $securedUser->decrement('points_balance', $pointsToBlock);
                $securedUser->increment('blocked_points', $pointsToBlock);

                $securedUser->pointLogs()->create([
                    'order_id' => $order->id,
                    'points' => $pointsToBlock,
                    'type' => 'debit',
                    'status' => 'blocked', 
                    'reason' => "بلوکه شدن امتیاز بابت تخفیف سفارش شماره #{$order->id}",
                ]);
            }

            return $order;
        });
    }
}