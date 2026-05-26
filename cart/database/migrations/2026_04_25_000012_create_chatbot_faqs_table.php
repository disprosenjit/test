<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chatbot_faqs', function (Blueprint $table) {
            $table->id();
            $table->text('question');
            $table->longText('answer');
            $table->string('category')->index();
            $table->string('keywords')->nullable()->comment('Comma-separated keywords for matching');
            $table->integer('display_order')->default(0);
            $table->integer('view_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->fullText(['question', 'answer', 'keywords']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_faqs');
    }
};
