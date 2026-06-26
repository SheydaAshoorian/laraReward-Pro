<?php

namespace App\Actions\Order;

use App\Models\User;
use App\Models\Product; 

class CalculateCartValueAction
{
   
    public function execute(User $user, array $cartItems): array
    {
        $rawTotal = 0;
        $validatedItems = [];

        $productIds = collect($cartItems)->pluck('id')->toArray();

        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        foreach ($cartItems as $item) {
            $productId = $item['id'];
            $quantity = $item['quantity'];

            if (isset($products[$productId])) {
                $productPrice = $products[$productId]->price; 
                $rawTotal += $productPrice * $quantity;

                $validatedItems[] = [
                    'id' => $productId,
                    'quantity' => $quantity,
                    'price' => $productPrice
                ];
            }
        }

        $discountFromPoints = 0;
        $pointsToBlock = 0;

        if ($rawTotal >= 10000000 && $user->points_balance > 0) {
            
            $maxDiscountPossible = $user->points_balance * 1000;

            if ($maxDiscountPossible > $rawTotal) {
                $discountFromPoints = $rawTotal;
                $pointsToBlock = (int) ceil($rawTotal / 1000);
            } else {
                $discountFromPoints = $maxDiscountPossible;
                $pointsToBlock = $user->points_balance;
            }
        }

        return [
            'raw_total'            => $rawTotal,
            'discount_from_points' => $discountFromPoints,
            'final_amount'         => $rawTotal - $discountFromPoints,
            'points_to_be_blocked' => $pointsToBlock,
            'validated_items'      => $validatedItems,
        ];
    }
}