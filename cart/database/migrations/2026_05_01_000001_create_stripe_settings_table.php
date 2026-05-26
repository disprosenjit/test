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
        Schema::create('stripe_settings', function (Blueprint $table) {
            $table->id();
            $table->string('publishable_key');
            $table->string('secret_key');
            $table->string('webhook_secret')->nullable();
            $table->enum('environment', ['test', 'live'])->default('test');
            $table->boolean('is_active')->default(true);
            $table->string('business_name')->nullable();
            $table->string('business_email')->nullable();
            $table->string('currency', 3)->default('USD');
            $table->decimal('minimum_amount', 10, 2)->default(0.50);
            $table->decimal('maximum_amount', 14, 2)->default(999999.99);
            $table->json('webhook_events')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('configured_at')->nullable();
            $table->unsignedBigInteger('configured_by')->nullable();
            $table->timestamps();

            $table->foreign('configured_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stripe_settings');
    }
};
