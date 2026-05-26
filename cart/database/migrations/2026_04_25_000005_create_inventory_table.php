<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->integer('warehouse_qty')->default(0);
            $table->integer('reserved_qty')->default(0);
            $table->integer('available_qty')->default(0);
            $table->timestamp('last_restock_at')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->unique('product_id');
            $table->index('available_qty');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
