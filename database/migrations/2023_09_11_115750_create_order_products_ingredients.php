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
        Schema::create('order_products_ingredients', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();

            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();

            $table->foreignId('ingredient_id')->nullable()->constrained('ingredients')->nullOnDelete();

            $table->unique([
                'order_id',
                'product_id',
                'ingredient_id',
            ], 'order_product_ingredient_unique_index');

            $table->float('quantity')->comment('the ingredient quantity ber grams');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_products_ingredients');
    }
};
