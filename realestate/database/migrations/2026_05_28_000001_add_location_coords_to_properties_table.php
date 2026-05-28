<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->string('country')->nullable()->after('location');
            $table->string('state')->nullable()->after('country');
            $table->string('city')->nullable()->after('state');
            $table->decimal('latitude', 10, 7)->nullable()->after('city');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->index('country');
            $table->index('state');
            $table->index('city');
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropIndex(['country']);
            $table->dropIndex(['state']);
            $table->dropIndex(['city']);
            $table->dropColumn(['country', 'state', 'city', 'latitude', 'longitude']);
        });
    }
};
