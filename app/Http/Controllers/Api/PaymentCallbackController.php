<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Actions\Order\CompleteOrderAction;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Exception;

class PaymentCallbackController extends Controller
{
    public function __construct(
        protected CompleteOrderAction $completeOrderAction
    ) {}

    /**
     * متدی که بانک پس از تراکنش صدا می‌زند
     */
    public function handleCallback(Request $request, Order $order): RedirectResponse
    {
        try {
            // در پروژه واقعی، اینجا توکن و وضعیت تراکنش را از $request (پاسخ بانک) راستی‌آزمایی می‌کنیم.
            // به عنوان نمونه فرض می‌کنیم بانک پارامتر status=success را می‌فرستد.
            $isSuccess = $request->get('status') === 'success';

            // صدا زدن اکشنِ مدیریت وضعیت امتیازها و سفارش
            $this->completeOrderAction->execute($order, $isSuccess);

            if ($isSuccess) {
                // هدایت کاربر به صفحه موفقیت در فرانت‌اِند (Next.js)
                return redirect()->to(config('app.frontend_url') . '/checkout/success?order=' . $order->id);
            }

            // هدایت کاربر به صفحه خطای پرداخت در فرانت‌اِند
            return redirect()->to(config('app.frontend_url') . '/checkout/failed?order=' . $order->id);

        } catch (Exception $e) {
            \Log::error("خطا در کال‌بک درگاه پرداخت سفارش #{$order->id}: " . $e->getMessage());
            return redirect()->to(config('app.frontend_url') . '/checkout/failed?error=system_error');
        }
    }
}