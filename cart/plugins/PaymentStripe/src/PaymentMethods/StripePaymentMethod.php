<?php

namespace Plugins\PaymentStripe\PaymentMethods;

use App\Models\Commerce\Order;
use App\Models\Payment\PaymentMethod as PaymentMethodModel;
use Plugins\PaymentStripe\Services\StripeService;
use App\PaymentMethods\PaymentMethodInterface;

class StripePaymentMethod implements PaymentMethodInterface
{
    protected StripeService $stripeService;
    protected PaymentMethodModel $config;

    public function __construct()
    {
        $this->config = PaymentMethodModel::where('key', 'stripe')->first();
    }

    public function getKey(): string
    {
        return 'stripe';
    }

    public function getName(): string
    {
        return 'Credit/Debit Card';
    }

    public function getIcon(): string
    {
        return 'fas fa-credit-card';
    }

    public function isEnabled(): bool
    {
        return $this->config && $this->config->is_enabled;
    }

    public function processPayment(Order $order): array
    {
        if (!$this->isEnabled()) {
            return ['success' => false, 'message' => 'Stripe payment method is not enabled'];
        }

        $this->stripeService = new StripeService($order);
        return $this->stripeService->processPayment();
    }

    public function getRoutePrefix(): string
    {
        return 'card-payment';
    }

    public function getViewPath(): string
    {
        return 'payment-stripe::checkout.card-payment';
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
