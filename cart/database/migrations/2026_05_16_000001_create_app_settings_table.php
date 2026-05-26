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
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('store_name')->nullable();
            $table->string('store_email')->nullable();
            $table->string('store_phone')->nullable();
            $table->string('store_address')->nullable();
            $table->string('store_city')->nullable();
            $table->string('store_state')->nullable();
            $table->string('store_zip')->nullable();
            $table->string('store_country')->nullable();
            $table->string('business_registration')->nullable();
            $table->string('tax_id')->nullable();
            $table->string('currency')->default('USD');
            $table->string('timezone')->nullable();
            $table->integer('items_per_page')->default(20);
            $table->boolean('enable_notifications')->default(true);
            $table->boolean('enable_api')->default(false);
            $table->text('footer_contact_description')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('favicon_path')->nullable();
            $table->string('primary_color')->default('#2563eb');
            $table->string('secondary_color')->default('#1e40af');
            $table->string('accent_color')->default('#3b82f6');
            $table->string('dark_mode_bg')->default('#1f2937');
            $table->string('dark_mode_text')->default('#f3f4f6');
            $table->string('dark_mode_accent')->default('#60a5fa');
            $table->boolean('enable_dark_mode')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
};
