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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->comment('Unique identifier (stripe, paypal, card, bank_transfer)');
            $table->string('name')->comment('Display name');
            $table->string('description')->nullable()->comment('Description for admin');
            $table->enum('type', ['card', 'wallet', 'bank', 'other'])->default('card')->comment('Payment method type');
            $table->boolean('is_enabled')->default(false)->comment('Is payment method active');
            $table->boolean('is_default')->default(false)->comment('Is this the default method');
            $table->integer('sort_order')->default(0)->comment('Display order');
            $table->decimal('minimum_amount', 12, 2)->nullable()->comment('Minimum transaction amount');
            $table->decimal('maximum_amount', 12, 2)->nullable()->comment('Maximum transaction amount');
            $table->json('settings')->nullable()->comment('Method-specific settings');
            $table->string('icon_class')->nullable()->comment('Font Awesome icon class');
            $table->string('controller_path')->nullable()->comment('Controller class path');
            $table->string('service_path')->nullable()->comment('Service class path');
            $table->timestamps();
            
            $table->index('is_enabled');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
