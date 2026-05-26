<?php

namespace App\PaymentMethods;

use App\Models\Commerce\Order;

interface PaymentMethodInterface
{
    /**
     * Get payment method key
     */
    public function getKey(): string;

    /**
     * Get payment method name
     */
    public function getName(): string;

    /**
     * Get payment method icon
     */
    public function getIcon(): string;

    /**
     * Check if method is enabled
     */
    public function isEnabled(): bool;

    /**
     * Process payment
     */
    public function processPayment(Order $order): array;

    /**
     * Get route prefix
     */
    public function getRoutePrefix(): string;

    /**
     * Get view path
     */
    public function getViewPath(): string;

    /**
     * Check if method supports refunds
     */
    public function supportsRefunds(): bool;
}
