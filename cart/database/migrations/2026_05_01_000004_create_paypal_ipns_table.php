<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('paypal_ipns', function (Blueprint $table) {
            $table->id();
            $table->string('txn_id')->unique()->nullable()->comment('PayPal transaction ID');
            $table->string('parent_txn_id')->nullable()->comment('Parent transaction ID for refunds/reversals');
            $table->enum('txn_type', [
                'web_accept',
                'subscr_signup',
                'subscr_payment',
                'subscr_failed',
                'subscr_cancel',
                'subscr_eot',
                'recurring_payment',
                'recurring_payment_profile_created',
                'recurring_payment_suspended_due_to_max_failed_payments',
                'recurring_payment_suspended',
                'recurring_payment_reactivated',
                'new_case',
                'recurring_payment_skipped',
                'recurring_payment_failed',
                'cart',
                'send_money',
                'reversal',
                'adjustment',
                'express_checkout',
                'masspay',
                'virtual_terminal',
                'check_echeck',
                'payer_creation_account',
                'update',
                'mp_signup',
                'merch_pmt',
                'ebay_txn_id',
                'auction_closing',
                'capture',
                'web_accept_refund',
                'refund'
            ])->comment('PayPal transaction type');
            $table->enum('payment_status', [
                'Canceled_Reversal',
                'Completed',
                'Denied',
                'Expired',
                'Failed',
                'Pending',
                'Processed',
                'Refunded',
                'Reversed',
                'Voided'
            ])->default('Pending')->comment('Payment status from PayPal');
            
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('cascade');
            $table->foreignId('payment_id')->nullable()->constrained('payments')->onDelete('cascade');
            
            $table->string('payer_email')->nullable();
            $table->string('payer_id')->nullable();
            $table->string('receiver_email')->nullable();
            $table->string('receiver_id')->nullable();
            
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            
            $table->decimal('mc_gross', 12, 2)->nullable()->comment('Gross amount');
            $table->decimal('mc_fee', 12, 2)->nullable()->comment('PayPal fee');
            $table->string('mc_currency')->default('USD')->comment('Currency code');
            
            $table->string('item_name')->nullable();
            $table->string('item_number')->nullable();
            $table->integer('quantity')->nullable();
            
            $table->string('custom')->nullable()->comment('Custom field (usually order ID)');
            $table->string('invoice')->nullable();
            
            $table->string('notify_version')->nullable();
            $table->string('verify_sign')->nullable();
            $table->enum('payment_type', ['instant', 'echeck'])->nullable();
            $table->string('address_status')->nullable();
            $table->string('address_street')->nullable();
            $table->string('address_city')->nullable();
            $table->string('address_state')->nullable();
            $table->string('address_zip')->nullable();
            $table->string('address_country')->nullable();
            
            $table->boolean('test_ipn')->default(false)->comment('Is this from sandbox');
            $table->boolean('verified')->default(false)->comment('IPN signature verified');
            $table->boolean('processed')->default(false)->comment('Payment processed');
            
            $table->text('raw_data')->nullable()->comment('Raw POST data from PayPal');
            $table->text('error_message')->nullable()->comment('Verification error');
            
            $table->timestamp('paypal_timestamp')->nullable()->comment('Timestamp from PayPal');
            $table->timestamps();
            
            $table->index('txn_id');
            $table->index('order_id');
            $table->index('payment_id');
            $table->index('verified');
            $table->index('processed');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paypal_ipns');
    }
};
