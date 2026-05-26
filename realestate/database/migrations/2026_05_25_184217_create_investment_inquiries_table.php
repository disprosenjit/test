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
        Schema::create('investment_inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('contact_person');
            $table->string('email');
            $table->string('phone');
            $table->text('inquiry_details');
            $table->decimal('investment_amount', 15, 2)->nullable();
            $table->string('investment_type')->nullable(); // residential, commercial, mixed
            $table->string('preferred_location')->nullable();
            $table->string('status')->default('new'); // new, reviewed, in-discussion, closed
            $table->timestamps();
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investment_inquiries');
    }
};
