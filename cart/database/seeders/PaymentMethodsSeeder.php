<?php

namespace Database\Seeders;

use App\Models\Payment\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = [
            [
                'key' => 'stripe',
                'name' => 'Credit/Debit Card (Stripe)',
                'description' => 'Accept payments via Stripe with credit and debit cards',
                'type' => 'card',
                'is_enabled' => true,
                'is_default' => true,
                'sort_order' => 1,
                'icon_class' => 'fas fa-credit-card',
                'controller_path' => 'Plugins\\PaymentStripe\\Controllers\\StripePaymentController',
                'service_path' => 'Plugins\\PaymentStripe\\Services\\StripeService',
            ],
            [
                'key' => 'paypal',
                'name' => 'PayPal',
                'description' => 'Accept payments via PayPal checkout',
                'type' => 'wallet',
                'is_enabled' => false,
                'is_default' => false,
                'sort_order' => 2,
                'icon_class' => 'fab fa-paypal',
                'controller_path' => 'Plugins\\PaymentPayPal\\Controllers\\PayPalPaymentController',
                'service_path' => 'Plugins\\PaymentPayPal\\Services\\PayPalService',
            ],
            [
                'key' => 'card',
                'name' => 'Card Payment (Direct)',
                'description' => 'Accept card details directly from checkout form',
                'type' => 'card',
                'is_enabled' => false,
                'is_default' => false,
                'sort_order' => 3,
                'icon_class' => 'fas fa-credit-card',
                'controller_path' => 'Plugins\\PaymentStripe\\Controllers\\StripePaymentController',
                'service_path' => 'Plugins\\PaymentStripe\\Services\\StripeService',
            ],
            [
                'key' => 'bank_transfer',
                'name' => 'Bank Transfer',
                'description' => 'Accept payments via manual bank transfer with proof upload',
                'type' => 'bank',
                'is_enabled' => false,
                'is_default' => false,
                'sort_order' => 4,
                'icon_class' => 'fas fa-university',
                'controller_path' => null,
                'service_path' => null,
            ],
        ];

        foreach ($methods as $method) {
            PaymentMethod::updateOrCreate(
                ['key' => $method['key']],
                $method
            );
        }
    }
}
