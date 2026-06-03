<?php

namespace App\Actions;

use App\Models\Coupon;
use Illuminate\Support\Str;

class GenerateCouponAction
{
    public function execute(int $userId, int $pointsSpent): Coupon
    {
        // محاسبه مبلغ تخفیف بر اساس امتیاز (مثلاً هر ۱ امتیاز = ۱,۰۰۰ تومان تخفیف)
        $discountAmount = $pointsSpent * 1000;

        // تولید یک کد یکتا و خوانا مثل: REWARD-X7B29A
        $couponCode = 'REWARD-' . strtoupper(Str::random(6));

        return Coupon::create([
            'user_id' => $userId,
            'code' => $couponCode,
            'discount_amount' => $discountAmount,
            'expires_at' => now()->addDays(30), // ۳۰ روز مهلت استفاده دارد
            'is_used' => false,
        ]);
    }
}