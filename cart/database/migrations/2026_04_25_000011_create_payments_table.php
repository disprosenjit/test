<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->enum('method', ['bank_transfer', 'upi', 'credit_card', 'debit_card']);
            $table->enum('status', ['pending', 'approved', 'failed', 'refunded'])->default('pending');
            $table->decimal('amount', 12, 2);
            $table->string('transaction_reference')->nullable();
            $table->string('gateway_response')->nullable();
            $table->json('metadata')->nullable();
            $table->string('proof_file_path')->nullable()->comment('For bank transfer proofs');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->index(['order_id', 'status']);
            $table->unique(['order_id', 'transaction_reference']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
