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
        Schema::create('stripe_refunds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_id');
            $table->string('stripe_charge_id');
            $table->string('stripe_refund_id');
            $table->decimal('amount', 12, 2);
            $table->enum('type', ['full', 'partial'])->default('full');
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->string('reason')->nullable();
            $table->text('notes')->nullable();
            $table->json('stripe_response')->nullable();
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->foreign('payment_id')
                ->references('id')
                ->on('payments')
                ->cascadeOnDelete();

            $table->foreign('processed_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->index('stripe_charge_id');
            $table->index('stripe_refund_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stripe_refunds');
    }
};
