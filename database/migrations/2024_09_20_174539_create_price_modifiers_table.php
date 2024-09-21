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
        Schema::create('price_modifiers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type', 50);
            $table->decimal('value', 10, 2);
            $table->decimal('amount_threshold', 10, 2)->nullable();
            $table->integer('quantity_threshold')->nullable();
            $table->boolean('is_percentage')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('apply_to_order')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_modifiers');
    }
};
