<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Log;
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

 
    public function handleCallback(Request $request, Order $order): RedirectResponse
    {
        try {
            $isSuccess = $request->get('status') === 'success';

            $this->completeOrderAction->execute($order, $isSuccess);

            if ($isSuccess) {
                return redirect()->to(config('app.frontend_url') . '/checkout/success?order=' . $order->id);
            }

            return redirect()->to(config('app.frontend_url') . '/checkout/failed?order=' . $order->id);

        } catch (Exception $e) {
            Log::error("خطا در کال‌بک درگاه پرداخت سفارش #{$order->id}: " . $e->getMessage());
            return redirect()->to(config('app.frontend_url') . '/checkout/failed?error=system_error');
        }
    }
}