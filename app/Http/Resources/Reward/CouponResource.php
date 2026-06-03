<?php

namespace App\Http\Resources\Reward;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{

    public function toArray(Request $request): array
    {

    return [
            'id' => $this->id,
            'coupon_code' => $this->code, 
            'amount' => $this->discount_amount,
            'is_redeemed' => $this->is_used,
            'expires_at' => $this->expires_at ? $this->expires_at->toIso8601String() : null, // فرمت استاندارد تاریخ برای فرانت‌اِند
            'created_date' => $this->created_at->diffForHumans(), // نمایش تاریخ به صورت زمان گذشته (مثلا: "۲ روز پیش")
        ];
    }
}