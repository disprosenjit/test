<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique()->index();
            $table->string('part_number')->index();
            $table->string('name');
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('vessel_type_id')->nullable();
            $table->text('description')->nullable();
            $table->json('specifications')->nullable()->comment('JSON format for flexible specs');
            $table->decimal('price', 12, 2);
            $table->decimal('cost', 12, 2)->nullable();
            $table->integer('stock_qty')->default(0);
            $table->string('weight')->nullable();
            $table->string('dimensions')->nullable();
            $table->string('image_url')->nullable();
            $table->json('images')->nullable()->comment('Array of image URLs');
            $table->boolean('is_active')->default(true);
            $table->integer('view_count')->default(0);
            $table->decimal('rating', 3, 2)->default(0);
            $table->timestamps();

            // Indexes for search and filtering
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('set null');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('vessel_type_id')->references('id')->on('vessel_types')->onDelete('set null');
            
            $table->index(['brand_id', 'is_active']);
            $table->index(['category_id', 'is_active']);
            $table->index(['vessel_type_id', 'is_active']);
            $table->fullText(['name', 'description', 'part_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
