<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paypal_settings', function (Blueprint $table) {
            $table->id();
            $table->string('client_id');
            $table->string('client_secret');
            $table->enum('environment', ['sandbox', 'live'])->default('sandbox');
            $table->boolean('is_active')->default(true);
            $table->string('currency', 3)->default('USD');
            $table->string('business_name')->nullable();
            $table->string('business_email')->nullable();
            $table->decimal('minimum_amount', 10, 2)->default(0.01);
            $table->decimal('maximum_amount', 14, 2)->default(999999.99);
            $table->text('notes')->nullable();
            $table->timestamp('configured_at')->nullable();
            $table->unsignedBigInteger('configured_by')->nullable();
            $table->timestamps();

            $table->foreign('configured_by')
                ->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paypal_settings');
    }
};
