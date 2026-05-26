<?php

namespace Plugins\PaymentPayPal\PaymentMethods;

use App\Models\Commerce\Order;
use App\Models\Payment\PaymentMethod as PaymentMethodModel;
use Plugins\PaymentPayPal\Services\PayPalService;
use App\PaymentMethods\PaymentMethodInterface;

class PayPalPaymentMethod implements PaymentMethodInterface
{
    protected PayPalService $paypalService;
    protected PaymentMethodModel $config;

    public function __construct()
    {
        $this->config = PaymentMethodModel::where('key', 'paypal')->first();
    }

    public function getKey(): string
    {
        return 'paypal';
    }

    public function getName(): string
    {
        return 'PayPal';
    }

    public function getIcon(): string
    {
        return 'fab fa-paypal';
    }

    public function isEnabled(): bool
    {
        return $this->config && $this->config->is_enabled;
    }

    public function processPayment(Order $order): array
    {
        if (!$this->isEnabled()) {
            return ['success' => false, 'message' => 'PayPal payment method is not enabled'];
        }

        $this->paypalService = new PayPalService($order);
        return $this->paypalService->processPayment();
    }

    public function getRoutePrefix(): string
    {
        return 'paypal';
    }

    public function getViewPath(): string
    {
        return 'payment-paypal::checkout.paypal-checkout';
    }

    public function supportsRefunds(): bool
    {
        return true;
    }

    public function getConfig(): ?PaymentMethodModel
    {
        return $this->config;
    }
}
