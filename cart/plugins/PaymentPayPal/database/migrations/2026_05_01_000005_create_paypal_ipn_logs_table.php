<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paypal_ipn_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ipn_log_id')->nullable()->constrained('paypal_ipns')->onDelete('set null');
            $table->longText('raw_post_data')->nullable();
            $table->enum('response_status', ['verified', 'invalid', 'pending'])->default('pending');
            $table->json('response_data')->nullable();
            $table->boolean('processed')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paypal_ipn_logs');
    }
};
