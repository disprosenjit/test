<?php

namespace Plugins\PaymentBank\PaymentMethods;

use App\Models\Commerce\Order;
use App\Models\Payment\Payment;
use App\Models\Payment\PaymentMethod as PaymentMethodModel;
use App\PaymentMethods\PaymentMethodInterface;

class BankTransferPaymentMethod implements PaymentMethodInterface
{
    protected PaymentMethodModel $config;

    public function __construct()
    {
        $this->config = PaymentMethodModel::where('key', 'bank_transfer')->first();
    }

    public function getKey(): string
    {
        return 'bank_transfer';
    }

    public function getName(): string
    {
        return 'Bank Transfer';
    }

    public function getIcon(): string
    {
        return 'fas fa-university';
    }

    public function isEnabled(): bool
    {
        return $this->config && $this->config->is_enabled;
    }

    public function processPayment(Order $order): array
    {
        if (!$this->isEnabled()) {
            return ['success' => false, 'message' => 'Bank transfer payment method is not enabled'];
        }

        $payment = Payment::create([
            'order_id' => $order->id,
            'method' => 'bank_transfer',
            'status' => 'pending',
            'amount' => $order->total,
            'metadata' => [
                'bank_details' => $this->getBankDetails(),
            ],
        ]);

        return [
            'success' => true,
            'payment_id' => $payment->id,
            'status' => 'pending',
            'bank_details' => $this->getBankDetails(),
            'amount' => $order->total,
            'order_number' => $order->order_number,
        ];
    }

    public function getRoutePrefix(): string
    {
        return 'bank-transfer';
    }

    public function getViewPath(): string
    {
        return 'payment-bank::checkout.bank-transfer';
    }

    public function supportsRefunds(): bool
    {
        return false;
    }

    public function getBankDetails(): array
    {
        return [
            'account_holder' => config('payment.bank.account_holder', 'Ship Spare Parts Store'),
            'bank_name' => config('payment.bank.bank_name', 'HDFC Bank'),
            'account_number' => config('payment.bank.account_number', '50100123456789'),
            'ifsc_code' => config('payment.bank.ifsc_code', 'HDFC0000050'),
            'branch' => config('payment.bank.branch', 'Mumbai'),
        ];
    }

    public function getConfig(): ?PaymentMethodModel
    {
        return $this->config;
    }
}
