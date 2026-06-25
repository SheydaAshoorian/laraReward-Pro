<?php

namespace App\Actions;

use App\Models\Coupon;
use Illuminate\Support\Str;

class GenerateCouponAction
{
    public function execute(int $userId, int $pointsSpent): Coupon
    {
        $discountAmount = $pointsSpent * 1000;

        $couponCode = 'REWARD-' . strtoupper(Str::random(6));

        return Coupon::create([
            'user_id' => $userId,
            'code' => $couponCode,
            'discount_amount' => $discountAmount,
            'expires_at' => now()->addDays(30), 
            'is_used' => false,
        ]);
    }
}