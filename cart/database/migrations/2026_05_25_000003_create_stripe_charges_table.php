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
        Schema::create('stripe_charges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_id');
            $table->unsignedBigInteger('stripe_customer_id');
            $table->unsignedBigInteger('stripe_card_id')->nullable();
            $table->string('stripe_charge_id')->unique();
            $table->string('stripe_payment_intent_id')->unique()->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3);
            $table->enum('status', ['pending', 'succeeded', 'failed', 'cancelled'])->default('pending');
            $table->string('failure_message')->nullable();
            $table->string('failure_code')->nullable();
            $table->json('metadata')->nullable();
            $table->json('receipt_data')->nullable();
            $table->timestamp('charged_at')->nullable();
            $table->timestamps();

            $table->foreign('payment_id')
                ->references('id')
                ->on('payments')
                ->onDelete('cascade');

            $table->foreign('stripe_customer_id')
                ->references('id')
                ->on('stripe_customers')
                ->onDelete('cascade');

            $table->foreign('stripe_card_id')
                ->references('id')
                ->on('stripe_cards')
                ->onDelete('set null');

            $table->index('stripe_charge_id');
            $table->index('stripe_payment_intent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stripe_charges');
    }
};
