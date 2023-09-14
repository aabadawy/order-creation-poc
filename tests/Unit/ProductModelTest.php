<?php

use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

test('should have a name attribute', function () {

    $expectedName = 'Beef Burger';

    $product = Product::factory()->createOne(['name' => $expectedName]);

    expect($product->name)->toEqual($expectedName);
});

test('should attach ingredients to product', function () {
    $product = Product::factory()->createOne();

    $ingredients = Ingredient::factory(3)->create();

    expect($product->ingredients()->count())->toEqual(0);

    $product_ingredients = $ingredients
        ->map(fn (Ingredient $ingredient) => [
            'ingredient_id' => $ingredient->getKey(),
            'quantity' => $ingredient->current_quantity,
        ])->keyBy('ingredient_id')->toArray();

    $product->ingredients()->sync($product_ingredients);

    expect($product->ingredients()->count())->toEqual(3);

    expect($product->ingredients()->first()->name)->toEqual($ingredients->first()->name);
});
