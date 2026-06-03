<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    // فیلدهایی که اجازه ثبت انبوه (Mass Assignment) دارند
    protected $fillable = [
        'user_id',
        'code',
        'discount_amount',
        'expires_at',
        'is_used',
    ];

    // مشخص کردن اینکه تاریخ انقضا باید به صورت شیء کربن (Carbon) یا دیت‌تایم خوانده شود
    protected $casts = [
        'expires_at' => 'datetime',
        'is_used' => 'boolean',
    ];

  
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function coupons(): HasMany
    {
        return $this->hasMany(Coupon::class);
    }
}