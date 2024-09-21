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
        Schema::create('product_price_modifiers', function (Blueprint $table) {
            $table->id();
            $table->uuid('product_SKU');
            $table->unsignedBigInteger('modifier_id');
            $table->timestamps();
            $table->foreign('product_SKU')->references('SKU')->on('products')->onDelete('cascade');
            $table->foreign('modifier_id')->references('id')->on('price_modifiers')->onDelete('cascade');

            $table->unique(['product_SKU', 'modifier_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_price_modifiers');
    }
};
