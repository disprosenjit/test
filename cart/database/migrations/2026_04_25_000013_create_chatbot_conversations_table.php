<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chatbot_conversations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('session_id')->index();
            $table->json('messages')->comment('Array of messages with role (user/bot) and content');
            $table->enum('status', ['active', 'closed', 'escalated'])->default('active');
            $table->boolean('escalated_to_support')->default(false);
            $table->string('support_email')->nullable();
            $table->text('escalation_reason')->nullable();
            $table->timestamp('escalated_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index('user_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_conversations');
    }
};
