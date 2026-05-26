<?php

namespace Plugins\PaymentBank\Controllers;

use App\Models\Commerce\Order;
use App\Models\Payment\PaymentMethod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BankTransferPaymentController extends Controller
{
    /**
     * Display bank transfer details
     */
    public function show(Order $order)
    {
        if (auth()->check() && $order->user_id !== auth()->id()) {
            abort(403);
        }

        if (!PaymentMethod::isEnabled('bank_transfer')) {
            return redirect("/orders/{$order->id}")
                ->with('error', 'Bank transfer payment method is not available');
        }

        if ($order->payment_status !== 'pending') {
            return redirect("/orders/{$order->id}")
                ->with('error', 'This order does not require payment');
        }

        return view('payment-bank::checkout.bank-transfer', compact('order'));
    }
}
