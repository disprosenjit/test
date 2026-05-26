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
        Schema::create('stripe_cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stripe_customer_id');
            $table->string('payment_method_id')->unique();
            $table->string('brand');
            $table->string('last_four');
            $table->unsignedSmallInteger('exp_month');
            $table->unsignedSmallInteger('exp_year');
            $table->string('cardholder_name')->nullable();
            $table->boolean('is_default')->default(false);
            $table->json('metadata')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();

            $table->foreign('stripe_customer_id')
                ->references('id')
                ->on('stripe_customers')
                ->onDelete('cascade');

            $table->index('payment_method_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stripe_cards');
    }
};
