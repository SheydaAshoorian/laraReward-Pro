<?php

namespace App\Actions\Order;

use App\Models\User;

class CalculateCartValueAction
{
    /**
     * شبیه‌سازی دیتابیس محصولات بدون نیاز به مدل Product
     * (یک آرایه ثابت از آیدی و قیمت محصولات برای تست مینی‌پروژه)
     */
    private array $mockProductsDatabase = [
        1 => 1500000, // محصول یک: ۱.۵ میلیون تومان
        2 => 4000000, // محصول دو: ۴ میلیون تومان
        3 => 6000000, // محصول سه: ۶ میلیون تومان
    ];

    public function execute(User $user, array $cartItems): array
    {
        $rawTotal = 0;

        // بهینه‌سازی حجم بالا: استخراج سریع قیمت‌ها بدون کوئری‌های تکراری
        foreach ($cartItems as $item) {
            $productId = $item['id'];
            $quantity = $item['quantity'];

            // چک کردن قیمت از دیتابیس فرضی ما
            if (isset($this->mockProductsDatabase[$productId])) {
                $rawTotal += $this->mockProductsDatabase[$productId] * $quantity;
            }
        }

        $discountFromPoints = 0;
        $pointsToBlock = 0;

        // شرط طلایی تو: بالای ۱۰ میلیون تومان خرید
        if ($rawTotal >= 10000000 && $user->points_balance > 0) {
            
            // هر ۱ امتیاز = ۱,۰۰۰ تومان تخفیف
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
            'raw_total' => $rawTotal,
            'discount_from_points' => $discountFromPoints,
            'final_amount' => $rawTotal - $discountFromPoints,
            'points_to_be_blocked' => $pointsToBlock,
        ];
    }
}