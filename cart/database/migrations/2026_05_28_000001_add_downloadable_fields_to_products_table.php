<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('product_type')->default('physical')->after('description');
            $table->string('download_file_path')->nullable()->after('images');
            $table->string('download_file_name')->nullable()->after('download_file_path');
            $table->string('download_file_mime_type')->nullable()->after('download_file_name');
            $table->unsignedBigInteger('download_file_size')->nullable()->after('download_file_mime_type');

            $table->index(['product_type', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['product_type', 'is_active']);
            $table->dropColumn([
                'product_type',
                'download_file_path',
                'download_file_name',
                'download_file_mime_type',
                'download_file_size',
            ]);
        });
    }
};